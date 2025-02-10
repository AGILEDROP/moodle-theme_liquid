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
 * Liquid.
 *
 * @package    theme_liquid
 * @copyright  2023 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_liquid_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/liquid/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/liquid/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_liquid', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        $scss .= file_get_contents($CFG->dirroot . '/theme/liquid/scss/preset/default.scss');
    }
    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return array
 */
function theme_liquid_get_pre_scss($theme) {
    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'primarycolor' => ['primary'],
        'textprimarycolor' => ['text-on-primary'],
        'successcolor' => ['success'],
        'infocolor' => ['info'],
        'warningcolor' => ['warning'],
        'dangercolor' => ['danger'],
        'secondarycolor' => ['secondary'],
        'textsecondarycolor' => ['text-on-secondary'],
        'fontfamily' => ['font-family-base'],
        'bodyfont' => ['font-size-base'],
        'borderwidth' => ['border-width'],
        'roundedbase' => ['border-radius'],
        'roundedlg' => ['border-radius-lg'],
        'roundedsm' => ['border-radius-sm'],
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function ($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array)$targets);
    }

    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_liquid_get_extra_scss($theme) {
    $content = '';
    return !empty($theme->settings->scss) ? $theme->settings->scss . ' ' . $content : $content;
}

/**
 * Set HTML attributes for dark mode.
 *
 *  This is a legacy callback that is used for compatibility with older Moodle versions.
 *  Moodle 4.4+ will use theme_liquid\hook_callbacks::before_html_attributes instead.
 *
 * @return array An associative array of HTML attributes.
 */
function theme_liquid_add_htmlattributes() {
    $darkthemecookie = isset($_COOKIE['darkThemeEnabled']) ? $_COOKIE['darkThemeEnabled'] : null;
    $theme = ($darkthemecookie === '1') ? 'dark' : 'light';

    return [
        'data-bs-theme' => $theme,
    ];
}

/**
 * Return theme setting value.
 *
 * @param string $setting
 * @param mixed $format
 * @return mixed
 */
function theme_liquid_setting($setting, $format = true) {
    global $CFG, $PAGE;

    require_once($CFG->dirroot . '/lib/weblib.php');
    $theme = theme_config::load('liquid');

    if (empty($theme->settings->$setting)) {
        return false;
    }

    if ($format === false) {
        return $theme->settings->$setting;
    }

    switch ($format) {
        case 'format_text':
            return format_text($theme->settings->$setting, FORMAT_PLAIN);

        case 'format_html':
            return format_text($theme->settings->$setting, FORMAT_HTML, ['trusted' => true, 'noclean' => true]);

        case 'file':
            return $PAGE->theme->setting_file_url($setting, $setting);

        default:
            return format_string($theme->settings->$setting);
    }
}

/**
 * Serve files form theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_liquid_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    static $theme;

    if (empty($theme)) {
        $theme = theme_config::load('liquid');
    }
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        if (preg_match("/slide[1-9][0-9]*image/", $filearea) !== false) {
            return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
        } else {
            send_file_not_found();
        }
    } else {
        send_file_not_found();
    }
}
