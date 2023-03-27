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
 * Externallib web services test
 *
 * @package   local_solent
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2023 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_solent;

use externallib_advanced_testcase;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/webservice/tests/helpers.php');
require_once($CFG->dirroot . '/local/solent/externallib.php');

/**
 * Test externallib functions
 */
class externallib_test extends externallib_advanced_testcase {

    /**
     * Get import restrictions. Ajax function.
     *
     * @covers \local_solent_external::get_import_restrictions
     * @return void
     */
    public function test_get_import_restrictions() {
        $this->resetAfterTest();
        // Exclude empty lines; lines with invalid (including partial) entries; comment lines.
        $settings = <<<SETTINGS
activity=forum|title=Unit announcements
title=For guidance and support
activity=turnitintooltwo
activity=assign
rubbish=don't include

title=[fa fa-beer] Escape this
activity=label|rubbish=don't include
title=Escape (this)
title=Escape {this}
activity=label|title=Collaborative learningCollaborative learning...
# This is a comment
SETTINGS;
        set_config('restrictbackupactivities', $settings, 'local_solent');
        $restrictions = \local_solent_external::get_import_restrictions();
        $this->assertCount(8, $restrictions);
        $this->assertSame(['activity' => 'forum', 'title' => 'Unit announcements'], $restrictions[0]);
        $this->assertSame(['activity' => null, 'title' => 'For guidance and support'], $restrictions[1]);
        $this->assertSame(['activity' => 'turnitintooltwo', 'title' => null], $restrictions[2]);
        $this->assertSame(['activity' => 'assign', 'title' => null], $restrictions[3]);
        $this->assertSame(['activity' => null, 'title' => '\[fa fa\-beer\] Escape this'], $restrictions[4]);
        $this->assertSame(['activity' => null, 'title' => 'Escape \(this\)'], $restrictions[5]);
        $this->assertSame(['activity' => null, 'title' => 'Escape \{this\}'], $restrictions[6]);
        $this->assertSame(['activity' => 'label', 'title' => 'Collaborative learningCollaborative learning\.\.\.'], $restrictions[7]);
    }
}
