"use strict";

import { readCookie, writeCookie } from "bolacha";

type Account = {};

const CREDENTIALS_COOKIE = "_Host-bloqs-auth";
const PROFILE_COOKIE = "_Host-bloqs-profile";
const LANG_COOKIE = "_Host-bloqs-lang";
const CONF_COOKIE = "CONF";
// const PROVIDER_COOKIE = "PROVIDER";
// const HIST_COOKIE = "HIST";

let jwt: string | null | undefined;
let acc: Account | null | undefined;

export function getJwt(): string | null {
    if (jwt === undefined) {
        jwt = readCookie(CREDENTIALS_COOKIE);
    }

    return jwt;
}

export function getAccount(): Account | null {
    if (jwt === undefined) {
        jwt = readCookie(CREDENTIALS_COOKIE);
    }

    if (typeof jwt === "string" && acc === undefined) {
        const [, payload,] = jwt.split(".");

        acc = typeof payload === "string" ? JSON.parse(atob(payload)) : null;
    }

    return acc ?? null;
}

export function getProfile(): number | null {
    const id = readCookie(PROFILE_COOKIE);

    return typeof id === "string" ? parseInt(id, 10) : null;
}

export function getLang(): string | null {
    return readCookie(LANG_COOKIE);
}

export function setLang(lang: string): void {
    writeCookie(LANG_COOKIE, lang, {
        path: "/",
        expires: new Date(Date.now() + 1000 * 60 * 60 * 24 * 7),
        domain: window.location.hostname,
        samesite: "strict",
    });
}

export function getConf(): {
    REST: {
        domain: string
    }
} | null {
    const conf = readCookie(CONF_COOKIE);

    return (typeof conf === "string") ? JSON.parse(conf) : null;
}
