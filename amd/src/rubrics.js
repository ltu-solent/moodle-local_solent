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

import Templates from 'core/templates';

/**
 * To save space, takes the rubric level descriptions and renders them as help icons in the rubric grading form element.
 *
 * @module     local_solent/rubrics
 * @copyright  2026 Southampton Solent University {@link https://www.solent.ac.uk}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
export const init = () => {
    const rubrics = document.querySelectorAll('.gradingform_rubric');
    if (!rubrics || rubrics.length === 0) {
        return;
    }
    rubrics.forEach((rubric) => {
         renderRubric(rubric);
    });
};

const renderRubric = (rubric) => {
    const criteria = rubric.querySelectorAll('.criterion');
    if (!criteria) {
        return;
    }

    criteria.forEach((criterion) => {
        const levels = criterion.querySelectorAll('.level');
        if (!levels) {
            return;
        }
        const criterionId = criterion.getAttribute('id');
        const criterionRow = criterion.querySelector('tr#' + criterionId + '-levels');
        const levelsRow = document.createElement('tr');
        levelsRow.classList.add('levels-row');
        let promises = [];
        /**
         * Render the level icons for the criterion levels.
         *
         * @returns {Promise<void>} A promise that resolves when all level icons have been rendered and appended to the levels row.
         */
        async function renderLevelIcons() {
            for (const level of levels) {
                const levelCell = document.createElement('td');
                levelCell.classList.add('level-desc');
                const description = level.querySelector('.definition');
                try {
                    if (description) {
                        const infoIcon = await Templates.render('core/help_icon', {
                            text: description.textContent.trim(),
                        });
                        levelCell.innerHTML = infoIcon;
                        description.style.display = 'none';
                    }
                } catch (error) {
                    window.console.error('Error rendering help icon for level description: ' + error);
                }
                levelsRow.appendChild(levelCell);
            }
            criterionRow.parentNode.insertBefore(levelsRow, criterionRow);
        }
        promises.push(renderLevelIcons());
        Promise.all(promises).then(() => {
            return true;
        }).catch((error) => {
            window.console.error('Error rendering level icons: ' + error);
        });
    });
};