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
 * @package Bloqs\\Controllers
 * @author João Torres <torres.dev@disroot.org>
 * @copyright Copyright (C) 2022-2023 João Torres
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License
 * @license https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3
 *
 * @since 0.0.5
 * @version 0.0.1
 */

declare(strict_types=1);

namespace Bloqs\Controllers;

use TorresDeveloper\MVC\Controller\Controller;
use TorresDeveloper\MVC\Controller\Route;
use TorresDeveloper\MVC\View\Loader\NativeViewLoader;
use TorresDeveloper\MVC\View\View;

use function Bloqs\Config\cnf;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\debug;

/**
 * The Controller to manage the client credentials
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
class Credentials extends Controller
{
    #[Route]
    #[View(NativeViewLoader::class)]
    public function index(): void
    {
        if (cnf() === null) {
            $this->res = $this->res
                ->withStatus(308)
                ->withHeader("Location", baseurl());

            return;
        }

        $type = $this->req->getQueryParams()[cnf("auth", "authTypeQueryParam")
            ?? "type"] ?? null;

        $methods_raw = cnf("auth", "supported") ?? [];
        $methods = [];
        foreach ($methods_raw as $i) {
            $methods[$i] = true;
        }

        if ($type === null) {
            $this->load("php/credentials/sign", [
                "type" => cnf("auth", "authTypeQueryParam") ?? "type",
                "action" => cnf("auth", "domain") . cnf("auth", "signPath"),
                "redirect" => cnf("auth", "redirectQueryParam") ?? "redirect",
                "location" => baseurl("/"),
                "methods" => $methods,
            ]);
        }
    }
}
