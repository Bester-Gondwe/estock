<?php
require_once __DIR__ . '/config/bootstrap.php';
require_once __DIR__ . '/models/User.php';

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $accountType = $_POST['account_type'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $allowedTypes = ['Customer', 'Merchant'];

    if ($firstname === '' || $lastname === '' || $email === '' || $password === '') {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $passwordConfirm) {
        $error = 'Passwords do not match.';
    } elseif (!in_array($accountType, $allowedTypes, true)) {
        $error = 'Please select a valid account type.';
    } else {
        $user = new User();
        if ($user->emailExists($email)) {
            $error = 'This email is already registered.';
        } else {
            $userId = $user->addUser($firstname, $lastname, $email, $password, $phone, $address);
            $roleId = $user->getRoleByName($accountType);
            if ($userId > 0 && $roleId > 0) {
                $user->assignRoleToUser($userId, $roleId);
                header('Location: account_success.php');
                exit;
            }
            $error = 'Registration failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up — eStock</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg border border-slate-200">
        <div class="text-center mb-6">
            <a href="./" class="text-2xl font-bold text-emerald-700">eStock</a>
            <p class="text-slate-500 text-sm mt-1">Create your account</p>
        </div>

        <?php if ($error): ?>
            <div class="mb-4 rounded-lg bg-red-50 text-red-700 text-sm px-4 py-3 border border-red-100"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="post" class="space-y-5" id="registerForm">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="firstname" class="block text-sm font-medium text-slate-700 mb-1">First name</label>
                    <input type="text" name="firstname" id="firstname" value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
                <div>
                    <label for="lastname" class="block text-sm font-medium text-slate-700 mb-1">Last name</label>
                    <input type="text" name="lastname" id="lastname" value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                </div>
            </div>

            <div>
                <span class="block text-sm font-medium text-slate-700 mb-2">Account type</span>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="radio" name="account_type" value="Customer" <?= ($_POST['account_type'] ?? 'Customer') === 'Customer' ? 'checked' : '' ?> required class="text-emerald-600">
                        Customer
                    </label>
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="radio" name="account_type" value="Merchant" <?= ($_POST['account_type'] ?? '') === 'Merchant' ? 'checked' : '' ?> class="text-emerald-600">
                        Merchant
                    </label>
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                <p class="text-sm text-red-500 mt-1" id="emailMsg"></p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Phone (optional)</label>
                    <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Address (optional)</label>
                    <input type="text" name="address" id="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" id="password" minlength="8"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
            </div>
            <div>
                <label for="password_confirm" class="block text-sm font-medium text-slate-700 mb-1">Confirm password</label>
                <input type="password" name="password_confirm" id="password_confirm" minlength="8"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
                <p class="text-sm text-red-500 mt-1" id="passwordMsg"></p>
            </div>

            <button type="submit" name="submit" id="submit" class="w-full py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium transition">
                Create account
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-600">
            Already have an account? <a href="login.php" class="text-emerald-600 hover:text-emerald-800 font-medium">Sign in</a>
        </p>
    </div>

    <script>
        const emailInput = document.querySelector('#email');
        const submitBtn = document.querySelector('#submit');
        const emailMsg = document.querySelector('#emailMsg');
        const passwordField = document.querySelector('#password');
        const passwordConfirmField = document.querySelector('#password_confirm');
        const passwordMsg = document.querySelector('#passwordMsg');

        passwordConfirmField.addEventListener('input', () => {
            if (passwordConfirmField.value && passwordConfirmField.value !== passwordField.value) {
                passwordMsg.textContent = 'Passwords do not match';
            } else {
                passwordMsg.textContent = '';
            }
        });

        let emailTimer;
        emailInput.addEventListener('input', function () {
            clearTimeout(emailTimer);
            emailTimer = setTimeout(() => {
                if (!this.value.includes('@')) return;
                const formData = new FormData();
                formData.append('email', this.value);
                fetch('verify_email.php', { method: 'POST', body: formData })
                    .then(r => r.json())
                    .then(data => {
                        if (data.error) {
                            submitBtn.disabled = true;
                            emailMsg.textContent = data.error;
                        } else {
                            submitBtn.disabled = false;
                            emailMsg.textContent = '';
                        }
                    })
                    .catch(() => {});
            }, 400);
        });
    </script>
</body>
</html>
