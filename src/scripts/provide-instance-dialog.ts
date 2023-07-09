"use strict";

//import { default as Ajv, type JSONSchemaType } from "ajv/dist/2020.js";
//import { default as addFormats } from "ajv-formats"
import { pull } from "polypull"
import type Resource from "polypull/lib/Resource.js";

const sch: string = "https://bloqs.torres-dev.workers.dev/sch";

//interface Schema {
//    name: string,
//}

//const validator = pull(sch).then((res) => {
//    if (!res.ok) {
//        throw new Error();
//    }
//
//    return res;
//}).then((res) => res.json()).then((sch: JSONSchemaType<Schema>) => {
//    const ajv = new Ajv.default();
//    addFormats.default(ajv);
//
//    console.log(sch);
//    return ajv.compile<Schema>(sch);
//});

const dialog_id: string = "instance-asker";
const form_name: string = "instance-asker";
const input_name: string = "bloq-instance";

document.addEventListener("DOMContentLoaded", () => {
});

export function hydratate_dialog() {
    const dialog = document.getElementById(dialog_id);

    if (dialog instanceof HTMLDialogElement) {
        dialog.addEventListener("close", () => {
            dialog.showModal();

            dialog.addEventListener("close", reload);
            dialog.addEventListener("cancel", reload);
        }, { once: true });

        dialog.close();
    }

    const form = document.forms.namedItem(form_name);

    if (form === null) {
        return;
    }

    form.addEventListener("submit", () => { });

    const radio = form[input_name];

    if (!(radio instanceof RadioNodeList)) {
        return;
    }

    const input = radio.item(0);

    if (!(input instanceof HTMLInputElement)) {
        return;
    }

    input.addEventListener("blur", validateURLInput);
    input.addEventListener("change", validateURLInput);
    input.addEventListener("input", validateURLInput);

    input.addEventListener("focus", async () => { });
}

async function validateURLInput(event: Event) {
    const input = <HTMLInputElement>event.target;

    if ((input.name !== input_name) || (input.type !== "url")) {
        return;
    }

    const { form } = input;

    if (form === null) {
        return;
    }

    const valid = form.reportValidity();
    if (!valid) {
        input.focus();
        return;
    }

    try {
        new URL(input.value);
    } catch {
        return;
    }

    let res: Resource;
    try {
        res = await pull(input.value);
    } catch {
        return;
    }

    if (!res.ok) {
        return;
    }

    let cnf: any;
    try {
        cnf = await res.json();
    } catch {
        return;
    }

    //if (await validator.then((v) => v(cnf))) {
    //    return;
    //}

    if (typeof cnf === "object") {
        if (cnf.$scheme !== sch) {
            return;
        }

        if (typeof cnf.name !== "string") {
            return;
        }

        console.log(cnf.name);
    } else {
        return;
    }
}

function reload() {
    window.location.reload();
}
