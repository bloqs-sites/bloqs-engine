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

use function Bloqs\Core\getToken;
use function Bloqs\Core\issetToken;
use function React\Async\async;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\req;

/**
 * Client Model
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
#[Table("preference")]
class Category extends RESTModel
{
    private string $name;
    private string $description;
    private float $weight;
    private string $color;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public static function manipulateReq(RequestInterface $req): RequestInterface
    {
        return $req->withHeader("Authorization", "Bearer " . getToken())
            ->withHeader("Origin", req()->getHeader("Origin")[0] ?? baseurl());
    }

    public static function fromRESTJSON(UriInterface $endpoint, array $json): PromiseInterface
    {
        return async(static function () use ($endpoint, $json): ?static {
            if (($json["@type"] ?? null) !== "CategoryCode") {
                return null;
            }

            if (!isset($json["id"])) {
                return null;
            }

            $api = $endpoint->withPath("/");

            $o = static::find($api, (string) $json["id"]);

            if (isset($o?->id)) {
                return $o;
            }

            $o->id = (string) $json["id"];
            $o->setName($json["name"]);
            $o->setDescription($json["description"]);
            $o->weight = ($json["weight"] ?? 0);
            $o->setColor($json["color"] ?? "transparent");

            return $o;
        })();
    }

    public function toArray(): array
    {
        $o = [
            "name" => $this->name,
            "description" => $this->description,
            "color" => $this->color,
        ];

        if (isset($this->id)) {
            $o["id"] = $this->id;
        }

        return $o;
    }

    public function __toString(): string
    {
        return "";
    }

    public function jsonSerialize(): mixed
    {
    }
}
