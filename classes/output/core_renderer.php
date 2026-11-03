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

namespace theme_liquid\output;

use context_header;
use html_writer;
use theme_config;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 */
class core_renderer extends \theme_boost\output\core_renderer {
    /**
     * Add a heading above the password recovery instructions.
     *
     * @param string $contents Box content.
     * @param string|array $classes Box classes.
     * @param string|null $id Optional box ID.
     * @param array $attributes Additional attributes.
     * @return string Rendered HTML.
     */
    public function box($contents, $classes = 'generalbox', $id = null, $attributes = []) {
        $output = parent::box($contents, $classes, $id, $attributes);
        if (
            $this->page->pagetype === 'login-forgot_password'
            && $contents === get_string('passwordforgotteninstructions2')
        ) {
            $heading = $this->heading(get_string('resetpasswordtitle', 'theme_liquid'), 1, 'login-heading mb-4');
            return html_writer::div($heading . $output, 'forgot-password-instructions w-100');
        }

        return $output;
    }

    /**
     * Use login-style inputs for the password recovery search fields.
     *
     * @param string $templatename Template to render.
     * @param array|\stdClass $context Template context.
     * @return string Rendered HTML.
     */
    public function render_from_template($templatename, $context) {
        if ($this->page->pagetype === 'login-forgot_password' && $templatename === 'core_form/element-text') {
            $context = (array) $context;
            $fieldname = $context['element']['name'];
            if (in_array($fieldname, ['username', 'email'], true)) {
                $context['element']['hiddenlabel'] = true;
                $context['inputplaceholder'] = get_string($fieldname);
                $context['inputicon'] = $fieldname === 'email' ? 'i/email' : 'i/user';
                $templatename = 'theme_liquid/forgot_password_input';
            }
        }

        return parent::render_from_template($templatename, $context);
    }

    /**
     * The standard tags (meta tags, links to stylesheets and JavaScript, etc.)
     * that should be included in the <head> tag. Designed to be called in theme
     * layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_head_html() {
        $output = parent::standard_head_html();
        $theme = theme_config::load('liquid');
        $googlefont = theme_liquid_get_google_font_url($theme);
        if ($googlefont !== null) {
            $output .= html_writer::empty_tag('link', [
                'href' => $googlefont->out(false),
                'rel' => 'stylesheet',
                'referrerpolicy' => 'no-referrer',
            ]);
        }

        return $output;
    }

    /**
     * Renders the header bar.
     *
     * @param context_header $contextheader Header bar object.
     * @return string HTML for the header bar.
     */
    protected function render_context_header(\context_header $contextheader) {
        if (!isset($contextheader->heading)) {
            $heading = $this->heading($this->page->heading, $contextheader->headinglevel, 'h1');
        } else {
            $heading = $this->heading($contextheader->heading, $contextheader->headinglevel, 'h1');
        }

        $html = html_writer::start_div('page-context-header');

        if (isset($contextheader->imagedata)) {
            $html .= html_writer::div($contextheader->imagedata, 'page-header-image mr-2');
        }

        if (isset($contextheader->prefix)) {
            $prefix = html_writer::div($contextheader->prefix, 'text-muted text-uppercase small line-height-3');
            $heading = $prefix . $heading;
        }
        $html .= html_writer::tag('div', $heading, ['class' => 'page-header-headings']);

        if (isset($contextheader->additionalbuttons)) {
            $html .= html_writer::start_div('btn-group header-button-group');
            foreach ($contextheader->additionalbuttons as $button) {
                if (!isset($button->page)) {
                    if ($button['buttontype'] === 'togglecontact') {
                        \core_message\helper::togglecontact_requirejs();
                    }
                    if ($button['buttontype'] === 'message') {
                        \core_message\helper::messageuser_requirejs();
                    }
                    $image = $this->pix_icon($button['formattedimage'], $button['title'], 'moodle', [
                        'class' => 'iconsmall',
                        'role' => 'presentation',
                    ]);
                    $image .= html_writer::span($button['title'], 'header-button-title');
                } else {
                    $image = html_writer::empty_tag('img', [
                        'src' => $button['formattedimage'],
                        'role' => 'presentation',
                    ]);
                }
                $html .= html_writer::link($button['url'], html_writer::tag('span', $image), $button['linkattributes']);
            }
            $html .= html_writer::end_div();
        }
        $html .= html_writer::end_div();

        return $html;
    }
}
