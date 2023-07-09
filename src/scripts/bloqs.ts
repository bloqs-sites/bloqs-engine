import { trim } from "./utils.js";

export function hydratate_create_tags() {
    const form = document.forms.namedItem("bloqs-create");

    if (form === null) {
        return;
    }

    const keywords = form["keywords"];

    if (!(keywords instanceof HTMLTextAreaElement)) {
        return;
    }

    const separator = ";";
    keywords.addEventListener("input", () => {
        const text = trim(keywords.value, separator);

        const tags_splited = text.split(separator);
        const tags: string[] = [];
        const whiteSpace = new RegExp(/^\s*$/);
        for (const i of tags_splited) {
            const pos = tags.length;

            if (i.match(whiteSpace)) {
                if (tags[pos] === undefined) {
                    tags[pos] = "";
                }
                tags[pos] += i.length ? i : separator;
                continue;
            }

            if (tags[pos]?.length) {
                tags[pos + 1] = "";
            }

            if (tags[pos] === undefined) {
                tags[pos] = "";
            }
            tags[pos] += i;
        }

        console.log(tags_splited, tags);
    });
}
