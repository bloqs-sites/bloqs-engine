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
use Bloqs\Models;
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
use function React\Async\async;
use function React\Async\await;
use function React\Promise\all;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\debug;

class Offer extends Controller
{
    public function before(): void
    {
        parent::before();

        $this->needsConf();
    }

    #[Route]
    #[MethodsAllowed(HTTPVerb::GET, HTTPVerb::POST)]
    #[View(NativeViewLoader::class)]
    public function create(string ...$segments): void
    {
        $this->needsAccount();
        if ($this->respondEarly()) {
            return;
        }

        //$api = new URI(cnf("REST", "domain"));

        //if (!($segments[0] ?? false)) {
        //    if ($this->req->getMethod() == "POST") {
        //        $o = Models\Offer::new($api);

        //        $offers = $this->req->getParsedBody()["offers"] ?? [];
        //        $promises = [];
        //        foreach ($offers as $product) {
        //            $promises[] = async(static fn (): Product => Product::new($api, $product))();
        //        }

        //        $o->setAvailability($this->body("availability"));
        //        $o->setAvailabilityStarts(\DateTimeImmutable::createFromFormat("Y-m-d\\TH:i", $this->body("availabilityStarts")));
        //        $o->setAvailabilityEnds(\DateTimeImmutable::createFromFormat("Y-m-d\\TH:i", $this->body("availabilityEnds")));
        //        $o->setOfferedBy(Person::new($api, $this->body("offeredBy")));
        //        $o->setPrice(floatval($this->body("price")));
        //        $o->setItemsOffered(await(all($promises)));

        //        $o->insert();

        //        $res = $o->response->response();
        //        switch ($res->getStatusCode()) {
        //            case 401:
        //                $this->redirect(baseurl("credentials/log", [
        //                    "redirect" => baseurl("offer/create")
        //                ]));
        //                return;
        //            case 403:
        //                throw new NoPermissionsException($res);
        //        }

        //        $this->redirect("/");
        //        return;
        //    }

        //    $find = Models\Person::getFinder($api)
        //        ->withID(cnf("REST", "myself") ?? "@")
        //        ->run();

        //    $profiles = [...$find];

        //    /** @var \TorresDeveloper\Pull\Pull $res */
        //    $res = $find->getReturn();

        //    switch ($res->response()->getStatusCode()) {
        //        case 404:
        //            $profiles = [];
        //            break;
        //        case 401:
        //            $this->redirect(baseurl("credentials/log", [
        //                "redirect" => baseurl("offer/create")
        //            ]));
        //            return;
        //        case 403:
        //            throw new NoPermissionsException($res->response());
        //    }

        //    $this->load("php/offer/mk", [
        //        "profiles" => $profiles,
        //        "cur" => Models\Person::new($api, getProfile()),
        //    ]);
        //} elseif ($segments[0] === "items-offered") {
        //    $find = Models\Person::getFinder($api)
        //        ->withID(cnf("REST", "myself") ?? "@")
        //        ->run();
        //    $profiles = [...$find];

        //    /** @var \TorresDeveloper\Pull\Pull $res */
        //    $res = $find->getReturn();

        //    switch ($res->response()->getStatusCode()) {
        //        case 404:
        //            $profiles = [];
        //            break;
        //        case 401:
        //            $this->redirect(baseurl("credentials/log", [
        //                "redirect" => baseurl("offer/create")
        //            ]));
        //            return;
        //        case 403:
        //            throw new NoPermissionsException($res->response());
        //    }
        //    $id = $this->req->getQueryParams()["offeredBy"];
        //    $cur = null;
        //    /** @var \Bloqs\Models\Person $i */
        //    foreach ($profiles  as $i) {
        //        if ($i->getId() === $id) {
        //            $cur = $i;
        //            break;
        //        }
        //    }

        $this->load("php/offer/mk", []);
        //}

        //$this->res = $this->res->withStatus(404);
    }
}
