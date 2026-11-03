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
 * Keeps open TinyMCE editors in the page's colour mode.
 *
 * editor_tiny picks the light (oxide) or dark (oxide-dark) skin when an editor is created, and TinyMCE has no API to
 * change the skin of a running editor. This module follows the data-bs-theme attribute of the html tag, which changes
 * when the user switches mode or when "auto" follows a change of the device's colour scheme, and swaps the skin
 * stylesheets of every open editor in place, so the editor keeps its content, selection and undo history.
 *
 * For each editor it swaps:
 * - the UI skin (toolbar, menus, dialogs), a stylesheet shared by all editors on the page;
 * - the content skin, a stylesheet inside the editor's iframe;
 * - the data-bs-theme attribute on the editor container, the menu sink and the iframe's root, which editor_tiny and
 *   the theme's editor stylesheet key their colour mode styles off.
 *
 * TinyMCE counts the uses of the shared UI skin, and unloads the skin an editor was created with when the editor is
 * removed. This module keeps that count right by giving every editor exactly one use of the skin it currently shows.
 *
 * @module     theme_liquid/editor_colourmode
 * @copyright  2026 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

const SKINS = {
    light: 'oxide',
    dark: 'oxide-dark',
};

/** @var {WeakMap} currentSkins The skin each switched editor currently shows, when it is not the one it was created with. */
const currentSkins = new WeakMap();

/** @var {WeakMap} queues The latest switch of each editor, so that switches of one editor never run at the same time. */
const queues = new WeakMap();

let initialised = false;

/**
 * The URL of one of a skin's stylesheets, built the way TinyMCE builds it.
 *
 * @param {Object} editor The TinyMCE editor.
 * @param {String} skin The skin name.
 * @param {String} filename The stylesheet name, without the suffix and extension (skin or content).
 * @returns {String}
 */
const getSkinCssUrl = (editor, skin, filename) =>
    `${editor.editorManager.baseURL}/skins/ui/${skin}/${filename}${editor.editorManager.suffix}.css`;

/**
 * The skin an editor shows at the moment.
 *
 * @param {Object} editor The TinyMCE editor.
 * @returns {String}
 */
const getCurrentSkin = (editor) => currentSkins.get(editor) ?? editor.options.get('skin');

/**
 * Whether this module can switch the editor: an initialised iframe editor using one of TinyMCE's own skins.
 *
 * @param {Object} editor The TinyMCE editor.
 * @returns {Boolean}
 */
const canSwitch = (editor) => editor.initialized && !editor.removed && !editor.inline
    && Object.values(SKINS).includes(editor.options.get('skin'));

/**
 * Release the skin a switched editor holds when the editor is removed.
 *
 * TinyMCE itself unloads the skin the editor was created with. Every editor follows the page, so when this editor is
 * showing the other skin no editor holds the original one any more, and TinyMCE's unload of it does nothing.
 *
 * @param {Object} editor The TinyMCE editor.
 */
const releaseOnRemove = (editor) => {
    editor.on('remove', () => {
        const skin = currentSkins.get(editor);
        if (skin && skin !== editor.options.get('skin')) {
            editor.ui.styleSheetLoader.unload(getSkinCssUrl(editor, skin, 'skin'));
        }
        currentSkins.delete(editor);
    });
};

/**
 * Show an editor in the page's current colour mode.
 *
 * @param {Object} editor The TinyMCE editor.
 */
const switchEditor = async(editor) => {
    const mode = document.documentElement.getAttribute('data-bs-theme');
    const from = getCurrentSkin(editor);
    const to = SKINS[mode];
    if (!to || from === to || !canSwitch(editor)) {
        return;
    }

    // Load the new skins before the old ones are removed, so the editor is never shown unstyled.
    const contentFrom = getSkinCssUrl(editor, from, 'content');
    const contentTo = getSkinCssUrl(editor, to, 'content');
    await Promise.all([
        editor.ui.styleSheetLoader.load(getSkinCssUrl(editor, to, 'skin')),
        editor.dom.styleSheetLoader.load(contentTo),
    ]);
    if (editor.removed) {
        return;
    }

    // The page may have changed mode again while the stylesheets were loading.
    if (document.documentElement.getAttribute('data-bs-theme') !== mode) {
        editor.ui.styleSheetLoader.unload(getSkinCssUrl(editor, to, 'skin'));
        editor.dom.styleSheetLoader.unload(contentTo);
        return;
    }

    editor.ui.styleSheetLoader.unload(getSkinCssUrl(editor, from, 'skin'));
    editor.dom.styleSheetLoader.unload(contentFrom);

    // TinyMCE unloads editor.contentCSS when the editor is removed, so it has to name the content skin now in use.
    const contentIndex = editor.contentCSS.indexOf(contentFrom);
    if (contentIndex !== -1) {
        editor.contentCSS[contentIndex] = contentTo;
    }

    if (!currentSkins.has(editor)) {
        releaseOnRemove(editor);
    }
    currentSkins.set(editor, to);

    editor.getContainer()?.setAttribute('data-bs-theme', mode);
    editor.getDoc()?.documentElement.setAttribute('data-bs-theme', mode);
};

/**
 * Show every open editor in the page's current colour mode.
 */
const switchEditors = () => {
    const mode = document.documentElement.getAttribute('data-bs-theme');
    if (!window.tinymce || !SKINS[mode]) {
        return;
    }

    // The sink holds the menus and dialogs of every editor on the page.
    document.querySelectorAll('.tox-silver-sink').forEach((sink) => sink.setAttribute('data-bs-theme', mode));

    window.tinymce.get().forEach((editor) => {
        const queued = (queues.get(editor) ?? Promise.resolve())
            .then(() => switchEditor(editor))
            .catch(() => {
                // The editor keeps the skin it has. It shows the page's mode after the next page load.
            });
        queues.set(editor, queued);
    });
};

/**
 * Start following the page's colour mode.
 */
export const init = () => {
    if (initialised) {
        return;
    }
    initialised = true;

    new MutationObserver(switchEditors).observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-bs-theme'],
    });
};
