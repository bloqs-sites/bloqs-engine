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
 * @package TorresDeveloper\\BlocksEngine\\Models
 * @author João Torres <torres.dev@disroot.org>
 * @copyright Copyright (C) 2022-2023 João Torres
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License
 * @license https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3
 *
 * @since 0.0.3
 * @version 0.0.1
 */

declare(strict_types=1);

namespace Bloqs\Models;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use React\Promise\PromiseInterface;
use TorresDeveloper\MVC\Model\RESTModel;
use TorresDeveloper\MVC\Model\Table;

use function React\Async\async;

#[Table("org")]
class Organization extends RESTModel
{

    public static function fromRESTJSON(UriInterface $endpoint, array $json): PromiseInterface
    {
        return async(static function () use ($endpoint, $json): ?static {
            return null;
        })();
    }

    public static function manipulateReq(RequestInterface $req): RequestInterface
    {
        return $req;
    }

    public function toArray(): array
    {
        return [];
    }

    public function __toString(): string
    {
        return "";
    }

    public function jsonSerialize(): mixed
    {
    }
}
