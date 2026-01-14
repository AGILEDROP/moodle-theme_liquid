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

namespace theme_liquid\output\core;

use context_course;
use core_course_category;
use lang_string;
use moodle_url;

/**
 * The core course renderer.
 *
 * Can be retrieved with the following:
 * $renderer = $PAGE->get_renderer('core','course');
 */
class course_renderer extends \core_course_renderer
{
    /**
     * Returns HTML to print list of available courses for the frontpage
     *
     * @return string
     */
    public function frontpage_available_courses()
    {
        global $CFG;

        $chelper = new \coursecat_helper();
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED);
        $chelper->set_courses_display_options([
            'recursive' => true,
            'limit' => $CFG->frontpagecourselimit,
            'viewmoreurl' => new moodle_url('/course/index.php'),
            'viewmoretext' => new lang_string('fulllistofcourses'),
        ]);

        $chelper->set_attributes(['class' => 'frontpage-course-list-all']);
        $courses = \core_course_category::top()->get_courses($chelper->get_courses_display_options());
        $totalcount = \core_course_category::top()->get_courses_count($chelper->get_courses_display_options());

        if (
            !$totalcount && !$this->page->user_is_editing() && has_capability(
                'moodle/course:create',
                \context_system::instance()
            )
        ) {
            return $this->add_new_course_button();
        }

        if (!empty($courses)) {
            $data = [];
            foreach ($courses as $course) {
                $data[] = $this->available_coursebox($course);
            }

            return $this->output->render_from_template(
                "theme_liquid/frontpage/available_courses",
                ['courses' => $data],
            );
        }
    }

    /**
     * Return contents for the available course block on the frontpage.
     *
     * @param array $course course detials.
     *
     * @return array $data available course data.
     */
    public function available_coursebox($course)
    {
        $coursecontext = context_course::instance($course->id);

        // Category.
        static $categoriescache = [];
        if (!array_key_exists($course->category, $categoriescache)) {
            $categoriescache[$course->category] = core_course_category::get($course->category, IGNORE_MISSING);
        }
        $category = $categoriescache[$course->category];
        $categoryname = '';
        if (!empty($category)) {
            $categoryname = \core_external\util::format_string($category->name, $category->get_context());
        }

        // Image.
        $courseimage = \core_course\external\course_summary_exporter::get_course_image($course);
        if (!$courseimage) {
            $courseimage = $this->output->get_generated_image_for_id($course->id);
        }

        // Course data.
        $data = [];
        $data['id'] = $course->id;
        $data['fullname'] = \core_external\util::format_string($course->fullname, $coursecontext);
        $data['viewurl'] = (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false);
        $data['coursecategory'] = $categoryname;
        $data['courseimage'] = $courseimage;

        return $data;
    }
}
