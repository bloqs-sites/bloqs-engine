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

use Bloqs\Core\Controller;
use Bloqs\Errors\NoPermissionsException;
use Bloqs\Models\Category;
use TorresDeveloper\HTTPMessage\HTTPVerb;
use TorresDeveloper\HTTPMessage\URI;
use TorresDeveloper\MVC\Controller\Route;
use TorresDeveloper\MVC\View\Loader\NativeViewLoader;
use TorresDeveloper\MVC\View\View;

use function Bloqs\Config\cnf;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\debug;

/**
 * The Controller to manage the instance preferences
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
final class Preferences extends Controller
{
    public function before(): void
    {
        parent::before();

        $this->needsConf();
        $this->needsSuper();
    }

    #[Route]
    #[View(NativeViewLoader::class)]
    public function index(): void
    {
        $api = new URI(cnf("REST", "domain"));

        switch ($this->req->getMethod()) {
            case HTTPVerb::GET->value:
                $this->load("php/preferences", [
                    "resources" => Category::getFinder($api)->run(),
                ]);
                return;
            case HTTPVerb::POST->value:
                $o = new Category($api);
                $o->setName($this->body("name"));
                $o->setDescription($this->body("description"));
                $o->setColor($this->body("color"));
                $o->insert();

                $res = $o->actionRes();
                if (!$res->ok()) {
                    switch ($res->getStatusCode()) {
                        case 403:
                            throw new NoPermissionsException($res);
                        case 401:
                            $this->redirect(baseurl("credentials/log", [
                                "redirect" => baseurl("preferences"),
                            ]));
                            return;
                    }
                    throw new \RuntimeException($res->text());
                }

                $this->redirect(baseurl("preferences"));
                return;
        }

        $this->res = $this->res->withStatus(405);
    }
}
