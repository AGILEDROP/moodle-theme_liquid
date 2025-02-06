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

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

$PAGE->requires->css(new moodle_url('/theme/liquid/js/swiper/swiper-bundle.min.css'));
$PAGE->requires->js_call_amd('theme_liquid/frontpage', 'init');

$addblockbutton = $OUTPUT->addblockbutton();

$extraclasses = ['frontpage'];
if (theme_liquid_setting('leftnavigation') == 0) {
    $extraclasses[] = 'no-left-nav';
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$secondarynavigation = false;
$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$PAGE->requires->js_call_amd('theme_liquid/darkmode', 'init');

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'leftnavigation' => theme_liquid_setting('leftnavigation'),
];

// Frontpage slider data.
$slider = [];
$slider['showslider'] = theme_liquid_setting('showslider');
$slider['numberslides'] = theme_liquid_setting('numberslides');
for ($i = 1; $i <= $slider['numberslides']; $i++) {
    $slide = [];
    $slide['slidestatus'] = theme_liquid_setting('slide' . $i . 'status');
    $slide['slideimage'] = $PAGE->theme->setting_file_url('slide' . $i . 'image', 'slide' . $i . 'image');
    $slide['slidetext'] = theme_liquid_setting('slide' . $i . 'text', 'format_html');
    $slide['slidelink'] = theme_liquid_setting('slide' . $i . 'link');
    $slider['slides'][] = $slide;
}

$templatecontext += $slider;

// Frontpage FAQ data.
$faq = [];
$faq['showfaq'] = theme_liquid_setting('showfaq');
$faq['numberfaq'] = theme_liquid_setting('numberfaq');
$faq['faqtitle'] = theme_liquid_setting('faqtitle');
$faq['faqdescription'] = theme_liquid_setting('faqdescription');
for ($i = 1; $i <= $faq['numberfaq']; $i++) {
    $question = [];
    $question['isfirst'] = false;
    if ($i === 1) {
        $question['isfirst'] = true;
    }
    $question['faqid'] = $i;
    $question['faqquestion'] = theme_liquid_setting('faq' . $i . 'question');
    $question['faqanswer'] = theme_liquid_setting('faq' . $i . 'answer', 'format_html');
    $faq['questions'][] = $question;
}

$templatecontext += $faq;

echo $OUTPUT->render_from_template('theme_liquid/frontpage', $templatecontext);
