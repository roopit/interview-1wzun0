/**
 * Show a Bootstrap alert at the top of the screen. Calling this function replaces any existing alert in
 * the relevant container
 *
 * @param {string} message
 * @param {string} type - The Bootstrap alert type to display
 */
window.showAlert = (message, type) => {
    // for alert types, see https://getbootstrap.com/docs/5.1/components/alerts/#examples
    const alertContainer = document.getElementById('alert-container');

    if (alertContainer) {
        const wrapper = document.createElement('div')
        wrapper.innerHTML = `
        <div class="alert alert-${type} alert-dismissible show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;

        alertContainer.replaceChildren(wrapper);
    }
};
