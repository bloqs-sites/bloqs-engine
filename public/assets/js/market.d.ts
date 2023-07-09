type Bloq = {
    id: number;
    category: Promise<CategoryCode>;
    creator: Promise<Person>;
    description: string;
    hasAdultConsideration: boolean;
    keywords: string[];
    name: string;
    image: string;
};
type CategoryCode = {
    color: string;
    description: string;
    href: string;
    id: number;
    name: string;
};
type Person = {
    description: string;
    href: string;
    id: number;
    image: string;
    level: number;
    name: string;
};
export declare function getBloqs(): AsyncGenerator<Bloq>;
export {};
//# sourceMappingURL=market.d.ts.map