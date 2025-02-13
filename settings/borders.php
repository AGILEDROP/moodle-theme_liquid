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

$page = new admin_settingpage('theme_liquid_borders', get_string('borderssettings', 'theme_liquid'));

// Border width.
$title = get_string('borderwidth', 'theme_liquid');
$default = '1px';
$setting = new admin_setting_configtext('theme_liquid/borderwidth', $title, '', $default, PARAM_TEXT);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Rounded base.
$title = get_string('roundedbase', 'theme_liquid');
$default = '1rem';
$setting = new admin_setting_configtext('theme_liquid/roundedbase', $title, '', $default, PARAM_TEXT);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Rounded SM.
$title = get_string('roundedsm', 'theme_liquid');
$default = '0.75rem';
$setting = new admin_setting_configtext('theme_liquid/roundedsm', $title, '', $default, PARAM_TEXT);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Rounded LG.
$title = get_string('roundedlg', 'theme_liquid');
$default = '1.125rem';
$setting = new admin_setting_configtext('theme_liquid/roundedlg', $title, '', $default, PARAM_TEXT);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$settings->add($page);
