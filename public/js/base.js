
    document.addEventListener('DOMContentLoaded', () => {
        console.log(location.pathname);

        const activeTab = document.querySelector('nav a.active');
        activeTab.removeAttribute('aria-current');
        activeTab.classList.remove('active');

        const locationTab = document.querySelector('nav a[href="' + document.location.pathname + '"]')
        locationTab.classList.add('active');
        locationTab.setAttribute('aria-current', 'page');
    });