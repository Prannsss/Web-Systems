<?php
session_start();

// Guard — must be logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$email = htmlspecialchars($_SESSION['email'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Welcome — Web Systems</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Pixel font for the exact requested design vibe -->
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-white text-black dark:bg-[#111] dark:text-white flex flex-col font-sans transition-colors duration-300">

    <!-- Navbar -->
    <nav class="border-b border-gray-200 dark:border-gray-800 px-6 py-4 flex items-center justify-between">
        <span class="font-bold text-lg tracking-widest text-black dark:text-white">WEB SYSTEMS</span>

        <div class="flex items-center gap-6">
            <!-- Theme Toggle Component -->
            <?php include __DIR__ . '/../components/theme-toggle.php'; ?>
            
            <span class="text-sm text-gray-500 dark:text-gray-400 hidden sm:block"><?= $email ?></span>
            <a href="../config/logout.php"
            class="text-sm border border-black dark:border-white px-5 py-2 hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition duration-200 font-medium text-black dark:text-white">
                Logout
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="flex-1 flex flex-col items-center justify-center px-4 relative overflow-hidden">
        
        <!-- Minimal decorative grid or elements could go here if wanted, kept cleanly black/white -->

        <!-- GEMINI-style pixel art text -->
        <h1 class="text-3xl md:text-5xl lg:text-7xl xl:text-8xl text-center leading-tight tracking-wider drop-shadow-[4px_4px_0_rgba(0,0,0,0.1)] dark:drop-shadow-[4px_4px_0_rgba(255,255,255,0.1)]" 
            style="font-family: 'Press Start 2P', cursive; line-height: 1.5;">
            <div class="text-gray-500 dark:text-[#888] text-sm md:text-xl lg:text-2xl mb-8 tracking-[0.2em] font-sans font-bold" style="font-family: ui-sans-serif, system-ui;">WELCOME TO</div>
            <span class="text-black dark:text-white">WEB</span><br/>
            <span class="text-black dark:text-white relative top-2 md:top-4">SYSTEMS</span>
        </h1>

    </main>

    <!-- Footer -->
    <footer class="text-center text-gray-500 dark:text-gray-600 text-xs py-6 border-t border-gray-200 dark:border-gray-800 uppercase tracking-widest font-medium">
        &copy; <?= date('Y') ?> Web Systems
    </footer>

    <!-- Toast Component -->
    <?php include __DIR__ . '/../components/toast.php'; ?>
    <script>
        <?php if (!empty($_SESSION['toast_success'])): ?>
            goeyToast.success('<?= addslashes(htmlspecialchars($_SESSION['toast_success'])) ?>');
            <?php unset($_SESSION['toast_success']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
