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

use Bloqs\Review\Review;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;
use React\Promise\PromiseInterface;
use TorresDeveloper\HTTPMessage\URI;
use TorresDeveloper\MVC\Model\RESTModel;
use TorresDeveloper\MVC\Model\Table;

use function Bloqs\Core\getToken;
use function React\Async\async;
use function React\Async\await;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\req;

/**
 * Bloq Model
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
#[Table("bloq")]
class Product extends RESTModel
{
    private string $name;
    private string $description;
    private Category $preference;
    private UploadedFileInterface $image;
    /** @var string[] $keywords */
    private array $keywords;
    private bool $hasAdultConsideration;
    private Person|Organization $creator;
    private \DateTimeImmutable $releaseDate;
    /** @var \Bloqs\Models\Product[] $related */
    private array $related;
    /** @var \Bloqs\Models\Review[] $reviews */
    private array $reviews;
    private UriInterface $reviewsUri;
    private Review $negativeNotes;
    private Review $positiveNotes;

    public function getReviewsUri(): UriInterface
    {
        return $this->reviewsUri;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    public function getPreference(): Category
    {
        return $this->preference;
    }
    public function setPreference(Category $preference): void
    {
        $this->preference = $preference;
    }
    public function getImage(): UploadedFileInterface
    {
        return $this->image;
    }
    public function setImage(UploadedFileInterface $image): void
    {
        $this->image = $image;
    }
    public function getKeywords(): array
    {
        return $this->keywords;
    }
    public function setKeywords(array $keywords): void
    {
        $this->keywords = $keywords;
    }
    public function getHasAdultConsideration(): bool
    {
        return $this->hasAdultConsideration;
    }
    public function setHasAdultConsideration(bool $hasAdultConsideration): void
    {
        $this->hasAdultConsideration = $hasAdultConsideration;
    }
    public function getCreator(): Person|Organization
    {
        return $this->creator;
    }
    public function setCreator(Person|Organization $creator): void
    {
        $this->creator = $creator;
    }
    public function getReviews(): array
    {
        return $this->reviews;
    }
    public function setReviews(array $reviews): void
    {
        $this->reviews = $reviews;
    }
    public function getRelated(): array
    {
        return $this->related;
    }
    public function setRelated(array $related): void
    {
        $this->related = $related;
    }
    public function getReleaseDate(): \DateTimeInterface
    {
        return $this->releaseDate;
    }

    public static function manipulateReq(RequestInterface $req): RequestInterface
    {
        return $req->withHeader("Authorization", "Bearer " . getToken())
            ->withHeader("Origin", req()->getHeader("Origin")[0] ?? baseurl());
    }

    public static function fromRESTJSON(UriInterface $endpoint, array $json): PromiseInterface
    {
        return async(static function () use ($endpoint, $json): ?static {
            if (($json["@type"] ?? null) !== "Product") {
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
            $o->setName($json["name"]);
            $o->setDescription($json["description"]);

            $category = Category::new($api, (string) $json["category"]);
            if (isset($category)) {
                $o->setPreference($category);
            }
            //$o->setImage($json["image"]);
            $o->setKeywords($json["keywords"]);
            $o->setHasAdultConsideration($json["hasAdultConsideration"]);
            $o->setCreator(Person::new($api, (string) $json["creator"]));
            $o->releaseDate = \DateTimeImmutable::createFromFormat("Y-m-d\\TH:i:s\\Z", $json["releaseDate"]);
            $o->reviewsUri = new URI($json["reviews"]);
            //$reviews = [];
            //$type = null;
            //foreach ($json["reviews"] as $r) {
            //    if (isset($r["@type"])) {
            //        $type = $r["@type"];
            //    } else {
            //        if (isset($type)) {
            //            $r["@type"] = $type;
            //        }
            //    }

            //    $i = Review::fromRESTJSON($api, $r);
            //    if ($o !== null) {
            //        $reviews[] = $i;
            //    }
            //}
            //$o->setReviews($reviews);
            $related = [];
            $type = null;
            foreach ($json["related"] as $r) {
                if (isset($r["@type"])) {
                    $type = $r["@type"];
                } else {
                    if (isset($type)) {
                        $r["@type"] = $type;
                    }
                }

                $i = await(Product::fromRESTJSON($api, $r));
                if ($i !== null) {
                    $related[] = $o;
                }
            }
            $o->setReviews($related);

            return $o;
        })();
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id ?? null,
            "name" => $this->name,
            "description" => $this->description,
            "category" => $this->preference->getId(),
            "image" => !isset($this->image) ? null : new \CURLFile($this->image->getClientFilename(), $this->image->getClientMediaType()),
            "keywords" => $this->keywords,
            "creator" => $this->creator->getId(),
            "related" => isset($this->related) ? $this->related : null,
            "reviews" => isset($this->reviews) ? $this->reviews : null,
            "hasAdultConsideration" => (int) $this->hasAdultConsideration
        ];
    }

    public function __toString(): string
    {
        return "";
    }

    public function jsonSerialize(): mixed
    {
    }
}
