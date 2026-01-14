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

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_liquid_typography', get_string('typographysettings', 'theme_liquid'));

// Google font URL.
$title = get_string('googlefonturl', 'theme_liquid');
$description = get_string('googlefonturl_desc', 'theme_liquid');
$default = 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap';
$setting = new admin_setting_configtext('theme_liquid/googlefonturl', $title, $description, $default, PARAM_TEXT);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Font family.
$title = get_string('fontfamily', 'theme_liquid');
$description = get_string('fontfamily_desc', 'theme_liquid');
$default = '\'Inter\', sans-serif';
$setting = new admin_setting_configtext('theme_liquid/fontfamily', $title, $description, $default, PARAM_TEXT);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Body font size, rem.
$name = 'theme_liquid/bodyfont';
$title = get_string('bodyfont', 'theme_liquid');
$default = '0.875rem';
$options = [
    '0.75rem' => '0.75',
    '0.875rem' => '0.875',
    '1rem' => '1',
    '1.05rem' => '1.05',
    '1.1rem' => '1.1',
    '1.15rem' => '1.15',
    '1.25rem' => '1.25',
    '1.375rem' => '1.375',
];

$setting = new admin_setting_configselect($name, $title, '', $default, $options);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$settings->add($page);
