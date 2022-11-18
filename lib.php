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
 * Lib file
 *
 * @package   local_Solent
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2022 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function local_solent_page_init(moodle_page $page) {
    global $CFG, $COURSE, $DB;
    $systemcontext = context_system::instance();
    $coursecontext = context_course::instance($COURSE->id);
    $config = get_config('local_solent');
    // Set up body classes to be used by scripts.
    if (isloggedin()) {
        $page->add_body_class('loggedin');
    }

    if (is_siteadmin()) {
        $page->add_body_class('systemrole-admin');
    }

    if (is_enrolled($coursecontext)) {
        $page->add_body_class('coursestatus-enrolled');
    }

    if (is_viewing($coursecontext)) {
        $page->add_body_class('coursestatus-viewing');
    }

    if (is_guest($coursecontext)) {
        $page->add_body_class('courserole-guest'); // Probably don't need this.
    }

    if ($roles = get_user_roles($coursecontext)) {
        foreach ($roles as $role) {
            $page->add_body_class('courserole-' . $role->shortname);
        }
    } else {
        if (count($roles) === 0 && !is_guest($coursecontext) && !is_siteadmin() && $COURSE->category > 0) {
            $page->add_body_class('courserole-none');
        }
    }
    // Delete content if the current user has no role in this course.
    if (isset($config->enablenoroledeletecontent)) {
        $page->requires->js_call_amd('local_solent/enrolments', 'deleteContent');
    }

    $page->requires->strings_for_js([
        'noroleerror'
    ], 'local_solent');
}