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

$page = new admin_settingpage('theme_liquid_advanced', get_string('advancedsettings', 'theme_liquid'));

// Unaddable blocks.
$default = 'navigation,settings,course_list,section_links';
$title = get_string('unaddableblocks', 'theme_liquid');
$description = get_string('unaddableblocks_desc', 'theme_liquid');
$setting = new admin_setting_configtext('theme_liquid/unaddableblocks', $title, $description, $default, PARAM_TEXT);
$page->add($setting);

// Presets.
$name = 'theme_liquid/preset';
$title = get_string('preset', 'theme_liquid');
$description = get_string('preset_desc', 'theme_liquid');
$default = 'default.scss';

$context = context_system::instance();
$fs = get_file_storage();
$files = $fs->get_area_files($context->id, 'theme_liquid', 'preset', 0, 'itemid, filepath, filename', false);

$choices = [];
foreach ($files as $file) {
    $choices[$file->get_filename()] = $file->get_filename();
}

$choices['default.scss'] = 'default.scss';
$choices['plain.scss'] = 'plain.scss';

$setting = new admin_setting_configthemepreset($name, $title, $description, $default, $choices, 'liquid');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$name = 'theme_liquid/presetfiles';
$title = get_string('presetfiles', 'theme_liquid');
$description = get_string('presetfiles_desc', 'theme_liquid');

$setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
    ['maxfiles' => 20, 'accepted_types' => ['.scss']]);
$page->add($setting);

// Raw SCSS to include before the content.
$setting = new admin_setting_scsscode('theme_liquid/scsspre',
    get_string('rawscsspre', 'theme_liquid'), get_string('rawscsspre_desc', 'theme_liquid'), '', PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Raw SCSS to include after the content.
$setting = new admin_setting_scsscode('theme_liquid/scss', get_string('rawscss', 'theme_liquid'),
    get_string('rawscss_desc', 'theme_liquid'), '', PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

$settings->add($page);
