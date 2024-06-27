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
 * Competency picker.
 *
 * To handle 'save' events use: picker.on('save')
 * This will receive a object with either a single 'competencyId', or an array in 'competencyIds'
 * depending on the value of multiSelect.
 *
 * @module     local_solent/backup
 * @copyright  2024 Solent University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import Ajax from 'core/ajax';
import Pending from 'core/pending';
import Notification from 'core/notification';

/**
 * Get import restrictions to exclude templated items.
 * @return {object}
 */
export const getImportRestrictions = () => {
    let args = [];
    const request = {
        methodname: 'local_solent_get_import_restrictions',
        args: args
    };
    const pendingPromise = new Pending('local/solent:getImportRestrictions');
    Ajax.call([request])[0]
        .then(restrictactivitybackup)
        .catch(Notification.exception);
    return pendingPromise.resolve();
};

/**
 * Restrict specified items
 * @param {array} data The data returned by getImportRestrictions.
 */
const restrictactivitybackup = (data) => {
    const biform = document.querySelector('#page-backup-import div[role="main"] form');
    if (!biform.stage) {
        return;
    }
    const stage = biform.stage.value;
    const jumpto = document.querySelector('[name="oneclickbackup"]');
    const legacy = document.querySelector('[name="setting_root_legacyfiles"]');
    // Remove "Jump to final step" button.
    if (jumpto) {
        jumpto.style.display = 'none';
        jumpto.setAttribute('disabled', true);
    }
    // Make sure Legacy files are not imported.
    if (legacy) {
        legacy.removeAttribute("checked");
        legacy.setAttribute("disabled", true);
        legacy.value = 0;
    }
    if (!data) {
        return;
    }
    if (stage != 2) {
        return;
    }

    let checkboxes = biform.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        let name = checkbox.name;
        let label = checkbox.parentNode.textContent
            .replaceAll('\u00a0', ' ') // Non-breaking space.
            .replaceAll('\n', ' ');
        data.forEach((item) => {
            if (item.activity && item.title) {
                if ((name.search(new RegExp('activity_' + item.activity + '[0-9a-z_]+included')) != -1)
                    && (label.search(item.title) != -1)) {
                    checkbox.setAttribute("checked", false);
                    checkbox.setAttribute("disabled", true);
                }
                return;
            }
            if (item.activity) {
                if (name.search(new RegExp('activity_' + item.activity + '[0-9a-z_]+included')) != -1) {
                    checkbox.setAttribute("checked", false);
                    checkbox.setAttribute("disabled", true);
                }
                return;
            }
            if (item.title) {
                if (label.search(item.title) != -1) {
                    checkbox.setAttribute("checked", false);
                    checkbox.setAttribute("disabled", true);
                    // Let's also disable the user's info bit.
                    // data.splice(key, 1); // Reduce the number of items to loop through as this one is done.
                }
                return;
            }
        });
    });
};
