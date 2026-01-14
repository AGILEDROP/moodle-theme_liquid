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

namespace theme_liquid;

use core\hook\output\before_html_attributes;

/**
 * Hook callbacks for theme_liquid.
 *
 * @package    theme_liquid
 *
 * @copyright  2025 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks
{
    /**
     * Runs before HTTP attributes.
     *
     * @param before_html_attributes $hook
     */
    public static function before_html_attributes(before_html_attributes $hook): void
    {
        global $USER;

        $darkthemecookie = isset($_COOKIE['darkThemeEnabled']) ? $_COOKIE['darkThemeEnabled'] : null;
        $theme = ($darkthemecookie === '1') ? 'dark' : 'light';

        if (!isguestuser() && isloggedin()) {
            $themeuserpreferences = get_user_preferences('theme_liquid-dark-mode', null, $USER->id);
            $theme = $themeuserpreferences ?? $theme;
        }

        $hook->add_attribute('data-bs-theme', $theme);
    }
}
