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
 * @package   local_solent
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2022 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 /**
  * Called by theme's page_init function.
  *
  * @param moodle_page $page
  * @return void
  */
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
            // Delete content if the current user has no role in this course.
            if (isset($config->enablenoroledeletecontent) && $config->enablenoroledeletecontent == 1) {
                $page->requires->js_call_amd('local_solent/enrolments', 'deleteContent');
            }
        }
    }

    if ($page->pagetype == 'backup-import') {
        $page->requires->js_call_amd('local_solent/backup', 'getImportRestrictions');
    }

    $page->requires->strings_for_js([
        'noroleerror'
    ], 'local_solent');
}

/**
 * This function allows us to override any webservice function. Use it with care.
 * https://docs.moodle.org/dev/Miscellaneous_callbacks#override_webservice_execution
 *
 * @param object $function Details of the function to be called.
 * @param array $params Parameters passed by the ajax call.
 * @return boolean|array If the callback returns anything other than false, we assume it replaces the original function.
 */
function local_solent_override_webservice_execution($function, $params) {
    if ($function->name === 'core_course_get_course_content_items') {
        $result = call_user_func_array([$function->classname, $function->methodname], $params);
        foreach ($result['content_items'] as $index => $contentitem) {
            $hiddenactivities = explode(',', get_config('local_solent', 'hiddenactivities'));
            if (in_array($contentitem->name, $hiddenactivities)) {
                unset($result['content_items'][$index]);
            }
        }
        return $result;
    }
    // phpcs:disable
    if ($function->name === 'core_courseformat_get_state') {
        // $result = call_user_func_array([$function->classname, $function->methodname], $params);
        // $decoded = json_decode($result);
        // $updated = false;
        // foreach ($decoded->cm as $key => $cm) {
        //     $name = preg_replace('/\[(fa\-[a-z\-]+)\]/', '<i class="fa $1"></i>', $cm->name);
        //     // error_log("+++++++++++++++++++++++++++++++" . $name);
        //     if ($name != $cm->name) {
        //         $decoded->cm[$key]->name = $name;
        //         $updated = true;
        //     }
        // }
        // if ($updated) {
        //     return json_encode($decoded);
        // }
        // foreach ($result['content_items'] as $index => $contentitem) {
        //     if (!\local_solent\canuse($contentitem->name, $courseid)) {
        //         unset($result['content_items'][$index]);
        //     }
        // }
        // return $result;
    }
    // phpcs:enable
    return false;
}
