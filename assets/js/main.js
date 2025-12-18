/**
 * Project: JA Square Digital Portfolio
 * File: assets/js/main.js
 * Logic: Final Merged JS for Bulma + Tailwind Toggle
 */

document.addEventListener('DOMContentLoaded', function() {
    
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const html = document.documentElement;

    // 1. Theme Initialization
    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const newTheme = html.classList.contains('dark') ? 'light' : 'dark';
            applyTheme(newTheme);
            localStorage.setItem('theme', newTheme);
        });
    }

    function applyTheme(theme) {
        if (theme === 'dark') {
            html.classList.replace('light', 'dark');
            if(themeIcon) themeIcon.className = 'fa-solid fa-sun is-size-5';
        } else {
            html.classList.add('light');
            html.classList.remove('dark');
            if(themeIcon) themeIcon.className = 'fa-solid fa-moon is-size-5';
        }
    }

    // 2. Portfolio Filtering (Bulma implementation)
    const filterLinks = document.querySelectorAll('.filter-trigger');
    const portfolioItems = document.querySelectorAll('.portfolio-item');

    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            filterLinks.forEach(l => l.classList.remove('is-active'));
            this.classList.add('is-active');

            const filterValue = this.getAttribute('data-filter');
            portfolioItems.forEach(item => {
                if (filterValue === 'all' || item.classList.contains(filterValue)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});