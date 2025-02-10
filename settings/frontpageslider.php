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

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_liquid_frontpageslider', get_string('frontpageslider', 'theme_liquid'));

// Show or hide slider on the frontpage.
$name = 'theme_liquid/showslider';
$title = get_string('showslider', 'theme_liquid');
$desc = get_string('showsliderdesc', 'theme_liquid');
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $desc, $default);
$page->add($setting);

// Number of slides on the frontpage.
$name = 'theme_liquid/numberslides';
$title = get_string('numberslides', 'theme_liquid');
$desc = get_string('numberslidesdesc', 'theme_liquid');
$default = 1;
$options = [
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
];
$page->add(new admin_setting_configselect($name, $title, $desc, $default, $options));

// Single slide settings.
$numberslides = get_config('theme_liquid', 'numberslides');
for ($i = 1; $i <= $numberslides; $i++) {
    // Heading for single slide.
    $name = 'theme_liquid/slide' . $i . 'info';
    $heading = get_string('slidenumber', 'theme_liquid', ['slide' => $i]);
    $setting = new admin_setting_heading($name, $heading, '');
    $page->add($setting);

    // Enable or disable option for slide show.
    $name = 'theme_liquid/slide' . $i . 'status';
    $title = get_string('slideshow', 'theme_liquid', ['slide' => $i]);
    $desc = get_string('slideshowdesc', 'theme_liquid', ['slide' => $i]);
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $desc, $default);
    $page->add($setting);

    // Slide image.
    $name = 'theme_liquid/slide' . $i . 'image';
    $title = get_string('slideimage', 'theme_liquid');
    $desc = get_string('slideimagedesc', 'theme_liquid');
    $setting = new admin_setting_configstoredfile($name, $title, $desc, 'slide' . $i . 'image');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Text for slide.
    $name = 'theme_liquid/slide' . $i . 'text';
    $title = get_string('slidetext', 'theme_liquid');
    $desc = get_string('slidetextdesc', 'theme_liquid');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $desc, $default);
    $page->add($setting);

    // Link for slide.
    $name = 'theme_liquid/slide' . $i . 'link';
    $title = get_string('slidelink', 'theme_liquid');
    $desc = get_string('slidelinkdesc', 'theme_liquid');
    $default = '#';
    $setting = new admin_setting_configtext($name, $title, $desc, $default);
    $page->add($setting);
}

$settings->add($page);
