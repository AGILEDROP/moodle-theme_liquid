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
 * Darkmode functionality for theme Liquid.
 *
 * @module     theme_liquid/darkmode
 * @copyright  2025 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([], function() {
    var init = function() {
        const darkThemeMq = window.matchMedia("(prefers-color-scheme: dark)");
        const darkThemeToggle = document.querySelectorAll(".darktheme-checkbox");

        const setTheme = function(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
        };

        const updateCheckboxes = function(checked) {
            darkThemeToggle.forEach(function(checkbox) {
                checkbox.checked = checked;
            });
        };

        const initializeTheme = function() {
            const storedDarkThemeSetting = getCookie("darkThemeEnabled");

            if (storedDarkThemeSetting === null) {
                setCookie("darkThemeEnabled", darkThemeMq.matches ? "1" : "0");
                updateCheckboxes(darkThemeMq.matches);
                setTheme(darkThemeMq.matches ? 'dark' : 'light');
            } else {
                const isDarkTheme = storedDarkThemeSetting === "1";
                updateCheckboxes(isDarkTheme);
                setTheme(isDarkTheme ? 'dark' : 'light');
            }
        };

        const handleCheckboxChange = function(e) {
            const isDarkThemeEnabled = e.target.checked;

            setTheme(isDarkThemeEnabled ? 'dark' : 'light');
            setCookie("darkThemeEnabled", isDarkThemeEnabled ? "1" : "0");
            updateCheckboxes(isDarkThemeEnabled);
        };

        darkThemeToggle.forEach(function(checkbox) {
            checkbox.addEventListener("change", handleCheckboxChange);
        });

        initializeTheme();

        /**
         * Retrieves the value of a cookie by name.
         * @param {string} name - The name of the cookie.
         * @returns {string|null} - The value of the cookie or null if not found.
         */
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
            return null;
        }

        /**
         * Sets a cookie with the given name and value.
         * @param {string} name - The name of the cookie.
         * @param {string} value - The value to set for the cookie.
         */
        function setCookie(name, value) {
            document.cookie = `${name}=${value}; path=/; SameSite=None; Secure`;
        }
    };

    return {
        init: init
    };
});