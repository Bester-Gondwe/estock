<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Account Created</h2>
            </div>
            <div class="text-center">
                <p class="text-lg text-gray-700 mb-4">Your account has been successfully created!</p>
                <p class="text-gray-600">Continue with <a href="login.php" class="text-blue-600 hover:text-blue-800 font-semibold">login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
