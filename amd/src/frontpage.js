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
 * Frontpage functionality for theme Liquid.
 *
 * @module     theme_liquid/frontpage
 * @copyright  2025 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['theme_liquid/swiper'], function(Swiper) {
    /**
     * Initializes Swiper instances on all elements with the 'swiper' class.
     */
    function initSwiper() {
        var swipers = document.querySelectorAll('.swiper');

        swipers.forEach(function(swiperElement) {
            var options = JSON.parse(swiperElement.getAttribute('data-swiper-options'));
            new Swiper(swiperElement, options);
        });
    }

    return {
        init: function() {
            initSwiper();
        }
    };
});