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
 * Solent language file
 *
 * @package   local_solent
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2022 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['backup'] = 'Backup';

$string['course'] = 'Course';

$string['enablenoroledeletecontent'] = 'Enable delete "No role" content';
$string['enablenoroledeletecontent_desc'] = 'If someone is enrolled on a page, but they have no role, this will delete all the content, except the navigation.';

$string['maxtablength'] = 'Max tab length';
$string['maxtablength_desc'] = 'Max tab length in characters. Only applies to Module pages.';

$string['noroleerror'] = 'You are not correctly enrolled on this page. Please contact Guided Learning on <a href="https://learn.solent.ac.uk/ask">https://learn.solent.ac.uk/ask</a>';

$string['pluginname'] = 'Solent modifications';
$string['progressreport'] = 'Progress report';
$string['progressreport_desc'] = 'Override settings for the course progress report';
$string['progressreport_addfields'] = 'Add user fields';
$string['progressreport_addfields_desc'] = 'Add these user fields to the output (comma separated list of user fields).';

$string['restrictbackupactivities'] = 'Restrict backup activities';
$string['restrictbackupactivities_desc'] = '<p>Enter details of the activities that should be excluded from being backed up when doing a rollover.</p>
<p>One entry per line. Each entry is separated by a pipe (|) and has a key/value pair where the key is "activity" or "title"
and the value for "activity" could include "assign", "label", "folder" etc. And the value for "title" is the title, or intro text for labels.</p>
<p>The program will search for the title in the whole text, so it doesn\'t need to be complete, but enough to uniquely identifiy the activity.</p>
<p>You don\'t have to have both "activity" and "title", but you must have at least one.</p>';

$string['startdatestring'] = ' - Start date: {$a}';
$string['studentid'] = 'Student ID';
$string['submissiontypesinfo'] = '<div class="alert alert-info">Submission types - click the help icon <i class="icon fa fa-question-circle text-info fa-fw"></i>for information about submission settings.</div>';
