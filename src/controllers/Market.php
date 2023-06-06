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
 * @package TorresDeveloper\\BlocksEngine\\Controllers
 * @author João Torres <torres.dev@disroot.org>
 * @copyright Copyright (C) 2022 João Torres
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License
 * @license https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3
 *
 * @since 0.0.3
 * @version 0.0.2
 */

declare(strict_types=1);

namespace Bloqs\Controllers;

use Bloqs\Core\ClientData;
use TorresDeveloper\HTTPMessage\HTTPVerb;
use TorresDeveloper\MVC\Controller\Controller;
use TorresDeveloper\MVC\Controller\MethodsAllowed;
use TorresDeveloper\MVC\Controller\Route;
use TorresDeveloper\MVC\View\Loader\NativeViewLoader;
use TorresDeveloper\MVC\View\View;

use function Bloqs\Config\cnf;
use function Bloqs\Config\hist;
use function Bloqs\Config\provide;
use function Bloqs\Config\provider;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\debug;

/**
 * The default Controller.
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
class Market extends Controller
{
    #[Route]
    #[View(NativeViewLoader::class)]
    public function index(): void
    {
        //debug($this->req->getCookieParams());

        $this->load("php/market", [
            "logged" => ClientData::getClient($this->req),
            "provider" => provider(),
            "name" => cnf("name") ?? "",
            "fav_instances" => null,
            "instances_hist" => hist(),
        ]);
    }

    #[Route]
    #[MethodsAllowed(HTTPVerb::POST)]
    public function provide(): void
    {
        $provider = $this->body("instance");

        if ($provider !== null) {
            provide($provider);
        }

        $this->res = $this->res
            ->withStatus(308)
            ->withHeader("Location", baseurl("market/index"));
    }
}
