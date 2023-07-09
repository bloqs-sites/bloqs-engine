<?php

/**
 *        MVCBase - A base for a MVC.
 *        Copyright (C) 2022-2023  João Torres
 *
 *        This program is free software: you can redistribute it and/or modify
 *        it under the terms of the GNU Affero General Public License as
 *        published by the Free Software Foundation, either version 3 of the
 *        License, or (at your option) any later version.
 *
 *        This program is distributed in the hope that it will be useful,
 *        but WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *        GNU Affero General Public License for more details.
 *
 *        You should have received a copy of the GNU Affero General Public License
 *        along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @package Bloqs\\Core
 * @author João Torres <torres.dev@disroot.org>
 * @copyright Copyright (C) 2022-2023  João Torres
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License
 * @license https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3
 *
 * @since 0.15.0
 * @version 1.0.0
 */

declare(encoding="UTF-8");

declare(strict_types=1);

namespace Bloqs\Core;

use TorresDeveloper\Polyglot\Lang;

use function TorresDeveloper\MVC\config;
use function TorresDeveloper\MVC\req;

//const TOKEN = "__Host-bloqs-auth";
const CREDENTIALS_TOKEN = "_Host-bloqs-auth";
const PROFILE_TOKEN = "_Host-bloqs-profile";
const LANG_TOKEN = "_Host-bloqs-lang";

function getToken(): ?string
{
    return req()->getCookieParams()[CREDENTIALS_TOKEN] ?? null;
}

function getClient(): ?array
{
    $token = getToken(req());

    if ($token === null) {
        return $token;
    }

    return json_decode(base64_decode(explode(".", $token)[1], true), true);
}

function setToken(string $token): void
{
    setcookie(CREDENTIALS_TOKEN, $token, [
        "expires" => 0,
        "path" => "/",
        "domain" => config()->get("domain"),
        //"secure" => true,
        //"httponly" => true,
        "samesite" => "Strict"
    ]);
}

function issetToken(): bool
{
    return null !== getToken();
}

function unsetToken(): void
{
    $req = req();
    $cookies = $req->getCookieParams();
    unset(
        $_COOKIE[CREDENTIALS_TOKEN],
        $_SESSION[CREDENTIALS_TOKEN],
        $cookies[CREDENTIALS_TOKEN]
    );
    $req = $req->withCookieParams($cookies);
    setcookie(CREDENTIALS_TOKEN, "", [
        "expires" => -1,
        "path" => "/",
    ]);

    unsetProfile();
}

function getProfile(): ?string
{
    return req()->getCookieParams()[PROFILE_TOKEN] ?? null;
}

function setProfile(string $profile): void
{
    setcookie(PROFILE_TOKEN, $profile, [
        "expires" => 0,
        "path" => "/",
        "domain" => config()->get("domain"),
        //"secure" => true,
        //"httponly" => true,
        "samesite" => "Strict"
    ]);
}

function issetProfile(): bool
{
    return null !== getProfile();
}

function unsetProfile(): void
{
    $req = req();
    $cookies = $req->getCookieParams();
    unset(
        $_COOKIE[PROFILE_TOKEN],
        $_SESSION[PROFILE_TOKEN],
        $cookies[PROFILE_TOKEN]
    );
    $req = $req->withCookieParams($cookies);
    setcookie(PROFILE_TOKEN, "", [
        "expires" => -1,
        "path" => "/",
    ]);
}

function getLang(): Lang
{
    return new Lang(req()->getCookieParams()[LANG_TOKEN] ?? "en");
}

function setLang(Lang $lang): void
{
    setcookie(LANG_TOKEN, $lang->getCode(), [
        "expires" => 0,
        "path" => "/",
        "domain" => config()->get("domain"),
        //"secure" => true,
        //"httponly" => true,
        "samesite" => "Strict"
    ]);
}
