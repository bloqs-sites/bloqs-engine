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

use TorresDeveloper\MVC\Model\Model;
use TorresDeveloper\MVC\Model\Table;

use function TorresDeveloper\MVC\config;

use const TorresDeveloper\MVC\EMAIL_REGEX;

/**
 * Client Model
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
#[Table("client")]
class Person extends Model
{
    private string $id;
    private string $email;
    private string $password;
    private bool $adultConsideration;

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setEmail(string $email): void
    {
        $len = strlen($email);
        if ($len > 0xFE) {
            throw new \InvalidArgumentException("Invalid e-mail $email: "
                . "To long");
        } elseif ($len < 5) {
            throw new \InvalidArgumentException("Invalid e-mail $email: "
                . "To short");
        }

        $email = filter_var($email, FILTER_VALIDATE_REGEXP, ["options" => [
            "regexp" => EMAIL_REGEX
        ]]);

        if ($email === false) {
            throw new \InvalidArgumentException("Invalid e-mail $email");
        }

        $domain = explode("@", $email)[1];
        $mxr = [];
        $w = [];
        if (getmxrr($domain, $mxr, $w) === false) {
            throw new \InvalidArgumentException("Invalid e-mail $email: "
                . "No MX records were found");
        }

        array_multisort($w, $mxr);

        $mxr[] = $domain;

        $domain = config()->get("domain");

        $valid = false;
        foreach ($mxr as $mx) {
            $fp = fsockopen("tcp://" . $mx, 25);
            $stream = stream_socket_client("tcp://$mx:25");

            if (!$fp) {
                continue;
            }

            stream_socket_sendto($fp, "HELO $domain\r\n");
            fputs($fp, "HELO $domain\r\n");
            stream_socket_sendto($fp, "MAIL FROM: <marado.pen.eng@gmail.com>\r\n");
            fputs($fp, "MAIL FROM: <marado.pen.eng@gmail.com>\r\n");
            stream_socket_sendto($fp, "RCPT TO: <$email>\r\n");
            fputs($fp, "RCPT TO: <$email>\r\n");
            stream_socket_sendto($fp, "RSET\r\n");
            fputs($fp, "RSET\r\n");

            $response = intval(fgets($fp, 4));

            if ($response === 250) {
                $valid = true;
                break;
            }

            fputs($fp, "QUIT\r\n");
            stream_socket_sendto($fp, "QUIT\r\n");
            fclose($fp);
        }

        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function setAdultConsideration(bool $adultConsideration): void
    {
        $this->adultConsideration = $adultConsideration;
    }

    protected function toArray(): array
    {
        return [
            "id" => $this->id,
            "email" => $this->email,
            "password" => $this->password,
            "adultConsideration" => (int) $this->adultConsideration,
        ];
    }

    public function __toString(): string
    {
        return $this->id . " <" . $this->email . ">";
    }

    public function jsonSerialize(): mixed
    {
    }
}
