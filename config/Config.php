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
 * @package TorresDeveloper\\BlocksEngine\\Config
 * @author João Torres <torres.dev@disroot.org>
 * @copyright Copyright (C) 2022-2023 João Torres
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License
 * @license https://opensource.org/licenses/AGPL-3.0 GNU Affero General Public License version 3
 *
 * @since 0.0.3
 * @version 0.0.1
 */

declare(strict_types=1);

namespace TorresDeveloper\BlocksEngine\Config;

use TorresDeveloper\MVC\Config\Config as AbstractConfig;

/**
 * The Config for the MVC App
 *
 * @author João Torres <torres.dev@disroot.org>
 *
 * @since 0.0.3
 * @version 0.0.2
 */
class Config extends AbstractConfig
{
    protected function getEntries(): array
    {
        $entries = [];
        $entries["root"] = __DIR__ . "/..";
        $entries["public"] = $entries["root"] . "/public";
        $entries["uri"] = "http://{$_SERVER["HTTP_HOST"]}/";
        $entries["db_cfg"] = __DIR__ . "/databaseConfig.php";
        $entries["default_db"] = "default";
        $entries["debug"] = true;
        $entries["debug_lvl"] = E_ALL;
        $entries["debug_trace"] = true;
        $entries["charset"] = "UTF-8";
        $entries["default_controller"] = "Market";
        $entries["templates"] =$entries["root"] . "/src/templates/";
        $entries["path_search_param"] = "path";

        return $entries;
    }
}
