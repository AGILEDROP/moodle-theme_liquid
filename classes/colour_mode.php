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

/**
 * Liquid's light and dark colour modes, built on Boost's colour modes.
 *
 * Liquid stores the chosen mode exactly as Boost does, in the theme_boost_colourmode user preference and the cookie of
 * the same name, so the two always agree. Unlike Boost, Liquid's colour modes do not depend on Boost's experimental
 * enablecolourmodes setting, and a person who has not chosen a mode follows the colour scheme of their device.
 *
 * @package    theme_liquid
 * @copyright  2026 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class colour_mode extends \theme_boost\colour_mode {
    /** @var string The cookie used by Liquid before Moodle 5.3, read until the visitor chooses a mode again. */
    public const LEGACY_COOKIE = 'darkThemeEnabled';

    /**
     * The colour mode chosen for the current visitor.
     *
     * The user preference wins whenever it can be read, then the browser copy of the mode, then the cookie Liquid used
     * before Moodle 5.3. A visitor who has never chosen a mode follows their device.
     *
     * @return string One of the self::LIGHT, self::DARK or self::AUTO constants.
     */
    public static function get_current_mode(): string {
        if (isloggedin() && !isguestuser()) {
            $preference = get_user_preferences(self::PREFERENCE);
            if (self::is_valid_mode($preference)) {
                return $preference;
            }
        }

        if (
            defined('BEHAT_SITE_RUNNING')
            && function_exists('behat_get_colour_mode')
        ) {
            $behatmode = behat_get_colour_mode();
            if (self::is_valid_mode($behatmode)) {
                return $behatmode;
            }
        }

        $browsermode = self::get_browser_mode();
        if ($browsermode !== null) {
            return $browsermode;
        }

        $legacy = $_COOKIE[self::LEGACY_COOKIE] ?? null;
        if ($legacy === '1') {
            return self::DARK;
        }
        if ($legacy === '0') {
            return self::LIGHT;
        }

        return self::AUTO;
    }

    /**
     * The mode to write to the data-bs-theme attribute before the browser has resolved "auto".
     *
     * @param string $mode One of the self::LIGHT, self::DARK or self::AUTO constants.
     * @return string Either self::LIGHT or self::DARK.
     */
    public static function get_rendered_mode(string $mode): string {
        return $mode === self::AUTO ? self::LIGHT : $mode;
    }

    /**
     * The configuration the theme_liquid/darkmode AMD module needs.
     *
     * @return array
     */
    public static function get_js_config(): array {
        return [
            'canstore' => isloggedin() && !isguestuser(),
            'cookieattributes' => self::get_cookie_attributes(),
            'cookiename' => self::PREFERENCE,
            'legacycookiename' => self::LEGACY_COOKIE,
            'mode' => self::get_current_mode(),
            'preference' => self::PREFERENCE,
            'saveerrormessage' => get_string('colourmodesavefailed', 'theme_liquid'),
        ];
    }
}
