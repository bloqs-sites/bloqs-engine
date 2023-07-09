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
use Bloqs\Models\Product;
use TorresDeveloper\HTTPMessage\HTTPVerb;
use TorresDeveloper\HTTPMessage\URI;
use TorresDeveloper\MVC\Controller\MethodsAllowed;
use TorresDeveloper\MVC\Controller\Route;
use TorresDeveloper\MVC\View\Loader\NativeViewLoader;
use TorresDeveloper\MVC\View\View;

use function Bloqs\Config\cnf;
use function Bloqs\Core\getProfile;
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
    public function before(): void
    {
        parent::before();

        $this->needsConf();
    }

    #[Route]
    #[MethodsAllowed(HTTPVerb::GET)]
    #[View(NativeViewLoader::class)]
    public function index(string $id): void
    {
        if (!$id) {
            $this->redirect(baseurl());
            return;
        }

        $api = new URI(cnf("REST", "domain"));

        $bloq = Product::new($api, $id);

        if ($bloq === null) {
            $this->redirect(baseurl());
            return;
        }

        $res = $bloq->response->response();
        switch ($res->getStatusCode()) {
            case 404:
                $this->redirect(baseurl());
                return;
            case 401:
                $this->redirect(baseurl("credentials/log", [
                    "redirect" => baseurl("bloq/make")
                ]));
                return;
            case 403:
                throw new NoPermissionsException($res);
        }

        $this->load("php/info", [
            "bloq" => $bloq,
        ]);
    }

    #[Route]
    #[MethodsAllowed(HTTPVerb::GET, HTTPVerb::POST)]
    #[View(NativeViewLoader::class)]
    public function make(): void
    {
        $api = new URI(cnf("REST", "domain"));

        if ($this->getVerb() === HTTPVerb::POST) {
            $bloq = new Product($api);
            $bloq->setName($this->body("name"));
            $bloq->setDescription($this->body("description"));
            $separator = ";";
            $keywords = explode($separator, $this->body("keywords"));
            $tags = [];
            foreach ($keywords as $i) {
                if ($i === "") {
                    if (count($tags) !== 0) {
                        $tags[count($tags) - 1] .= ";";
                    }
                    continue;
                }

                $tags[] = $i;
            }
            if (count($tags) && str_ends_with($tags[count($tags) - 1], ";")) {
                $tags[count($tags) - 1] = substr($tags[count($tags) - 1], 0, -1);
            }
            $bloq->setKeywords($tags);
            $preference = Category::new($api, $this->body("preference"));
            $res = $preference->response->response();
            switch ($res->getStatusCode()) {
                case 404:
                    $profiles = [];
                    break;
                case 401:
                    $this->redirect(baseurl("credentials/log", [
                        "redirect" => baseurl("bloq/make")
                    ]));
                    return;
                case 403:
                    throw new NoPermissionsException($res);
            }
            $bloq->setPreference($preference);
            $creator = Person::new($api, $this->body("creator"));
            $res = $creator->response->response();
            switch ($res->getStatusCode()) {
                case 404:
                    $profiles = [];
                    break;
                case 401:
                    $this->redirect(baseurl("credentials/log", [
                        "redirect" => baseurl("bloq/make")
                    ]));
                    return;
                case 403:
                    throw new NoPermissionsException($res);
            }
            $bloq->setCreator($creator);
            $image = $this->req->getUploadedFiles()["image"];
            if ($image) {
                $bloq->setImage($this->req->getUploadedFiles()["image"]);
            }
            $bloq->setHasAdultConsideration($this->body("adult") === "on");
            $bloq->insert();
            $res = $bloq->actionRes();
            switch ($res->getStatusCode()) {
                case 404:
                    $profiles = [];
                    break;
                case 401:
                    $this->redirect(baseurl("credentials/log", [
                        "redirect" => baseurl("bloq/make")
                    ]));
                    return;
                case 403:
                    throw new NoPermissionsException($res);
            }

            $this->redirect(baseurl());

            return;
        }

        $preferences = Category::getFinder($api)->run();
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
                    "redirect" => baseurl("bloq/make")
                ]));
                return;
            case 403:
                throw new NoPermissionsException($res->response());
        }

        $this->load("php/bloq", [
            "preferences" => $preferences,
            "profiles" => $profiles,
            "cur" => Person::new($api, getProfile()),
        ]);
    }
}
