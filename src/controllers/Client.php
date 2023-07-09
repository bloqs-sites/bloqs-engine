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

use Bloqs\Core\Controller;
use Bloqs\Errors\NoPermissionsException;
use Bloqs\Models\Category;
use Bloqs\Models\Person;
use TorresDeveloper\HTTPMessage\HTTPVerb;
use TorresDeveloper\HTTPMessage\URI;
use TorresDeveloper\MVC\Controller\MethodsAllowed;
use TorresDeveloper\MVC\Controller\Route;
use TorresDeveloper\MVC\View\Loader\NativeViewLoader;
use TorresDeveloper\MVC\View\View;

use function Bloqs\Config\cnf;
use function Bloqs\Core\setProfile;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\debug;

/**
 * The Controller to manage the client session
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
class Client extends Controller
{
    public function before(): void
    {
        parent::before();

        $this->needsConf();
        $this->needsCredentials();
    }

    #[Route]
    #[View(NativeViewLoader::class)]
    public function index(): void
    {
        $api = new URI(cnf("REST", "domain"));

        switch ($this->req->getMethod()) {
            case HTTPVerb::GET->value:
                $find = Person::getFinder($api)
                    ->withID(cnf("REST", "myself") ?? "@")
                    ->run();
                $profiles = [...$find];
                /** @var \TorresDeveloper\Pull\Pull $res */
                $res = $find->getReturn();
                switch ($res->response()->getStatusCode()) {
                    case 404:
                        $profiles = [];
                        break;
                    case 401:
                        $this->redirect(baseurl("credentials/log", [
                            "redirect" => baseurl("client")
                        ]));
                        return;
                    case 403:
                        throw new NoPermissionsException($res->response());
                }

                $find = Category::getFinder($api)->run();
                $categories = [...$find];
                /** @var \TorresDeveloper\Pull\Pull $res */
                $res = $find->getReturn();
                switch ($res->response()->getStatusCode()) {
                    case 404:
                        $profiles = [];
                        break;
                    case 401:
                        $this->redirect(baseurl("credentials/log", [
                            "redirect" => baseurl("client")
                        ]));
                        return;
                    case 403:
                        throw new NoPermissionsException($res->response());
                }

                $this->load("php/select_profile", [
                    "profiles" => $profiles,
                    "preferences" => $categories,
                    "adultAllowed" => cnf("REST", "NSFW") ?? false,
                    "canCreate" => (cnf("REST", "profiles", "max") ?? 1) > count($profiles),
                ]);
                return;
            case HTTPVerb::POST->value:
                $o = new Person($api);
                $o->setName($this->body("name"));
                $o->setDescription($this->body("description"));
                $image = $this->req->getUploadedFiles()["image"] ?? null;
                if (isset($image)) {
                    $o->setImage($image);
                }
                $url = $this->body("url");
                if ($url) {
                    $o->setUrl(new URI($url));
                }
                if ((cnf("REST", "NSFW") ?? false) === true) {
                    $v = $this->body("adult");
                    $o->setHasAdultConsideration($v == "1" || $v == "true" || $v == "on" || $v == "yes");
                }
                $preferences = array_keys($this->body("preferences") ?? []);
                foreach ($preferences as $i) {
                    $like = new Category($api);
                    $like->setId((string) $i);
                    $o->addLike($like);
                }

                $o->insert();
                $res = $o->actionRes();
                switch ($res->getStatusCode()) {
                    case 401:
                        $this->redirect(baseurl("credentials/log", [
                            "redirect" => baseurl("client")
                        ]));
                        return;
                    case 403:
                        throw new NoPermissionsException($res);
                }

                $res = $o->actionRes();
                if (!$res->ok()) {
                    debug($res, $res->text());
                    $this->back();
                }

                $this->back();
                return;
        }
    }

    #[Route]
    #[MethodsAllowed(HTTPVerb::POST)]
    public function select(): void
    {
        $api = new URI(cnf("REST", "domain"));

        $o = Person::new($api, $this->body("id"));

        if ($o) {
            setProfile($o->getId());
        }

        $this->redirect(baseurl());
    }
}
