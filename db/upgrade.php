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
 * @param int $oldversion The version we are upgrading from.
 * @return bool
 */
function xmldb_theme_liquid_upgrade($oldversion) {
    global $DB;

    if ($oldversion < 2026101300) {
        // Liquid now stores the colour mode in Boost's preference. Copy each user's Liquid dark mode choice across,
        // unless they have already chosen a mode in Boost, then remove the old preference.
        $preferences = $DB->get_recordset('user_preferences', ['name' => 'theme_liquid-dark-mode'], '', 'id, userid, value');
        foreach ($preferences as $preference) {
            if (!in_array($preference->value, ['light', 'dark'], true)) {
                continue;
            }
            $exists = $DB->record_exists('user_preferences', [
                'userid' => $preference->userid,
                'name' => 'theme_boost_colourmode',
            ]);
            if (!$exists) {
                $DB->insert_record('user_preferences', (object) [
                    'userid' => $preference->userid,
                    'name' => 'theme_boost_colourmode',
                    'value' => $preference->value,
                ]);
            }
        }
        $preferences->close();

        $DB->delete_records('user_preferences', ['name' => 'theme_liquid-dark-mode']);

        upgrade_plugin_savepoint(true, 2026101300, 'theme', 'liquid');
    }

    return true;
}
