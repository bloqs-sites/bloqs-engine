<?php

/**
 *        MVCBase - A base for a MVC.
 *        Copyright (C) 2022-2023  João Torres
 *
 *        This program is free software: you can redistribute it and/or modify
 *        it under the terms of the GNU Affero General Public License as
 *        published by the Free Software Foundation, either version 3 of the
 *        License, or (at your option) any later version.
 *
 *        This program is distributed in the hope that it will be useful,
 *        but WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *        GNU Affero General Public License for more details.
 *
 *        You should have received a copy of the GNU Affero General Public License
 *        along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @package Bloqs\\Core
 * @author João Torres <torres.dev@disroot.org>
 * @copyright Copyright (C) 2022-2023  João Torres
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License
 * @license https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3
 *
 * @since 0.15.0
 * @version 1.0.0
 */

declare(encoding="UTF-8");

declare(strict_types=1);

namespace Bloqs\Core;

use Bloqs\Models\Person;
use Psr\Http\Message\RequestInterface;
use TorresDeveloper\HTTPMessage\Response;
use TorresDeveloper\HTTPMessage\URI;
use TorresDeveloper\MVC\Controller\Controller as Base;
use TorresDeveloper\Pull\Pull;

use function Bloqs\Config\cnf;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\debug;
use function TorresDeveloper\MVC\req;

class Controller extends Base
{
    private const REQUEST_CACHE = "REQUEST_CACHE";
    private array $reqCache = [];

    protected bool $js = false;
    protected ?Person $profile = null;

    public function before(): void
    {
        $this->js = boolval($this->req->getCookieParams()["JS"]?? null);

        //$id = getProfile();
        //
        //if (isset($id) && (null !== cnf())) {
        //    $uri = new URI(cnf("REST", "domain"));

        //    foreach (Person::getFinder($uri)->withID($id)->run() as $i) {
        //        $this->profile = $i;
        //        break;
        //    }
        //}

        $reqCache = unserialize($this->req->getCookieParams()[self::REQUEST_CACHE] ?? "");

        if (is_iterable($reqCache)) {
            foreach ($reqCache as $i) {
                try {
                    new Pull($i);
                } catch (\Throwable) {
                    $this->reqCache[] = $i;
                }
            }
        }
    }

    public function after(): void
    {
        $_SESSION[self::REQUEST_CACHE] = serialize($this->reqCache);
        setcookie("JS", "", [
            "expires" => -1,
            "path" => "/",
        ]);
    }

    final protected function tryAgain(RequestInterface $req): void
    {
        $this->reqCache[] = $req;
    }

    protected function needsConf(): void
    {
        if (cnf() === null) {
            $this->redirect(baseurl());
            $this->respondEarly();
        }
    }

    protected function needsCredentials(): void
    {
        if (!issetToken()) {
            $this->redirect(baseurl("credentials/log"));
            $this->respondEarly();
        }
    }

    protected function needsSuper(): void
    {
        if ((getClient()["is_super"] ?? false) === false) {
            $this->res = new Response(403);
            $this->respondEarly();
        }
    }

    protected function needsAccount(): void
    {
        if (!issetProfile()) {
            $this->redirect(baseurl("client/index"));
            $this->respondEarly();
        }
    }

    protected function needsAccountIfCredentials(): void
    {
        if (issetToken()) {
            $this->needsAccount();
        }
    }
}
