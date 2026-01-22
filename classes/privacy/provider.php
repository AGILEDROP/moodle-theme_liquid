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
use core_privacy\local\request\user_preference_provider;
use core_privacy\local\request\writer;

/**
 * Privacy provider implementation for theme_liquid.
 *
 * The liquid theme stores user preference data.
 *
 * @copyright  2025 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    // This plugin has data.
    \core_privacy\local\metadata\provider,
    // This plugin has some sitewide user preferences to export.
    user_preference_provider
{
    /** The user preferences for light or dark theme. */
    const THEME_MODE = 'theme_liquid-dark-mode';

    /**
     * Returns meta data about this system.
     *
     * @param  collection $collection The initialised item collection to add items to.
     * @return collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_user_preference(self::THEME_MODE, 'privacy:metadata:preference:thememode');
        return $collection;
    }

    /**
     * Store all user preferences for the plugin.
     *
     * @param int $userid The userid of the user whose data is to be exported.
     */
    public static function export_user_preferences(int $userid) {

        $thememodepref = get_user_preferences(self::THEME_MODE, null, $userid);

        if (isset($thememodepref)) {
            $preferencestring = get_string('privacy:thememodelight', 'theme_liquid');
            if ($thememodepref === '1') {
                $preferencestring = get_string('privacy:thememodedark', 'theme_liquid');
            }
            writer::export_user_preference(
                'theme_liquid',
                self::THEME_MODE,
                $thememodepref,
                $preferencestring
            );
        }
    }
}
