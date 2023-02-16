import Log from 'core/log';

export const restrictactivitybackup = (data) => {
    Log.debug("hello");
    const biform = document.querySelector('#page-backup-backup div[role="main"] form, #page-backup-import div[role="main"] form');
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
    if (stage != 2) {
        return;
    }
    if (!data) {
        return;
    }

    let checkboxes = biform.querySelectorAll('input[type="checkbox"]');
    Log.debug(data.length);
    checkboxes.forEach(checkbox => {
        let name = checkbox.name;
        let label = checkbox.parentNode.innerText;
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
    Log.debug(data.length);
};
