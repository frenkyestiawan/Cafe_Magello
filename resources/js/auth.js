document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.auth-form');

    if (!form) {
        return;
    }

    form.setAttribute('data-ready', 'true');
});
