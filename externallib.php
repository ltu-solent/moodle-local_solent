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
 * External functions
 *
 * @package   local_solent
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2023 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/externallib.php');

/**
 * External functions
 */
class local_solent_external extends external_api {
    /**
     * Get import restriction parameters
     *
     * @return external_function_parameters
     */
    public static function get_import_restrictions_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    /**
     * Get list of activities than cannot be imported because they're part of our template.
     *
     * @return array List of items
     */
    public static function get_import_restrictions() {
        $config = get_config('local_solent');
        $restrictactivities = explode("\n", $config->restrictbackupactivities);
        $data = [];
        foreach ($restrictactivities as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            $selectors = explode('|', $line);
            // Either part can be null, but not both.
            $item = [
                'activity' => null,
                'title' => null
            ];
            foreach ($selectors as $selector) {
                $keyvalues = explode('=', $selector);
                if (count($keyvalues) != 2) {
                    continue;
                }
                // Need to escape brackets as this value will be processed in JS as a Regular Expression.
                $item[$keyvalues[0]] = str_replace(['[', ']', '(', ')'], ['\[', '\]', '\(', '\)'], $keyvalues[1]);
            }
            if ($item['activity'] == null && $item['title'] == null) {
                continue;
            }
            $data[] = $item;
        }
        return $data;
    }

    /**
     * Return import restrictions
     *
     * @return external_multiple_structure
     */
    public static function get_import_restrictions_returns(): external_multiple_structure {
        return new external_multiple_structure(
            new external_single_structure([
                'activity' => new external_value(PARAM_ALPHAEXT, 'Component name of activity', VALUE_REQUIRED, null),
                'title' => new external_value(PARAM_TEXT, 'Text to search for', VALUE_REQUIRED, null)
            ])
        );
    }
}