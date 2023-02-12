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

use Psr\Http\Message\ServerRequestInterface;

use function TorresDeveloper\MVC\config;

final class ClientData
{
    //public const TOKEN = "__Host-bloqs-auth";
    public const TOKEN = "_Host-bloqs-auth";

    private function __construct()
    {
    }

    public static function getToken(ServerRequestInterface $req): ?string
    {
        return $req->getCookieParams()[self::TOKEN] ?? null;
    }

    public static function getClient(ServerRequestInterface $req): ?array
    {
        $token = static::getToken($req);

        if ($token === null) {
            return $token;
        }

        return json_decode(base64_decode(explode(".", $token)[1], true), true);
    }

    public static function setToken(string $token): void
    {
        setcookie(self::TOKEN, $token, [
            "expires" => 0,
            "path" => "/",
            "domain" => config()->get("domain"),
            //"secure" => true,
            "httponly" => true,
            "samesite" => "Strict"
        ]);
    }

    public static function issetToken(ServerRequestInterface $req): bool
    {
        return isset($req->getCookieParams()[self::TOKEN]);
    }

    public static function unsetToken(ServerRequestInterface $req): void
    {
        $cookies = $req->getCookieParams();
        unset($_COOKIE[self::TOKEN], $cookies[self::TOKEN]);
        $req = $req->withCookieParams($cookies);
        setcookie(self::TOKEN, "", [
            "expires" => -1,
            "path" => "/",
        ]);
    }
}
