<?php

/**
 *    Blocks Engine - Template for a market place.
 *    Copyright (C) 2022-2023 João Torres
 *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Affero General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *    GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * @package TorresDeveloper\\BlocksEngine\\Config
 * @author João Torres <torres.dev@disroot.org>
 * @copyright Copyright (C) 2022-2023 João Torres
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License
 * @license https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3
 *
 * @since 0.0.5
 * @version 0.0.1
 */

declare(strict_types=1);

namespace Bloqs\Config;

use function TorresDeveloper\MVC\config;
use function TorresDeveloper\MVC\debug;
use function TorresDeveloper\MVC\now;
use function TorresDeveloper\MVC\unix;
use function TorresDeveloper\Pull\pull;

const CONF = "CONF";
const PROVIDER = "PROVIDER";
const HIST = "HIST";

function cnf(string ...$keys): mixed
{
    $cnf = ($_SESSION[CONF] ?? null);

    if (!isset($cnf)) {
        return null;
    }

    if (isset($cnf)) {
        $cnf = json_decode($cnf, true);
    }

    if (!is_array($cnf)) {
        return null;
    }

    foreach ($keys as $k) {
        $v = $cnf[$k] ?? null;

        if ($v === null) {
            return null;
        }

        if (is_array($v)) {
            $cnf = $v;
        } else {
            return $v;
        }
    }

    return $cnf;
}

function provide(string $provider): void
{
    $res = pull($provider);
    $_SESSION[CONF] = $res->text();
    $_SESSION[PROVIDER] = $provider;

    appendToProviderHist($provider);
}

function provider(): ?string
{
    return $_SESSION[PROVIDER] ?? null;
}

function hist(): array
{
    return json_decode($_COOKIE[HIST] ?? "[]", true);
}

function appendToProviderHist(): void
{
    $provider = provider();

    if ($provider === null) {
        return;
    }

    $hist = hist();

    $deleted = 0;
    foreach ($hist as $i => $p) {
        if ($p["uri"] === $provider) {
            $deleted++;
            unset($hist[$i]);
            continue;
        }

        unset($hist[$i]);
        $hist[$i - $deleted] = $p;
    }
    $hist[] = [
        "uri" => $provider,
        "name" => cnf("name"),
    ];

    setcookie(HIST, json_encode($hist), [
        "expires" => unix(now()) + 3600,
        "path" => "/",
        "domain" => config()->get("domain"),
    ]);
}
