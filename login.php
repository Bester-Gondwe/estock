<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/User.php';

$title = 'Login';
$error = '';

if (isset($_SESSION['user_id'])) {
    if (($_SESSION['user_role'] ?? '') === 'Merchant') {
        header('Location: merchant/');
    } else {
        header('Location: ./');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $keepMeLoggedIn = isset($_POST['keepLoggedCheck']);

    $user = new User();
    $loggedInUser = $user->login($email, $password);

    if ($loggedInUser) {
        $roles = $user->getUserRoles($loggedInUser['user_id']);
        $roleName = $roles[0]['role_name'] ?? 'Customer';

        $_SESSION['user_id'] = $loggedInUser['user_id'];
        $_SESSION['first_name'] = $loggedInUser['first_name'];
        $_SESSION['last_name'] = $loggedInUser['last_name'];
        $_SESSION['email'] = $loggedInUser['email'];
        $_SESSION['user_role'] = $roleName;

        if ($keepMeLoggedIn) {
            $token = bin2hex(random_bytes(32));
            setcookie('estock_remember', $token, [
                'expires' => time() + (60 * 60 * 24 * 30),
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            $_SESSION['remember_token'] = $token;
        }

        if ($roleName === 'Merchant') {
            header('Location: merchant/');
        } else {
            header('Location: ./');
        }
        exit;
    }

    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in — eStock</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border border-slate-200">
        <div class="text-center mb-6">
            <a href="./" class="text-2xl font-bold text-emerald-700">eStock</a>
            <p class="text-slate-500 text-sm mt-1">Sign in to your account</p>
        </div>

        <?php if ($error): ?>
            <div class="mb-4 rounded-lg bg-red-50 text-red-700 text-sm px-4 py-3 border border-red-100"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="post" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" id="password"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="keepLoggedCheck" id="keepLoggedCheck" class="h-4 w-4 text-emerald-600 border-slate-300 rounded">
                <label for="keepLoggedCheck" class="ml-2 text-sm text-slate-600">Keep me logged in</label>
            </div>
            <button type="submit" name="submit" class="w-full py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium transition">
                Sign in
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">
            New to eStock? <a href="register.php" class="text-emerald-600 hover:text-emerald-800 font-medium">Create an account</a>
        </p>
    </div>
</body>
</html>
