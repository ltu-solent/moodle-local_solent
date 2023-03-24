import Ajax from 'core/ajax';

/**
 * Get import restrictions to exclude templated items.
 */
export const getImportRestrictions = () => {
    let args = [];
    const request = {
        methodname: 'local_solent_get_import_restrictions',
        args: args
    };

    Ajax.call([request])[0].done(restrictactivitybackup);
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
