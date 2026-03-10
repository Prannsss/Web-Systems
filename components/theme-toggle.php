<button id="themeToggleBtn" onclick="toggleTheme()" type="button" 
        class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition flex items-center justify-center text-black dark:text-white"
        title="Toggle Theme">
    <span id="theme-icon" class="material-icons text-xl">dark_mode</span>
</button>

<script>
    // Theme setup and toggle logic
    function initTheme() {
        // Default is light mode, but check if user specifically set dark mode previously
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
            document.getElementById('theme-icon').textContent = 'light_mode';
        } else {
            document.documentElement.classList.remove('dark');
            document.getElementById('theme-icon').textContent = 'dark_mode';
        }
    }

    function toggleTheme() {
        const isDark = document.documentElement.classList.contains('dark');
        
        if (isDark) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            document.getElementById('theme-icon').textContent = 'dark_mode';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            document.getElementById('theme-icon').textContent = 'light_mode';
        }
    }

    // Run on script load
    initTheme();
</script>