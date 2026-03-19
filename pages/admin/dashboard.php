<?php
session_start();

// Guard — must be logged in & must be admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../config/db.php';

$email = htmlspecialchars($_SESSION['email'] ?? '');

// Get the count of users with role='user'
$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
$userCount = $stmt->fetchColumn();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Dashboard — Web Systems</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Pixel font -->
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-50 text-black dark:bg-[#111] dark:text-white flex flex-col font-sans transition-colors duration-300">

    <!-- Navbar -->
    <nav class="border-b border-gray-200 dark:border-gray-800 px-6 py-4 flex items-center justify-between bg-white dark:bg-black w-full">
        <span class="font-bold text-lg tracking-widest text-black dark:text-white">ADMIN PORTAL</span>

        <div class="flex items-center gap-6">
            <!-- Theme Toggle Component -->
            <?php include __DIR__ . '/../../components/theme-toggle.php'; ?>
            
            <span class="text-sm text-gray-500 dark:text-gray-400 hidden sm:block"><?= $email ?></span>
            <a href="../../config/logout.php"
            class="text-sm border border-black dark:border-white px-5 py-2 hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition duration-200 font-medium text-black dark:text-white">
                Logout
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-center p-6 relative overflow-hidden">
        
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-5xl font-bold tracking-wider drop-shadow-sm mb-4" style="font-family: 'Press Start 2P', cursive;">
                DASHBOARD
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm md:text-base font-medium tracking-widest uppercase">
                System Overview
            </p>
        </div>

        <!-- Centered Metric Card -->
        <div class="bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xl w-full max-w-sm overflow-hidden transition-all hover:scale-[1.02] duration-300">
            <div class="p-8 flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center mb-6">
                    <span class="material-icons text-blue-600 dark:text-blue-400" style="font-size: 32px;">group</span>
                </div>
                
                <h2 class="text-sm text-gray-500 dark:text-gray-400 font-bold tracking-widest uppercase mb-2">Total Users</h2>
                <div class="text-6xl font-black text-black dark:text-white tracking-tighter" style="font-family: 'Press Start 2P', cursive;">
                    <?= (int)$userCount ?>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-[#151515] border-t border-gray-100 dark:border-gray-800 px-6 py-4">
                <p class="text-xs text-center text-gray-500 dark:text-gray-400 flex items-center justify-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    Live Data
                </p>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="text-center bg-white dark:bg-black text-gray-500 dark:text-gray-600 text-xs py-6 border-t border-gray-200 dark:border-gray-800 uppercase tracking-widest font-medium w-full">
        &copy; <?= date('Y') ?> Web Systems Admin
    </footer>

    <!-- Toast Component -->
    <?php include __DIR__ . '/../../components/toast.php'; ?>
    <script>
        <?php if (!empty($_SESSION['toast_success'])): ?>
            goeyToast.success('<?= addslashes(htmlspecialchars($_SESSION['toast_success'])) ?>');
            <?php unset($_SESSION['toast_success']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
