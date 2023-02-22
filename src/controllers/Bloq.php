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
 * @since 0.0.3
 * @version 0.0.1
 */

declare(strict_types=1);

namespace Bloqs\Controllers;

use Bloqs\Config\BloqsCfg;
use Bloqs\Models\Category;
use Bloqs\Models\Product;
use TorresDeveloper\HTTPMessage\HTTPVerb;
use TorresDeveloper\MVC\Controller\Controller;
use TorresDeveloper\MVC\Controller\Route;
use TorresDeveloper\MVC\View\Loader\NativeViewLoader;
use TorresDeveloper\MVC\View\View;

use function Bloqs\Core\api;
use function TorresDeveloper\MVC\baseurl;

/**
 * The Controller to manage the client session
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
class Bloq extends Controller
{
    #[Route]
    #[View(NativeViewLoader::class)]
    public function make(): void
    {
        if ($this->getVerb() === HTTPVerb::POST) {
            $bloq = new Product(api());
            $bloq->setName($this->body("name"));
            $bloq->setDescription($this->body("description"));
            $bloq->setPreference(Category::selectOne(api(), $this->body("preference")));
            $image = $this->req->getUploadedFiles()["image"];
            if ($image) {
                $bloq->setImage($this->req->getUploadedFiles()["image"]);
            }
            $bloq->setHasAdultConsideration($this->body("adult") === "on");
            $bloq->insert();

            $this->res = $this->res
                ->withHeader("Location", baseurl())
                ->withStatus(201);

            return;
        }

        $preferences = Category::getFinder(api())->run();

        $this->load("php/bloq", [
            "preferences" => $preferences,
            "adultAllowed" => BloqsCfg::getCfg()
                ->tryGet("allow_adult_consideration"),
        ]);
    }
}
