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

use Bloqs\Core\ClientData;
use TorresDeveloper\HTTPMessage\HTTPVerb;
use TorresDeveloper\MVC\Controller\Controller;
use TorresDeveloper\MVC\Controller\MethodsAllowed;
use TorresDeveloper\MVC\Controller\Route;
use TorresDeveloper\MVC\View\Loader\NativeViewLoader;
use TorresDeveloper\MVC\View\View;

use function Bloqs\Config\cnf;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\debug;
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
            $this->load("php/credentials/index", [
                "title" => $title,
                "action" => baseurl("credentials"),
                "methods" => $methods,
            ]);
        }
    }

    #[Route]
    #[MethodsAllowed(HTTPVerb::POST)]
    public function basic(): void
    {
        if (cnf() === null) {
            $this->res = $this->res
                ->withStatus(308)
                ->withHeader("Location", baseurl());

            return;
        }

        $type = cnf("auth", "authTypeQueryParam") ?? "type";
        $resource = cnf("auth", "domain")
            . cnf("auth", "logPath")
            . "?$type=basic";

        try {
            $res = pull(
                $resource,
                HTTPVerb::POST,
                (array) $this->req->getParsedBody(),
                [
                    "Origin" => $this->req->getHeader("Origin")[0],
                ]
            );
        } catch (\Throwable $th) {
            $th->getMessage();
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
                ClientData::setToken($body["token"]["jwt"]);
            }
        }

        $this->res = $this->res
            ->withStatus(308)
            ->withHeader("Location", baseurl());
    }
}
