
    document.addEventListener('DOMContentLoaded', () => {

        const pathName = window.location.pathname;

        const activeTab = document.querySelector('nav a.active');
        activeTab.removeAttribute('aria-current');
        activeTab.classList.remove('active');

        const locationTab = document.querySelector('nav a[href="' + pathName + '"], nav a[href="' + pathName.substring(0, pathName.length - 2)  + '"]')
        locationTab.classList.add('active');
        locationTab.setAttribute('aria-current', 'page');

    });