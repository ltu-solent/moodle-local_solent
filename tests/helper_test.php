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
 * Tests for helper function
 *
 * @package   local_solent
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2023 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_solent;

use advanced_testcase;

defined('MOODLE_INTERNAL') || die();

global $CFG;

/**
 * Tests for local_solent helper functions
 * @group sol
 */
class helper_test extends advanced_testcase {
    /**
     * Test get category type, mostly used to distinguish modules and courses
     *
     * @param array $category
     * @param string $response
     * @return void
     * @covers \local_solent\helper::get_category_type
     * @dataProvider get_category_type_provider
     */
    public function test_get_category_type($category, $response) {
        $this->resetAfterTest();
        $cat = $this->getDataGenerator()->create_category($category);
        $type = helper::get_category_type($cat);
        $this->assertEquals($response, $type);
    }

    /**
     * Provider for test_get_category_type
     *
     * @return array
     */
    public function get_category_type_provider() {
        return [
            'modules' => [
                'category' => [
                    'name' => 'Modules',
                    'idnumber' => 'modules_ABC101'
                ],
                'response' => 'modules'
            ],
            'courses' => [
                'category' => [
                    'name' => 'Courses',
                    'idnumber' => 'courses_ABC101'
                ],
                'response' => 'courses'
            ],
            'empty' => [
                'category' => [
                    'name' => 'Nothing special',
                    'idnumber' => ''
                ],
                'response' => ''
            ],
            'random' => [
                'category' => [
                    'name' => 'RANDOM',
                    'idnumber' => 'RANDOM'
                ],
                'response' => 'RANDOM'
            ]
        ];
    }

    /**
     * Test for is_module
     *
     * @param string|null $category
     * @param bool $response
     * @return void
     * @covers \local_solent\helper::is_module
     * @dataProvider is_module_provider
     */
    public function test_is_module($category, $response) {
        $this->resetAfterTest();
        $catid = null;
        if ($category) {
            $cat = $this->getDataGenerator()->create_category(['idnumber' => $category]);
            $catid = $cat->id;
        }
        $course = $this->getDataGenerator()->create_course(['category' => $catid]);
        $ismodule = helper::is_module($course);
        $this->assertEquals($response, $ismodule);
    }

    /**
     * Is_module provider
     *
     * @return array
     */
    public function is_module_provider(): array {
        return [
            'modules' => [
                'category' => 'modules_ABC',
                'response' => true
            ],
            'courses' => [
                'category' => 'courses_ABC',
                'response' => false
            ],
            'empty' => [
                'category' => null,
                'response' => false
            ],
            'random' => [
                'category' => 'RANDOM',
                'response' => false
            ]
        ];
    }
}
