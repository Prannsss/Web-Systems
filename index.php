<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: hero.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        require_once __DIR__ . '/config/db.php';

        $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email']   = $user['email'];
            header('Location: hero.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login</title>
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
        <h1 class="text-4xl font-bold mb-2">Login</h1>
        <p class="text-gray-600 mb-8 font-medium">Hi, Welcome back!</p>

        <?php if ($error): ?>
        <div class="mb-5 flex items-center gap-2 border border-black text-black px-4 py-3 text-sm font-medium">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="index.php" novalidate>
            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold mb-2">
                    Email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    placeholder="E.g. johndoe@email.com"
                    required
                    autocomplete="email"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition placeholder-gray-400"
                />
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-bold mb-2">
                    Password
                </label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition placeholder-gray-400 pr-12"
                    />
                    <button type="button" id="togglePasswordBtn" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-black transition">
                        <span id="toggleIcon" class="material-icons text-xl">visibility_off</span>
                    </button>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-8">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-black">
                    <input type="checkbox" class="w-4 h-4 accent-black" />
                    Remember Me
                </label>
                <a href="#" class="text-sm font-medium text-black hover:underline">Forgot Password?</a>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="w-full bg-black hover:bg-gray-800 text-white font-bold py-3 rounded-lg transition duration-200"
            >
                Login
            </button>
            
            <!-- Register Redirect -->
            <p class="text-center text-sm font-medium mt-6">
                <span class="text-gray-500">Not registered yet?</span>
                <a href="#" class="text-black hover:underline ml-1">Create an account</a>
            </p>

        </form>
    </div>

    <!-- Toggle Password Visibility Logic -->
    <script>
        const pwInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const toggleIcon = document.getElementById('toggleIcon');
        
        toggleBtn.addEventListener('click', () => {
            const isPassword = pwInput.getAttribute('type') === 'password';
            pwInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggleIcon.textContent = isPassword ? 'visibility' : 'visibility_off';
        });
    </script>
</body>
</html>
