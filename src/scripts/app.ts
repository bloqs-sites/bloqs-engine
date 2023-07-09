import { writeCookie } from "bolacha";
import { hydratate_create_tags } from "./bloqs.js";
import { hydratate_dialog } from "./provide-instance-dialog.js";
import { getBloqs } from "./market.js";

const [, ...segments] = window.location.pathname.split("/");

switch (segments?.[0]) {
    case "bloq":
        switch (segments?.[1]) {
            case "make":
                document.addEventListener("DOMContentLoaded", () => {
                    hydratate_create_tags();
                });
                break;
        }
        break;
}

document.addEventListener("DOMContentLoaded", () => {
    hydratate_dialog();
});

document.addEventListener("DOMContentLoaded", async () => {
    const template = document.getElementById("bloq-template");
    if (template === null || !(template instanceof HTMLTemplateElement)) {
        return;
    }

    const parent = template.parentNode;
    if (parent === null) {
        return;
    }
    for await (const i of getBloqs()) {
        const fragment = template.content.cloneNode(true) as DocumentFragment;
        const bloq = fragment.querySelector(".bloq");
        if (bloq === null) {
            return;
        }
        parent.appendChild(bloq);
        const img = bloq.querySelector<HTMLImageElement>(".image");
        if (img !== null) {
            img.src = i.image;
        }
        const name = bloq.querySelector<HTMLSpanElement>(".name");
        if (name !== null) {
            name.innerText = i.name;
        }
        const price = bloq.querySelector<HTMLSpanElement>(".price");
        if (price !== null) {
            price.innerText = "34";
        }
        const c = bloq.querySelector<HTMLSpanElement>(".c");
        if (c !== null) {
            i.category.then(v => {
                c.innerText = v.name;
            });
        }
        const keywords = bloq.querySelector<HTMLSpanElement>(".untitle");
        if (keywords !== null) {
            keywords.innerText = `[${i.keywords.length}]`;
        }
        const nsfw = bloq.querySelector<HTMLSpanElement>(".nsfw");
        if (nsfw !== null) {
            if (!i.hasAdultConsideration) {
                nsfw.remove();
            }
        }
        const author = bloq.querySelector<HTMLAnchorElement>(".a");
        if (author !== null) {
            i.creator.then(v => {
                author.href = window.location.origin + "/profile/" + v.id;
                author.innerText = v.name;
            });
        }
        const aggregater = bloq.querySelector<HTMLAnchorElement>(".aggregaterating");
        if (aggregater !== null) {
            aggregater.href = window.location.origin + "/bloq/index/" + i.id + "#reviews";
        }
        const visit = bloq.querySelector<HTMLAnchorElement>(".visit");
        if (visit !== null) {
            visit.href = window.location.origin + "/bloq/index/" + i.id;
        }
    }
});

writeCookie("JS", true.toString(), { "max-age": 15 * 60 });
