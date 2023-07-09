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
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriInterface;
use React\Promise\PromiseInterface;
use TorresDeveloper\HTTPMessage\Request;
use TorresDeveloper\HTTPMessage\URI;
use TorresDeveloper\MVC\Model\RESTModel;
use TorresDeveloper\MVC\Model\Table;
use TorresDeveloper\Pull\Pull;

use function Bloqs\Config\cnf;
use function Bloqs\Core\getToken;
use function React\Async\async;
use function React\Async\await;
use function React\Promise\all;
use function TorresDeveloper\MVC\baseurl;
use function TorresDeveloper\MVC\debug;
use function TorresDeveloper\MVC\req;

/**
 * Client Model
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
#[Table("profile")]
class Person extends RESTModel
{
    protected string $id;
    private string $name;
    private string $description;
    private string $honorificPrefix;
    private string $honorificSuffix;
    private UploadedFileInterface $image;
    private UriInterface $url;
    private bool $hasAdultConsideration;
    private int $level;
    /** @var \Bloqs\Models\Person[] $followers */
    private array $followers;
    private int $followers_count;
    /** @var \Bloqs\Models\Person[] $following */
    private array $following;
    private int $following_count;
    /** @var \Bloqs\Models\Category[] $likes */
    private array $likes = [];
    private array $likesWeight = [];
    private array $makesOffer;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setHonorificPrefix(string $honorificPrefix): void
    {
        $this->honorificPrefix = $honorificPrefix;
    }

    public function setHonorificSuffix(string $honorificSuffix): void
    {
        $this->honorificSuffix = $honorificSuffix;
    }

    public function setImage(UploadedFileInterface $image): void
    {
        $this->image = $image;
    }

    public function setUrl(UriInterface $uri): void
    {
        $this->url = $uri;
    }

    public function setHasAdultConsideration(bool $hasAdultConsideration): void
    {
        $this->hasAdultConsideration = $hasAdultConsideration;
    }

    public function addFollowers(Person ...$followers): void
    {
        foreach ($followers as $i) {
            $this->followers[$i->getId()] = $i;
        }
    }

    public function removeFollowers(Person ...$followers): void
    {
        foreach ($followers as $i) {
            unset($this->followers[$i->getId()]);
        }
    }

    public function addFollowing(Person ...$following): void
    {
        foreach ($following as $i) {
            $this->following[$i->getId()] = $i;
        }
    }

    public function removeFollowing(Person ...$following): void
    {
        foreach ($following as $i) {
            unset($this->following[$i->getId()]);
        }
    }

    public function addLike(Category ...$likes): void
    {
        foreach ($likes as $i) {
            $this->likes[$i->getId()] = $i;
        }
    }

    public function removeLike(Category ...$likes): void
    {
        foreach ($likes as $i) {
            unset($this->likes[$i->getId()]);
        }
    }

    public function getId(): ?string
    {
        return isset($this->id) ? $this->id : null;
    }

    public function getName(): string
    {
        return isset($this->name) ? $this->name : "";
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getHonorificPrefix(): string
    {
        return $this->honorificPrefix;
    }

    public function getHonorificSuffix(): string
    {
        return $this->honorificSuffix;
    }

    public function getImage(): UploadedFileInterface
    {
        return $this->image;
    }

    public function getUrl(): UriInterface
    {
        return $this->url;
    }

    public function getHasAdultConsideration(): bool
    {
        return $this->hasAdultConsideration;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    /** @return \Bloqs\Models\Person[] */
    public function getFollowers(): array
    {
        return $this->followers;
    }
    public function getFollowersCount(): int
    {
        return $this->followers_count;
    }

    /** @return \Bloqs\Models\Person[] */
    public function getFollowing(): array
    {
        return $this->following;
    }
    public function getFollowingCount(): int
    {
        return $this->following_count;
    }

    /** @return \Bloqs\Models\Category[] */
    public function getLikes(): array
    {
        return $this->likes;
    }

    public function getLikeWeight(Category $c): float
    {
        return $this->likesWeight[$c->getId()] ?? 0;
    }

    public function getMakesOffer(UriInterface $api): array
    {
        if (isset($this->makesOffer)) {
            return $this->makesOffer;
        }

        $path = $api->getPath() . "profile/" . $this->id . "/makesOffer";


        $req = new Request($api->withPath($path));

        $res = (new Pull($this->manipulateReq($req)))->response();

        if (!$res->ok()) {
            debug($res->text());
        }

        $result = $res->json();

        $promises = [];
        $this->makesOffer = [];
        $type = null;
        foreach ($result as $r) {
            if (isset($r["@type"])) {
                $type = $r["@type"];
            } else {
                if (isset($type)) {
                    $r["@type"] = $type;
                }
            }

            $promises[] =  async(static function () use ($api, $r) {
                return Product::fromRESTJSON($api, $r);
            })();
        }

        foreach (await(all($promises)) as $i) {
            if ($i !== null) {
                $this->makesOffer[] = $i;
            }
        };

        return $this->makesOffer;
    }

    public static function manipulateReq(RequestInterface $req): RequestInterface
    {
        return $req->withHeader("Authorization", "Bearer " . getToken())
            ->withHeader("Origin", req()->getHeader("Origin")[0] ?? baseurl());
    }

    public static function fromRESTJSON(UriInterface $endpoint, array $json): PromiseInterface
    {
        return async(static function () use ($endpoint, $json): ?static {
            if (($json["@type"] ?? null) !== "Person") {
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
            $o->setName((string) $json["name"]);
            $description = $json["description"];
            if ($description) {
                $o->setDescription($description);
            }
            $url = $json["url"];
            if ($url) {
                $o->setUrl(new URI($url));
            }
            if ((cnf("REST", "NSFW") ?? false) === true) {
                $o->setHasAdultConsideration($json["hasAdultConsideration"] ?? false);
            } else {
                $o->setHasAdultConsideration(false);
            }
            $o->level = $json["level"];

            foreach ($json["likes"] ?? [] as $i) {
                $c = Category::new($api, (string) $i["id"]);
                $o->addLike($c);
                $o->likesWeight[$c->getId()] = $i["weight"];
            }

            return $o;
        })();
    }

    public function toArray(): array
    {
        $o = [
            "name" => $this->name,
            "description" => isset($this->description) ? $this->description : null,
            "honorificPrefix" => isset($this->honorificPrefix) ? $this->honorificPrefix : null,
            "honorificSuffix" => isset($this->honorificSuffix) ? $this->honorificSuffix : null,
            "url" => ($this->url ?? null)?->__toString() ?? null,
            "hasAdultConsideration" => isset($this->hasAdultConsideration) ? $this->hasAdultConsideration : false,
            "followers" => array_map(static fn ($i) => $i->getId(), $this->followers ?? []),
            "following" => array_map(static fn ($i) => $i->getId(), $this->following ?? []),
            "likes" => array_map(static fn ($i) => $i->getId(), $this->likes ?? []),
        ];

        if (isset($this->image)) {
            $path = $this->image->getStream()->detach()?->getRealPath() ?? null;
            if (isset($path)) {
                $o["image"] = new \CURLFile(
                    $this->image->getClientMediaType(),
                    $this->image->getClientFilename()
                );
            }
        }

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
