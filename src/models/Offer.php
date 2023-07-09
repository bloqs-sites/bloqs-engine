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

use DateTimeImmutable;
use DateTimeInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use React\Promise\PromiseInterface;
use TorresDeveloper\MVC\Model\RESTModel;
use TorresDeveloper\MVC\Model\Table;

use function Bloqs\Core\getToken;
use function React\Async\async;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\req;

#[Table("offer")]
class Offer extends RESTModel
{
    private string $availability;
    private DateTimeInterface $availabilityStarts;
    private DateTimeInterface $availabilityEnds;
    private Person $offeredBy;
    private float $price;
    /**
        @property \Bloqs\Models\Product[] $itemsOffered
     */
    private array $itemsOffered;

    public function getAvailability(): string
    {
        return $this->availability;
    }
    public function setAvailability(string $i): void
    {
        $this->availability = $i;
    }
    public function getAvailabilityStarts(): DateTimeInterface
    {
        return $this->availabilityStarts;
    }
    public function setAvailabilityStarts(DateTimeInterface $i): void
    {
        $this->availabilityStarts = $i;
    }
    public function getAvailabilityEnds(): DateTimeInterface
    {
        return $this->availabilityEnds;
    }
    public function setAvailabilityEnds(DateTimeInterface $i): void
    {
        $this->availabilityEnds = $i;
    }
    public function getOfferedBy(): Person
    {
        return $this->offeredBy;
    }
    public function setOfferedBy(Person $i): void
    {
        $this->offeredBy = $i;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function setPrice(float $i): void
    {
        $this->price = $i;
    }
    public function getItemsOffered(): array
    {
        return $this->itemsOffered;
    }
    /**
     * @param \Bloqs\Models\Product[] $i
     */
    public function setItemsOffered(array $i): void
    {
        $this->itemsOffered = $i;
    }

    public static function manipulateReq(RequestInterface $req): RequestInterface
    {
        return $req->withHeader("Authorization", "Bearer " . getToken())
            ->withHeader("Origin", req()->getHeader("Origin")[0] ?? baseurl());
    }

    public static function fromRESTJSON(UriInterface $endpoint, array $json): PromiseInterface
    {
        return async(static function () use ($endpoint, $json): ?static {
            if (($json["@type"] ?? null) !== "Offer") {
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

            $o->setId((string) $json["id"]);
            $o->setAvailability($json["availability"]);
            $o->setAvailabilityStarts(\DateTimeImmutable::createFromFormat(
                \DateTimeInterface::RFC3339,
                $json["availabilityStarts"]
            ));
            $o->setAvailabilityEnds(\DateTimeImmutable::createFromFormat(
                \DateTimeInterface::RFC3339,
                $json["availabilityEnds"]
            ));
            $o->setOfferedBy(Person::new($api, $json["offeredBy"]));
            $o->setPrice(floatval($json["price"]));

            return $o;
        })();
    }

    public function toArray(): array
    {
        $o = [
            "availability" => $this->getAvailability(),
            "availabilityStarts" => $this->getAvailabilityStarts()
                ->format(\DateTimeInterface::RFC3339),
            "availabilityEnds" => $this->getAvailabilityEnds()
                ->format(\DateTimeInterface::RFC3339),
            "creator" => $this->getOfferedBy()->getId(),
            "price" => $this->getPrice()
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
