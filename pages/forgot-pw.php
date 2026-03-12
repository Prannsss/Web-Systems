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
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($email) || empty($new_password) || empty($confirm_password)) {
        $error = 'Please fill out all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        require_once __DIR__ . '/../config/db.php';

        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            if ($update_stmt->execute([$hashed_password, $email])) {
                $success = 'Password has been updated successfully. You can now login.';
            } else {
                $error = 'Failed to update password. Please try again later.';
            }
        } else {
            $error = 'No account found with that email address.';
        }
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
        <p class="text-gray-600 mb-8 font-medium">Enter your email and new password below</p>

        <form method="POST" action="forgot-pw.php" novalidate>
            <!-- Email -->
            <div class="relative mb-6 mt-2">
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

            <!-- New Password -->
            <div class="relative mb-6">
                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    placeholder="New Password"
                    required
                    class="peer w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-transparent placeholder-transparent"
                />
                <label
                    for="new_password"
                    class="absolute left-10 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-black pointer-events-none"
                >
                    New Password
                </label>
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-black text-xl transition-colors">lock_outline</span>
                <button type="button" class="toggle-pw absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black transition-colors" data-target="new_password">
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
                Reset Password
            </button>
            
            <!-- Login Redirect -->
            <p class="text-center text-sm font-medium mt-6">
                <span class="text-gray-500">Remembered your password?</span>
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
    <!-- Toast Component -->
    <?php include __DIR__ . '/../components/toast.php'; ?>
    <script>
        <?php if (!empty($error)): ?>
            goeyToast.error('<?= addslashes(htmlspecialchars($error)) ?>');
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            goeyToast.success('<?= addslashes(htmlspecialchars($success)) ?>');
        <?php endif; ?>
    </script>
</body>
</html>
