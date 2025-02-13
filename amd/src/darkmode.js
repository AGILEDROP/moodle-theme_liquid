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

import {getUserPreference, setUserPreference} from 'core_user/repository';

const darkThemeMq = window.matchMedia("(prefers-color-scheme: dark)");
const darkThemeToggle = document.querySelectorAll(".darktheme-checkbox");

const setTheme = (theme) => {
    document.documentElement.setAttribute('data-bs-theme', theme);
};

/**
 * Retrieves the value of a cookie by name.
 * @param {string} name - The name of the cookie.
 * @returns {string|null} - The value of the cookie or null if not found.
 */
const getCookie = async(name) => {
    let cookie = null;

    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) {
        cookie = parts.pop().split(';').shift();
    }

    const userPreference = await getUserPreference('theme_liquid-dark-mode');
    if (userPreference !== null) {
        cookie = userPreference === "dark" ? "1" : "0";
    }

    return cookie;
};

/**
 * Sets a cookie with the given name and value.
 * @param {string} name - The name of the cookie.
 * @param {string} value - The value to set for the cookie.
 */
const setCookie = (name, value) => {
    document.cookie = `${name}=${value}; path=/; SameSite=None; Secure`;
    setUserPreference('theme_liquid-dark-mode', value === "0" ? "light" : "dark");
};

const handleCheckboxChange = (e) => {
    const isDarkThemeEnabled = e.target.checked;

    setTheme(isDarkThemeEnabled ? 'dark' : 'light');
    setCookie("darkThemeEnabled", isDarkThemeEnabled ? "1" : "0");
    updateCheckboxes(isDarkThemeEnabled);
};

const updateCheckboxes = (checked) => {
    darkThemeToggle.forEach(function(checkbox) {
        checkbox.checked = checked;
    });
};

const initializeTheme = async() => {
    const storedDarkThemeSetting = await getCookie("darkThemeEnabled");

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

export const init = () => {
    darkThemeToggle.forEach(function(checkbox) {
        checkbox.addEventListener("change", handleCheckboxChange);
    });

    initializeTheme();
};
