<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Privacy Subsystem implementation for Liquid theme.
 *
 * @package    theme_liquid
 * @copyright  2025 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_liquid\privacy;

use core_privacy\local\metadata\collection;

/**
 * Privacy provider implementation for theme_liquid.
 *
 * Colour preferences are reported by Boost. Optional Google Fonts loading sends browser request data to Google.
 *
 * @copyright  2025 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements \core_privacy\local\metadata\provider {
    /**
     * Describe data handled by Boost and the optional external font service.
     *
     * @param collection $collection The metadata collection.
     * @return collection The completed metadata collection.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_plugintype_link('theme_boost', [], 'privacy:metadata:boost');
        $collection->add_external_location_link('googlefonts', [
            'ipaddress' => 'privacy:metadata:googlefonts:ipaddress',
            'browser' => 'privacy:metadata:googlefonts:browser',
        ], 'privacy:metadata:googlefonts');

        return $collection;
    }
}
