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

namespace TorresDeveloper\BlocksEngine\Models;

use TorresDeveloper\MVC\Model\Model;

class Rating extends Model
{
    private Person $author;

    public const BEST_RATING = 5.0;
    public const WORST_RATING = -5.0;

    private ?string $ratingExplanation;

    private const EXPLANATION_MAX_LEN = 140;

    private int $ratingValue;

    final public function getAuthor(): Person
    {
        return $this->author;
    }

    final public function getRatingExplanation(): string
    {
        return $this->ratingExplanation;
    }

    final public function setRatingExplanation(string $msg)
    {
        if (mb_strlen($msg) > self::EXPLANATION_MAX_LEN) {
            throw new \RuntimeException("Explanation too long. More than "
                . self::EXPLANATION_MAX_LEN . "bytes of length");
        }

        $this->ratingExplanation = $msg;
    }

    final public function getRatingValue(): int
    {
        return $this->ratingValue;
    }

    final public function setRatingValue(int $v)
    {
        if ($v === 0) {
            throw new \DomainException("Not a valid rating. "
                . "0 would be the same as not rating");
        }

        if ($v > self::BEST_RATING || $v < self::WORST_RATING) {
            throw new \DomainException("Rating goes from "
                . self::WORST_RATING
                . " to "
                . self::BEST_RATING
                . "!");
        }

        $this->ratingValue = $v;
    }

    protected function toArray(): array
    {
        return (array) $this;
    }

    public function __toString(): string
    {
        return "";
    }

    public function jsonSerialize(): mixed
    {
    }
}
