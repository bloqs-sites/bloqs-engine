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
 * @copyright Copyright (C) 2022-2023 João Torres
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License
 * @license https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3
 *
 * @since 0.0.3
 * @version 0.0.1
 */

declare(strict_types=1);

namespace TorresDeveloper\BlocksEngine\Controllers;

use TorresDeveloper\HTTPMessage\HTTPVerb;
use TorresDeveloper\HTTPMessage\Request;
use TorresDeveloper\MVC\Controller;
use TorresDeveloper\MVC\DB;
use TorresDeveloper\MVC\NativeViewLoader;
use TorresDeveloper\MVC\Route;
use TorresDeveloper\MVC\View;
use TorresDeveloper\Pull\Pull;

use function TorresDeveloper\MVC\baseurl;

/**
 * The Controller to manage the client session
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
class ClientController extends Controller
{
    #[Route]
    #[View(NativeViewLoader::class, TEMPLATES . "/php")]
    #[DB(DEFAULT_DB)]
    public function make(): void
    {
        if ($this->getVerb() === HTTPVerb::POST) {
            $body = $this->req->getParsedBody();

            try {
                $this->db->insert("client", [
                    "id" => $body["name"],
                ]);

                $req = new Request(
                    "http://localhost:8080/clients",
                    HTTPVerb::POST,
                    json_encode([
                        "client" => $body["name"],
                        "likes" => array_keys($body["preferences"] ?? [])
                    ]),
                );

                $req = $req->withHeader(
                    "Content-Type",
                    "application/javascript"
                );

                var_dump($req->getBody()->getContents());
                Pull::fetch()->start($req);
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    return;
                }
            }

            echo PHP_EOL . "LOCATION";
            //$this->res->withHeader("Location", baseurl());
            return;
        }

        $preferences = Pull::fetch()->start(
            new Request("http://$_SERVER[SERVER_NAME]:8080/preferences")
        )->getBody()->getContents();

        $this->load("sign", [
            "preferences" => json_decode($preferences, true)
        ]);
    }
}
