<?php
require_once 'models/User.php';
session_start();
$title = 'Login';
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] == "Merchant") {
        header("Location: merchant/");
    } else {
        header("Location: ./");
    }
    exit;
}
if (isset($_POST['submit'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $email = htmlspecialchars($_POST['email']);
        $password = htmlentities($_POST['password']);

        $keepMeLoggedIn = isset($_POST['keepLoggedCheck']);

        $user = new User();

        $loggedInUser = $user->login($email, $password);
        if ($loggedInUser) {
            // Store user information in the session
            $_SESSION['user_id'] = $loggedInUser['user_id'];
            $_SESSION['first_name'] = $loggedInUser['first_name'];
            $_SESSION['last_name'] = $loggedInUser['last_name'];
            $_SESSION['email'] = $loggedInUser['email'];
            $_SESSION['user_role'] = $user->getUserRoles($loggedInUser['user_id'])['role_name'];

            // Redirect to dashboard or another page
            if ($_SESSION['user_role'] == "Merchant") {
                header("Location: merchant/");
            } else {
                header("Location: ./");
            }
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
            <h1 class="text-2xl font-bold text-center text-gray-700 mb-6">Sign in</h1>

            <form action="" method="post" class="space-y-6">
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="keepLoggedCheck" id="keepLoggedCheck" class="h-4 w-4 text-blue-500 border-gray-300 rounded">
                        <label for="keepLoggedCheck" class="text-sm text-gray-600">Keep me logged in</label>
                    </div>
                    <a href="#" class="text-sm text-blue-500 hover:text-blue-700">Forgot your password?</a>
                </div>

                <button type="submit" name="submit" class="w-full py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Login</button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">New to eStock? <a href="register.php" class="text-blue-500 hover:text-blue-700">Sign up</a></p>
        </div>
    </div>

</body>

</html>
