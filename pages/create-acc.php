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
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        require_once __DIR__ . '/../config/db.php';

        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email is already registered.';
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            if ($stmt->execute([$email, $hashed_password])) {
                $success = 'Account created successfully. You can now login.';
            } else {
                $error = 'Something went wrong. Please try again later.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Create an Account</title>
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
        <h1 class="text-4xl font-bold mb-2">Sign Up</h1>
        <p class="text-gray-600 mb-8 font-medium">Create a new account below</p>

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

        <form method="POST" action="create-acc.php" novalidate>
            <!-- Email -->
            <div class="relative mb-6 mt-2">
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    placeholder="Email"
                    required
                    class="peer w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-transparent placeholder-transparent"
                />
                <label
                    for="email"
                    class="absolute left-10 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-black pointer-events-none"
                >
                    Email
                </label>
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-black text-xl transition-colors">mail_outline</span>
            </div>

            <!-- Password -->
            <div class="relative mb-6">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    required
                    class="peer w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-transparent placeholder-transparent"
                />
                <label
                    for="password"
                    class="absolute left-10 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-black pointer-events-none"
                >
                    Password
                </label>
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-black text-xl transition-colors">lock_outline</span>
                <button type="button" class="toggle-pw absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black transition-colors" data-target="password">
                    <span class="material-icons text-xl">visibility_off</span>
                </button>
            </div>

            <!-- Confirm Password -->
            <div class="relative mb-8">
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm Password"
                    required
                    class="peer w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-transparent placeholder-transparent"
                />
                <label
                    for="confirm_password"
                    class="absolute left-10 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-black pointer-events-none"
                >
                    Confirm Password
                </label>
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-black text-xl transition-colors">lock_outline</span>
                <button type="button" class="toggle-pw absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black transition-colors" data-target="confirm_password">
                    <span class="material-icons text-xl">visibility_off</span>
                </button>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="w-full bg-black hover:bg-gray-800 text-white font-bold py-3 rounded-lg transition duration-200"
            >
                Create Account
            </button>
            
            <!-- Login Redirect -->
            <p class="text-center text-sm font-medium mt-6">
                <span class="text-gray-500">Already registered?</span>
                <a href="../index.php" class="text-black hover:underline ml-1">Login here</a>
            </p>

        </form>
    </div>

    <!-- Toggle Password Visibility Logic -->
    <script>
        const toggleBtns = document.querySelectorAll('.toggle-pw');
        toggleBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('.material-icons');
                
                if (input.getAttribute('type') === 'password') {
                    input.setAttribute('type', 'text');
                    icon.textContent = 'visibility';
                } else {
                    input.setAttribute('type', 'password');
                    icon.textContent = 'visibility_off';
                }
            });
        });
    </script>
</body>
</html>
