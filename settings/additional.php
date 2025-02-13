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

$page = new admin_settingpage('theme_liquid_additional', get_string('additionalsettings', 'theme_liquid'));

// Heading for navigation.
$name = 'theme_liquid/navigationtitle';
$heading = get_string('navigationtitle', 'theme_liquid');
$setting = new admin_setting_heading($name, $heading, '');
$page->add($setting);

// Left navigation.
$name = 'theme_liquid/leftnavigation';
$title = get_string('leftnavigation', 'theme_liquid');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, '', $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Heading for social icons.
$name = 'theme_liquid/socialiconstitle';
$heading = get_string('socialiconstitle', 'theme_liquid');
$setting = new admin_setting_heading($name, $heading, '');
$page->add($setting);

// Facebook link.
$name = 'theme_liquid/facebooklink';
$title = get_string('facebooklink', 'theme_liquid');
$desc = '';
$default = '';
$setting = new admin_setting_configtext($name, $title, $desc, $default);
$page->add($setting);

// X link.
$name = 'theme_liquid/xlink';
$title = get_string('xlink', 'theme_liquid');
$desc = '';
$default = '';
$setting = new admin_setting_configtext($name, $title, $desc, $default);
$page->add($setting);

// Instagram link.
$name = 'theme_liquid/instagramlink';
$title = get_string('instagramlink', 'theme_liquid');
$desc = '';
$default = '';
$setting = new admin_setting_configtext($name, $title, $desc, $default);
$page->add($setting);

// Linkedin link.
$name = 'theme_liquid/linkedinlink';
$title = get_string('linkedinlink', 'theme_liquid');
$desc = '';
$default = '';
$setting = new admin_setting_configtext($name, $title, $desc, $default);
$page->add($setting);

// Youtube link.
$name = 'theme_liquid/youtubelink';
$title = get_string('youtubelink', 'theme_liquid');
$desc = '';
$default = '';
$setting = new admin_setting_configtext($name, $title, $desc, $default);
$page->add($setting);

// Tiktok link.
$name = 'theme_liquid/tiktoklink';
$title = get_string('tiktoklink', 'theme_liquid');
$desc = '';
$default = '';
$setting = new admin_setting_configtext($name, $title, $desc, $default);
$page->add($setting);

$settings->add($page);
