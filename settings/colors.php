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

$page = new admin_settingpage('theme_liquid_colors', get_string('colorssettings', 'theme_liquid'));

// Primary color.
$name = 'theme_liquid/primarycolor';
$title = get_string('primarycolor', 'theme_liquid');
$default = '#032633';
$setting = new admin_setting_configcolourpicker($name, $title, '', $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Text color on primary color.
$name = 'theme_liquid/textprimarycolor';
$title = get_string('textprimarycolor', 'theme_liquid');
$default = '#FFFFFF';
$setting = new admin_setting_configcolourpicker($name, $title, '', $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Success color.
$name = 'theme_liquid/successcolor';
$title = get_string('successcolor', 'theme_liquid');
$default = '#3FCA90';
$setting = new admin_setting_configcolourpicker($name, $title, '', $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Info color.
$name = 'theme_liquid/infocolor';
$title = get_string('infocolor', 'theme_liquid');
$default = '#3F7FCA';
$setting = new admin_setting_configcolourpicker($name, $title, '', $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Warning color.
$name = 'theme_liquid/warningcolor';
$title = get_string('warningcolor', 'theme_liquid');
$default = '#EDCB50';
$setting = new admin_setting_configcolourpicker($name, $title, '', $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Danger color.
$name = 'theme_liquid/dangercolor';
$title = get_string('dangercolor', 'theme_liquid');
$default = '#ED5050';
$setting = new admin_setting_configcolourpicker($name, $title, '', $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Secondary color.
$name = 'theme_liquid/secondarycolor';
$title = get_string('secondarycolor', 'theme_liquid');
$default = '#EE4723';
$setting = new admin_setting_configcolourpicker($name, $title, '', $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Text color on secondary color.
$name = 'theme_liquid/textsecondarycolor';
$title = get_string('textsecondarycolor', 'theme_liquid');
$default = '#FFFFFF';
$setting = new admin_setting_configcolourpicker($name, $title, '', $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$settings->add($page);
