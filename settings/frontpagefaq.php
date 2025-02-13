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

$page = new admin_settingpage('theme_liquid_frontpagefaq', get_string('frontpagefaq', 'theme_liquid'));

// Show or hide faq on the frontpage.
$name = 'theme_liquid/showfaq';
$title = get_string('showfaq', 'theme_liquid');
$desc = get_string('showfaqdesc', 'theme_liquid');
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $desc, $default);
$page->add($setting);

// Number of questions on the frontpage.
$name = 'theme_liquid/numberfaq';
$title = get_string('numberfaq', 'theme_liquid');
$desc = get_string('numberfaqdesc', 'theme_liquid');
$default = 4;
$options = [
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
];
$page->add(new admin_setting_configselect($name, $title, $desc, $default, $options));

// FAQ title.
$name = 'theme_liquid/faqtitle';
$title = get_string('faqtitle', 'theme_liquid');
$desc = get_string('faqtitledesc', 'theme_liquid');
$default = '';
$setting = new admin_setting_configtext($name, $title, $desc, $default);
$page->add($setting);

// Faq description.
$name = 'theme_liquid/faqdescription';
$title = get_string('faqdescription', 'theme_liquid');
$desc = get_string('faqdescriptiondesc', 'theme_liquid');
$default = '';
$setting = new admin_setting_configtextarea($name, $title, $desc, $default);
$page->add($setting);

// Single question settings.
$numberfaq = get_config('theme_liquid', 'numberfaq');
for ($i = 1; $i <= $numberfaq; $i++) {
    // Heading for question.
    $name = 'theme_liquid/faq' . $i . 'info';
    $heading = get_string('faqnumber', 'theme_liquid', ['faq' => $i]);
    $setting = new admin_setting_heading($name, $heading, '');
    $page->add($setting);

    // Question.
    $name = 'theme_liquid/faq' . $i . 'question';
    $title = get_string('faqquestion', 'theme_liquid');
    $desc = get_string('faqquestiondesc', 'theme_liquid');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $desc, $default);
    $page->add($setting);

    // Answer.
    $name = 'theme_liquid/faq' . $i . 'answer';
    $title = get_string('faqanswer', 'theme_liquid');
    $desc = get_string('faqanswerdesc', 'theme_liquid');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $desc, $default);
    $page->add($setting);
}

$settings->add($page);
