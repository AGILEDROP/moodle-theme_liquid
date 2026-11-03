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
 * @copyright  2025 Agiledrop ltd.
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
    // Include local fonts for all presets, including uploaded presets.
    $scss .= "\n" . file_get_contents($CFG->dirroot . '/theme/liquid/scss/liquid/fonts.scss');
    return $scss;
}

/**
 * Return the explicitly enabled Google Fonts stylesheet URL, if valid.
 *
 * @param theme_config $theme The theme configuration.
 * @return moodle_url|null The stylesheet URL, or null when external loading is disabled or invalid.
 */
function theme_liquid_get_google_font_url($theme): ?moodle_url {
    if (empty($theme->settings->usegooglefonts)) {
        return null;
    }

    $url = trim($theme->settings->googlefonturl ?? '');
    if ($url === '' || clean_param($url, PARAM_URL) !== $url) {
        return null;
    }

    $parts = parse_url($url);
    if (
        $parts === false
        || ($parts['scheme'] ?? '') !== 'https'
        || ($parts['host'] ?? '') !== 'fonts.googleapis.com'
        || !in_array($parts['path'] ?? '', ['/css', '/css2'], true)
        || isset($parts['user'])
        || isset($parts['pass'])
        || isset($parts['port'])
        || isset($parts['fragment'])
    ) {
        return null;
    }

    return new moodle_url($url);
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
        'btnprimarycolor' => ['btn-primary-bg'],
        'btnsecondarycolor' => ['btn-secondary-bg'],
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
