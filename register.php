<?php
require_once 'models/User.php';
session_start();
$title = "Register";
if (isset($_POST['submit'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstname = htmlentities($_POST['firstname']);
        $lastname = htmlentities($_POST['lastname']);
        $email = htmlentities($_POST['email']);
        $password = htmlentities($_POST['password']);
        $accountType = htmlspecialchars($_POST['account_type']);

        $user = new User();
        $userId = $user->addUser($firstname, $lastname, $email, $password);
        $roleId = $user->getRoleByName($accountType);
        if ($userId > 0 && $roleId > 0) {
            $user->assignRoleToUser($userId, $roleId);
            header('Location: account_success.php');
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
    <title>Sign up</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-lg">
            <h1 class="text-2xl font-bold text-center text-gray-700 mb-6">Sign up</h1>

            <form action="" method="post" class="space-y-6">
                <!-- Full Name Fields -->
                <div class="flex space-x-4">
                    <div class="w-1/2">
                        <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="firstname" id="firstname" placeholder="First Name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div class="w-1/2">
                        <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="lastname" id="lastname" placeholder="Last Name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                </div>

                <!-- Account Type Fields -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Account Type</label>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="account_type" id="customer-type" value="Customer" required class="h-4 w-4 text-blue-500 border-gray-300 rounded">
                            <label for="customer-type" class="text-sm text-gray-600">Customer</label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="account_type" id="merchant-type" value="Merchant" class="h-4 w-4 text-blue-500 border-gray-300 rounded">
                            <label for="merchant-type" class="text-sm text-gray-600">Merchant</label>
                        </div>
                    </div>
                </div>

                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <p class="text-sm text-red-500 mt-2" id="emailMsg"></p>
                </div>

                <!-- Password Fields -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="space-y-2">
                    <label for="password-confirm" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="password-confirm" placeholder="Confirm Password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" name="submit" id="submit" class="w-full py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Sign Up
                    </button>
                </div>
            </form>

            <!-- Already have an account link -->
            <p class="mt-4 text-center text-sm text-gray-600">Already have an account? <a href="login.php" class="text-blue-500 hover:text-blue-700">Sign in</a></p>
        </div>
    </div>

    <script>
        const passwordField = document.querySelector("#password");
        const passwordConfirmField = document.querySelector("#password-confirm");
        passwordConfirmField.addEventListener("input", function() {
            if (passwordConfirmField.value === passwordField.value) {
                console.log("Password matched");
            }
        });

        document.querySelector("#email").addEventListener('input', function() {
            const formData = new FormData();
            formData.append('email', this.value);

            fetch('verify_email.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(data => {
                if (data.length > 0) {
                    document.querySelector("#submit").disabled = true;
                    document.querySelector("#emailMsg").textContent = data;
                } else {
                    document.querySelector("#submit").disabled = false;
                    document.querySelector("#emailMsg").textContent = '';
                }
            })
        })
    </script>

</body>

</html>
