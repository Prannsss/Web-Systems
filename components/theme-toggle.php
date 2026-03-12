<style>
    @keyframes spin-once {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .animate-spin-once {
        animation: spin-once 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

<button id="themeToggleBtn" onclick="toggleTheme()" type="button" 
        class="group p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition flex items-center justify-center text-black dark:text-white"
        title="Toggle Theme">
    <span id="theme-icon" class="material-icons text-xl transition-transform duration-700 group-hover:rotate-[360deg]">dark_mode</span>
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
        const icon = document.getElementById('theme-icon');
        
        // Trigger spin animation on click
        icon.classList.remove('animate-spin-once');
        void icon.offsetWidth; // Trigger reflow to restart the animation
        icon.classList.add('animate-spin-once');
        
        if (isDark) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            icon.textContent = 'dark_mode';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            icon.textContent = 'light_mode';
        }
    }

    // Run on script load
    initTheme();
</script>