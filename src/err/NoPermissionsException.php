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
 * @package Bloqs\\Errors
 * @author João Torres <torres.dev@disroot.org>
 * @copyright Copyright (C) 2022-2023 João Torres
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License
 * @license https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3
 *
 * @since 0.0.5
 * @version 0.0.1
 */

declare(strict_types=1);

namespace Bloqs\Errors;

use TorresDeveloper\Pull\Response;

final class NoPermissionsException extends \Exception
{
    private Response $response;
    private string $permission;

    public function __construct(Response $res)
    {
        $permission = explode("`", $res->text())[1];

        if ($permission == null) {
            throw new \Exception();
        }

        $this->response = $res;
        $this->permission = $permission;

        parent::__construct($res->text());
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getPermission(): string
    {
        return $this->permission;
    }
}
