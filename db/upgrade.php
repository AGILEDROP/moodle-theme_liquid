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
 * Upgrade steps for theme_liquid.
 *
 * @package    theme_liquid
 * @copyright  2026 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade the theme.
 *
 * The 1.x versions must stay below 2026101300, so that the 2.0 upgrade still runs its dark mode preference migration.
 *
 * @param int $oldversion The version we are upgrading from.
 * @return bool
 */
function xmldb_theme_liquid_upgrade($oldversion) {
    if ($oldversion < 2026101200) {
        // Keep the saved font URL, but require an explicit opt-in before loading external fonts.
        set_config('usegooglefonts', 0, 'theme_liquid');
        upgrade_plugin_savepoint(true, 2026101200, 'theme', 'liquid');
    }

    return true;
}
