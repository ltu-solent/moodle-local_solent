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
 * Helper functions
 *
 * @package   local_solent
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2023 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_solent;

use context_course;
use context_system;
use core_course_category;

class helper {
    /**
     * Is this course a module page.
     *
     * @param stdClass $course Course object
     * @return boolean
     */
    public static function is_module($course) {
        global $DB;
        $course = (object)$course;
        if (!isset($course->category)) {
            $course = $DB->get_record('course', ['id' => $course->id]);
        }
        $category = core_course_category::get($course->category, IGNORE_MISSING);
        $cattype = self::get_category_type($category);
        return $cattype == 'modules';
    }

    /**
     * Is this category a course or module category. Returns the type.
     *
     * @param core_course_category $category
     * @return string modules, courses
     */
    public static function get_category_type(core_course_category $category) {
        $catparts = explode('_', $category->idnumber);
        $cattype = $catparts[0]; // Modules, Courses.
        return $cattype;
    }
}