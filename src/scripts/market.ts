import { pull } from "polypull";
import { getConf, getJwt } from "./cookies.js";

type BloqRaw = {
    id?: number,
    category: number,
    creator: number,
    description: string,
    hasAdultConsideration: boolean,
    keywords: string[],
    name: string,
    releaseDate: Date,
};
type Bloq = {
    id: number,
    category: Promise<CategoryCode>,
    creator: Promise<Person>,
    description: string,
    hasAdultConsideration: boolean,
    keywords: string[],
    name: string,
    image: string,
};
type CategoryCode = {
    color: string
    description: string,
    href: string
    id: number,
    name: string
}
type Person = {
    description: string,
    href: string
    id: number,
    image: string,
    level: number,
    name: string
}

type Cache<T> = Map<number, Promise<T>>;
const bloqMap: Cache<Bloq> = new Map();
const creatorMap: Cache<Person> = new Map();
const categoryMap: Cache<CategoryCode> = new Map();

const conf = getConf();
const headers: Record<string, string> = {};
const jwt = getJwt();
if (jwt !== null) {
    headers["Authorization"] = `Bearer ${jwt}`;
}

export async function* getBloqs(): AsyncGenerator<Bloq> {
    if (conf === null) {
        return;
    }

    const url = `${conf.REST.domain}/bloq`;
    const body: BloqRaw[] = await pull(url, { headers }).then(async r => {
        if (!r.ok) {
            console.error(`[${r.status}]\t${await r.text()}`);
            throw new Error(await r.text() ?? "")
        }

        return r.json();
    });

    for (const i of body) {
        if (typeof i.id !== "number") {
            continue;
        }

        const savedBloq = bloqMap.get(i.id);
        if (savedBloq !== undefined) {
            yield savedBloq;
            continue;
        }

        const bloq = Promise.resolve<Bloq>({
            id: i.id,
            category: one(categoryMap, i.category, "preference"),
            creator: one(creatorMap, i.creator, "profile"),
            description: i.description,
            hasAdultConsideration: i.hasAdultConsideration,
            keywords: i.keywords,
            name: i.name,
            image: "https://picsum.photos/254.webp"
        });

        bloqMap.set(i.id, bloq);

        yield bloq;
    }
}

function one<T>(map: Cache<T>, id: number, name: string): Promise<T> {
    if (conf === null) {
        throw new Error();
    }

    const saved = map.get(id);
    if (saved !== undefined) {
        return saved;
    } else {
        const i = pull(`${conf.REST.domain}/${name}/${id}`, { headers }).then(async r => {
            if (!r.ok) {
                console.error(`[${r.status}]\t${await r.text()}`);
                throw new Error(await r.text() ?? "")
            }

            return r.json();
        });

        map.set(id, i);

        return i;
    }
}
