import Notification from 'core/notification';

/**
 * Delete course content if the current user has no role.
 */
export const deleteContent = () => {
    const main = document.querySelector('body.courserole-none div[role="main"]');
    if (main) {
        main.querySelector('.onetopic-tab-body').innerHTML = '';
        Notification.addNotification({
            message: M.util.get_string('noroleerror', 'local_solent'),
            closebutton: false,
            type: 'error'
        });
    }
};