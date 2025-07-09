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
 * Course settings
 *
 * @package   local_solent
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2022 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('local_solent_course', get_string('course', 'local_solent'));

// Hidden activities and resources.
$name = 'local_solent/hiddenactivities';
$title = get_string('hiddenactivities', 'local_solent');
$description = get_string('hiddenactivitiesdesc', 'local_solent');
$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_TAGLIST);
$page->add($setting);

$name = 'local_solent/hiddenltis';
$title = get_string('hiddenltis', 'local_solent');
$description = get_string('hiddenltisdesc', 'local_solent');
$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_TAGLIST);
$page->add($setting);

$name = 'local_solent/enablenoroledeletecontent';
$title = new lang_string('enablenoroledeletecontent', 'local_solent');
$desc = new lang_string('enablenoroledeletecontent_desc', 'local_solent');
$setting = new admin_setting_configcheckbox($name, $title, $desc, false);
$page->add($setting);

$name = 'local_solent/maxtablength';
$title = new lang_string('maxtablength', 'local_solent');
$description = new lang_string('maxtablength_desc', 'local_solent');
$options = array_combine(range(30, 255, 5), range(30, 255, 5));
$default = 30;
$setting = new admin_setting_configselect($name, $title, $description, $default, $options);
$page->add($setting);

$name = 'local_solent/progressreport';
$title = new lang_string('progressreport', 'local_solent');
$description = new lang_string('progressreport_desc', 'local_solent');
$setting = new admin_setting_heading($name, $title, $description);
$page->add($setting);

$name = 'local_solent/progressreport_addfields';
$title = new lang_string('progressreport_addfields', 'local_solent');
$description = new lang_string('progressreport_addfields_desc', 'local_solent');
$default = 'idnumber,department,address';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TAGLIST);
$page->add($setting);

$settings->add($page);
