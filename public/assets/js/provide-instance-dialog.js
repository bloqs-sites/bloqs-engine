"use strict";
const sch = "https://bloqs.torres-dev.workers.dev/sch";
const dialog_id = "instance-asker";
const form_name = "instance-asker";
const input_name = "instance";
document.addEventListener("DOMContentLoaded", () => {
    const dialog = document.getElementById(dialog_id);
    if (dialog instanceof HTMLDialogElement) {
        dialog.close();
        dialog.showModal();
    }
    const form = document.forms.namedItem(form_name);
    if (form === null) {
        throw new Error("form === null");
    }
    const radio = form[input_name];
    if (!(radio instanceof RadioNodeList)) {
        throw new Error("");
    }
    const input = radio.item(0);
    if (!(input instanceof HTMLInputElement)) {
        throw new Error("");
    }
    input.addEventListener("change", async () => {
        const valid = form.reportValidity();
        if (!valid) {
            return;
        }
        let res;
        try {
            res = await fetch(input.value);
        }
        catch {
            return;
        }
        if (!res.ok) {
            return;
        }
        let cnf;
        try {
            cnf = await res.json();
        }
        catch {
            return;
        }
        if (typeof cnf === "object") {
            if (cnf.$scheme !== sch) {
                return;
            }
            if (typeof cnf.name !== "string") {
                return;
            }
            console.log(cnf.name);
        }
        else {
            return;
        }
    });
});
//# sourceMappingURL=provide-instance-dialog.js.map