import './bootstrap';

// Theme toggle function (available globally for Alpine.js)
window.toggleTheme = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    return isDark;
};
