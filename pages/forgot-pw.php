<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: pages/dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = 'Please enter your email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        require_once __DIR__ . '/../config/db.php';

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        
        // Always show the same success message to prevent user enumeration
        $success = 'If an account with that email exists, a password reset link has been sent.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Forgot Password</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="min-h-screen bg-white text-black flex items-center justify-center px-4">

    <div class="w-full max-w-sm">
        <h1 class="text-4xl font-bold mb-2">Forgot Password</h1>
        <p class="text-gray-600 mb-8 font-medium">Enter your email to receive a reset link</p>

        <?php if ($error): ?>
        <div class="mb-5 flex items-center gap-2 border border-black text-black px-4 py-3 text-sm font-medium">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="mb-5 flex items-center gap-2 border border-green-600 text-green-700 px-4 py-3 text-sm font-medium bg-green-50">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="forgot-pw.php" novalidate>
            <!-- Email -->
            <div class="relative mb-8 mt-2">
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    placeholder="Email Address"
                    required
                    class="peer w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-transparent placeholder-transparent"
                />
                <label
                    for="email"
                    class="absolute left-10 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-black pointer-events-none"
                >
                    Email Address
                </label>
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-black text-xl transition-colors">mail_outline</span>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="w-full bg-black hover:bg-gray-800 text-white font-bold py-3 rounded-lg transition duration-200"
            >
                Send Reset Link
            </button>
            
            <!-- Login Redirect -->
            <p class="text-center text-sm font-medium mt-6">
                <span class="text-gray-500">Remembered your password?</span>
                <a href="../index.php" class="text-black hover:underline ml-1">Login here</a>
            </p>

        </form>
    </div>
</body>
</html>
