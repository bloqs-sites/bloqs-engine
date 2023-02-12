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
use Bloqs\Core\ClientData;
use Bloqs\Models\Person;
use TorresDeveloper\HTTPMessage\HTTPVerb;
use TorresDeveloper\MVC\Controller\Controller;
use TorresDeveloper\MVC\Controller\DB;
use TorresDeveloper\MVC\Controller\Route;
use TorresDeveloper\MVC\View\Loader\NativeViewLoader;
use TorresDeveloper\MVC\View\View;
use TorresDeveloper\PdoWrapperAPI\Core\QueryBuilder;

use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\Pull\pull;

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
    #[Route]
    #[View(NativeViewLoader::class)]
    #[DB]
    public function auth(): void
    {
        if ($this->getVerb() === HTTPVerb::POST) {
            $data = $this->getQueryBuilder()
                ->select()
                ->from("client")
                ->where("id", QueryBuilder::EQ, $this->body("name"))
                ->run()
                ->fetchAll(\PDO::FETCH_ASSOC);

            if ($data) {
                $token = pull(
                    "http://localhost:8080/auth",
                    HTTPVerb::POST,
                    json_encode(["client" => $this->body("name")]),
                    ["Content-Type" => "application/javascript"]
                );

                ClientData::setToken($token);

                $this->res = $this->res
                    ->withHeader("Location", baseurl())
                    ->withStatus(301);

                return;
            }
        }

        $this->load("php/log");
    }

    #[Route]
    #[View(NativeViewLoader::class)]
    #[DB]
    public function make(): void
    {
        if ($this->getVerb() === HTTPVerb::POST) {
            try {
                $person = new Person($this->db);
                $person->setId($this->body("name"));
                $person->setEmail($this->body("email"));
                $person->setPassword($this->body("passwd"));
                $person->setAdultConsideration((bool) $this->body(
                    "adultConsideration"
                ));
                $person->insert();

                pull(
                    "http://localhost:8080/clients",
                    HTTPVerb::POST,
                    json_encode([
                        "client" => $this->body("name"),
                        "likes" => array_keys($this->body("preferences") ?? [])
                    ]),
                    ["Content-Type" => "application/javascript"]
                );
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    return;
                }
            }

            $this->res = $this->res
                ->withHeader("Location", baseurl())
                ->withStatus(201);

            echo "<a href=" / ">HOME</a>";

            return;
        }

        $preferences = pull("http://$_SERVER[SERVER_NAME]:8080/preferences");

        $this->load("php/sign", [
            "preferences" => json_decode($preferences, true),
            "adultAllowed" => BloqsCfg::getCfg()
                ->tryGet("allow_adult_consideration"),
        ]);
    }
}
