"use strict";
const id = "ask-provider";
document.addEventListener("DOMContentLoaded", () => {
    const dialog = document.getElementById(id);
    if (dialog !== null) {
        dialog.close();
        dialog.showModal();
    }
});
//# sourceMappingURL=providers-dialog.js.map