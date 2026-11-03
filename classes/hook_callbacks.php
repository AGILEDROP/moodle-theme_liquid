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
use core\hook\output\before_standard_head_html_generation;
use core\output\html_writer;

/**
 * Hook callbacks for theme_liquid.
 *
 * @package    theme_liquid
 *
 * @copyright  2025 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {
    /**
     * Set the colour mode on the html tag.
     *
     * Mirrors theme_boost\hook_listener::before_html_attributes_listener(), but uses Liquid's colour mode rules. This
     * callback runs after Boost's, so when Boost's experimental colour modes are enabled Liquid's choice still wins.
     *
     * @param before_html_attributes $hook
     */
    public static function before_html_attributes(before_html_attributes $hook): void {
        if (!self::is_liquid_page()) {
            return;
        }

        $mode = colour_mode::get_current_mode();

        // Auto is resolved in the browser by the script added in before_standard_head_html_generation().
        $hook->add_attribute('data-bs-theme', colour_mode::get_rendered_mode($mode));
        $hook->add_attribute('data-colourmode', $mode);
    }

    /**
     * Add the script which resolves the "auto" colour mode before the page is painted.
     *
     * The same script as theme_boost\hook_listener::before_standard_head_html_generation_listener(), which only adds it
     * when Boost's experimental colour modes are enabled.
     *
     * @param before_standard_head_html_generation $hook
     */
    public static function before_standard_head_html_generation(before_standard_head_html_generation $hook): void {
        if (!self::is_liquid_page() || colour_mode::is_enabled()) {
            // Not Liquid, or Boost already adds this script.
            return;
        }

        // Behat sites keep the colour mode the server decided on, as which mode "auto" resolves to depends on the
        // machine running the browser.
        if (defined('BEHAT_SITE_RUNNING')) {
            return;
        }

        $config = json_encode([
            'auto' => colour_mode::AUTO,
            'dark' => colour_mode::DARK,
            'light' => colour_mode::LIGHT,
        ]);

        $js = <<<EOF
            (function() {
                var config = {$config};
                var root = document.documentElement;
                var query = window.matchMedia('(prefers-color-scheme: dark)');
                var resolve = function() {
                    var mode = root.getAttribute('data-colourmode');
                    root.setAttribute(
                        'data-bs-theme',
                        mode === config.auto ? (query.matches ? config.dark : config.light) : mode
                    );
                };
                resolve();
                query.addEventListener('change', resolve);
            })();
            EOF;

        $hook->add_html(html_writer::script($js));
    }

    /**
     * Whether the page is rendered with the Liquid theme.
     *
     * @return bool
     */
    private static function is_liquid_page(): bool {
        global $PAGE;

        if (during_initial_install()) {
            return false;
        }

        return $PAGE->theme->name === 'liquid';
    }
}
