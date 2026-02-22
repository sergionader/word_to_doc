import './bootstrap';

// Theme toggle function (available globally for Alpine.js)
window.toggleTheme = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    return isDark;
};

// Re-apply theme after Livewire SPA navigation (inline FOUC script doesn't re-run)
function applyTheme() {
    const theme = localStorage.getItem('theme') || 'dark';
    document.documentElement.classList.toggle('dark', theme === 'dark');
}

document.addEventListener('livewire:navigated', applyTheme);
