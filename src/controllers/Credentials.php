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
use TorresDeveloper\HTTPMessage\HTTPVerb;
use TorresDeveloper\MVC\Controller\MethodsAllowed;
use TorresDeveloper\MVC\Controller\Route;
use TorresDeveloper\MVC\View\Loader\NativeViewLoader;
use TorresDeveloper\MVC\View\View;

use function Bloqs\Config\cnf;
use function Bloqs\Core\getClient;
use function Bloqs\Core\issetToken;
use function Bloqs\Core\setToken;
use function Bloqs\Core\unsetToken;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\Pull\pull;

/**
 * The Controller to manage the client credentials
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
final class Credentials extends Controller
{
    public function before(): void
    {
        $this->needsConf();

        parent::before();
    }

    #[Route]
    #[MethodsAllowed(HTTPVerb::GET)]
    #[View(NativeViewLoader::class)]
    public function sign(): void
    {
        $this->form("Sign In");
    }

    #[Route]
    #[MethodsAllowed(HTTPVerb::GET)]
    #[View(NativeViewLoader::class)]
    public function log(): void
    {
        $this->form("Log In");
    }

    private function form(string $title): void
    {
        $type = $this->req->getQueryParams()[cnf("auth", "queryParams", "type")
            ?? "type"] ?? null;

        $methods = cnf("auth", "supported") ?? [];

        if ($type === null) {
            $this->load("php/credentials/index", [
                "title" => $title,
                "action" => baseurl("credentials"),
                "redirect" => $this->req->getQueryParams()["redirect"] ?? null,
                "methods" => $methods,
                "client" => getClient()["client"] ?? null,
                "type" => getClient()["type"] ?? null,
            ]);
        }
    }

    #[Route]
    #[MethodsAllowed(HTTPVerb::POST)]
    public function basic(): void
    {
        $type = cnf("auth", "queryParams", "type") ?? "type";
        $resource = cnf("auth", "domain")
            . cnf("auth", "paths", "log")
            . "?$type=basic";

        try {
            $res = pull(
                $resource,
                HTTPVerb::POST,
                (array) $this->req->getParsedBody(),
                [
                    "Origin" => $this->req->getHeader("Origin")[0],
                ]
            )->response();
        } catch (\Throwable $th) {
            echo $th->__toString();
            $this->back();
            return;
        }

        $body = $res->json();

        if (!($body["validation"]["valid"] ?? false)) {
            $this->back();
            return;
        } else {
            $tk = $body["token"]["jwt"] ?? null;

            if ($tk) {
                setToken($body["token"]["jwt"]);
            }
        }

        $this->redirect($this->req->getQueryParams()["redirect"] ?: baseurl());
    }

    #[Route]
    #[MethodsAllowed(HTTPVerb::GET, HTTPVerb::POST)]
    #[View(NativeViewLoader::class)]
    public function grant(): void
    {
        if (!issetToken()) {
            $this->back();
            return;
        }

        $type = cnf("auth", "queryParams", "type") ?? "type";

        switch ($this->req->getMethod()) {
            case HTTPVerb::GET->value:
                $type = $this->req->getQueryParams()[$type] ?? null;

                $methods = cnf("auth", "supported") ?? [];

                if ($type === null) {
                    $this->load("php/credentials/grant", [
                        "type" => getClient()["type"],
                        "permissions" => $this->req->getQueryParams()["permission"],
                        "query" => "?permissions="
                            . implode(
                                "&permissions=",
                                $this->req->getQueryParams()["permission"]
                            )
                            . "&redirect="
                            . ($this->req->getHeader("Referer")[0] ?? ""),
                        "action" => baseurl("credentials"),
                        "methods" => $methods,
                    ]);
                } else {
                    $this->back();
                }

                return;
            case HTTPVerb::POST->value:
                $resource = cnf("auth", "domain")
                    . cnf("auth", "paths", "log")
                    . "?$type=basic&permissions=default&permissions="
                    . $this->req->getQueryParams()["permissions"];

                try {
                    $res = pull(
                        $resource,
                        HTTPVerb::POST,
                        [
                            "email" => getClient()["client"],
                            "pass" => $this->body("pass"),
                        ],
                        [
                            "Origin" => $this->req->getHeader("Origin")[0],
                        ]
                    )->response();
                } catch (\Throwable) {
                    $this->back();
                    return;
                }

                $body = $res->json();

                if (!($body["validation"]["valid"] ?? false)) {
                    echo $body["validation"]["message"] ?? "";
                    $this->back();
                    return;
                } else {
                    $tk = $body["token"]["jwt"] ?? null;

                    if ($tk) {
                        setToken($body["token"]["jwt"]);
                    }
                }

                $location = $this->req->getQueryParams()["redirect"] ?? null;
                $this->redirect($location ?? baseurl(), 301);
                return;
        }

        $this->res = $this->res->withStatus(405);
    }


    #[Route]
    public function revoke(): void
    {
        unsetToken();

        $this->redirect(baseurl(), 301);
    }
}
