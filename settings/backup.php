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
 * Backup and Import settings
 *
 * @package   local_solent
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2022 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('local_solent_backup', get_string('backup', 'local_solent'));

$name = 'local_solent/restrictbackupactivities';
$title = new lang_string('restrictbackupactivities', 'local_solent');
$desc = new lang_string('restrictbackupactivities_desc', 'local_solent');
$default = '
activity=forum|title=Unit announcements
title=For guidance and support
activity=turnitintooltwo
activity=assign';
$setting = new admin_setting_configtextarea($name, $title, $desc, $default);
$page->add($setting);

$settings->add($page);
