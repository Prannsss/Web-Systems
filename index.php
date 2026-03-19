<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    $redirect_url = ($_SESSION['role'] ?? 'user') === 'admin' ? 'pages/admin/dashboard.php' : 'pages/client/dashboard.php';
    header('Location: ' . $redirect_url);
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

        $stmt = $pdo->prepare("SELECT id, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['toast_success'] = 'Login Successful';
            
            $redirect_url = $user['role'] === 'admin' ? 'pages/admin/dashboard.php' : 'pages/client/dashboard.php';
            header('Location: ' . $redirect_url);
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!-- The rest of the file is the HTML form for login. I will update this index to the hero page when necessary -->
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

        <form method="POST" action="index.php" novalidate>
            <!-- Email -->
            <div class="relative mb-6 mt-2">
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    placeholder="Email"
                    required
                    autocomplete="email"
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
                    autocomplete="current-password"
                    class="peer w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition bg-transparent placeholder-transparent"
                />
                <label
                    for="password"
                    class="absolute left-10 -top-2.5 bg-white px-1 text-xs text-gray-500 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3 peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-black pointer-events-none"
                >
                    Password
                </label>
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 peer-focus:text-black text-xl transition-colors">lock_outline</span>
                <button type="button" id="togglePasswordBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black transition-colors">
                    <span id="toggleIcon" class="material-icons text-xl">visibility_off</span>
                </button>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-8">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-black">
                    <input type="checkbox" class="w-4 h-4 accent-black" />
                    Remember Me
                </label>
                <a href="pages/auth/forgot-pw.php" class="text-sm font-medium text-black hover:underline">Forgot Password?</a>
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
                <a href="pages/auth/create-acc.php" class="text-black hover:underline ml-1">Create an account</a>
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
    <!-- Toast Component -->
    <?php include __DIR__ . '/components/toast.php'; ?>
    <script>
        <?php if (!empty($_SESSION['toast_success'])): ?>
            goeyToast.success('<?= addslashes(htmlspecialchars($_SESSION['toast_success'])) ?>');
            <?php unset($_SESSION['toast_success']); ?>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            goeyToast.error('<?= addslashes(htmlspecialchars($error)) ?>');
        <?php endif; ?>
    </script>
</body>
</html>
