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
 * Light and dark mode switch for theme Liquid.
 *
 * The chosen mode is stored the same way as Boost's colour modes (theme_boost/colourmode): in the
 * theme_boost_colourmode user preference and the cookie of the same name. The server renders the mode on the html tag
 * (see theme_liquid\hook_callbacks), and a script in the page head resolves "auto" to the device's colour scheme, so
 * nothing needs to be loaded before the page is shown.
 *
 * @module     theme_liquid/darkmode
 * @copyright  2025 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {setUserPreference} from 'core_user/repository';
import {add as addToast} from 'core/toast';
import Prefetch from 'core/prefetch';

const SELECTORS = {
    TOGGLE: '.darktheme-checkbox',
};

const MODES = {
    LIGHT: 'light',
    DARK: 'dark',
    AUTO: 'auto',
};

/** @var {Object|null} config The configuration from theme_liquid\colour_mode::get_js_config(). */
let config = null;

/** @var {boolean} saving Whether we are currently saving the user's preference. */
let saving = false;

/**
 * Set every switch on the page to the mode currently shown.
 */
const syncToggles = () => {
    const isdark = document.documentElement.getAttribute('data-bs-theme') === MODES.DARK;
    document.querySelectorAll(SELECTORS.TOGGLE).forEach((toggle) => {
        toggle.checked = isdark;
    });
};

/**
 * Store the mode in the browser, so that pages nobody is logged in to, such as the login page, are shown in it.
 *
 * @param {string} mode One of the MODES.
 */
const rememberMode = (mode) => {
    document.cookie = `${config.cookiename}=${mode}${config.cookieattributes}`;
};

/**
 * Remove the cookie Liquid used before Moodle 5.3, once its value has been carried over.
 */
const forgetLegacyCookie = () => {
    document.cookie = `${config.legacycookiename}=; Max-Age=0; path=/; SameSite=None; Secure`;
};

/**
 * Show the page in the chosen mode and store the choice.
 *
 * @param {string} mode Either MODES.LIGHT or MODES.DARK.
 */
const applyMode = async(mode) => {
    if (saving) {
        syncToggles();
        return;
    }

    saving = true;
    const toggles = [...document.querySelectorAll(SELECTORS.TOGGLE)];
    toggles.forEach(toggle => {
        toggle.disabled = true;
    });

    // The checkbox changed on click; restore it until saving succeeds.
    syncToggles();

    try {
        if (config.canstore) {
            await setUserPreference(config.preference, mode);
        }

        const root = document.documentElement;
        root.setAttribute('data-colourmode', mode);
        root.setAttribute('data-bs-theme', mode);
        rememberMode(mode);
    } catch {
        addToast(config.saveerrormessage, {
            type: 'warning',
            autohide: true,
            delay: 5000,
            closeButton: true,
        });
    } finally {
        syncToggles();
        toggles.forEach(toggle => {
            toggle.disabled = false;
        });
        saving = false;
    }
};

/**
 * Initialise the light and dark mode switches.
 *
 * @param {Object} settings The configuration from theme_liquid\colour_mode::get_js_config().
 * @param {boolean} settings.canstore Whether the mode can be stored as a user preference.
 * @param {string} settings.cookieattributes The attributes to store the cookie with.
 * @param {string} settings.cookiename The name of the cookie which stores the mode.
 * @param {string} settings.legacycookiename The name of the cookie Liquid used before Moodle 5.3.
 * @param {string} settings.mode The mode the server rendered the page in.
 * @param {string} settings.preference The name of the user preference which stores the mode.
 */
export const init = (settings) => {
    if (config) {
        return;
    }
    config = settings;
    Prefetch.prefetchTemplate('core/local/toast/message');

    // Refresh the browser copy from the mode the server resolved, so that it belongs to whoever is logged in now,
    // and carry a choice made before Moodle 5.3 over to it.
    rememberMode(config.mode);
    forgetLegacyCookie();

    syncToggles();

    // While no mode has been chosen the page follows the device, and the script in the page head updates the mode.
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (document.documentElement.getAttribute('data-colourmode') === MODES.AUTO) {
            syncToggles();
        }
    });

    document.addEventListener('change', (e) => {
        const toggle = e.target.closest(SELECTORS.TOGGLE);
        if (toggle) {
            applyMode(toggle.checked ? MODES.DARK : MODES.LIGHT);
        }
    });
};
