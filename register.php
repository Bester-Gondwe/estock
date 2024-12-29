<?php
require_once 'models/User.php';
session_start();
if (isset($_POST['submit'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstname =  $_POST['firstname'];
        $lastname =  $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        try {
            $user = new User();
            $loggedInUser = $user->login($email, $password);
            $userId = $user->addUser($firstname, $lastname, $email, $password);
            if ($userId > 0) {
                $user->assignRoleToUser($userId, 1);
                // Store user information in the session
                $_SESSION['user_id'] = $userId;
                $_SESSION['first_name'] = $loggedInUser['first_name'];
                $_SESSION['email'] = $loggedInUser['email'];
                $_SESSION['user_role'] = $user->getUserRoles($loggedInUser['user_id']);
            }
        } catch (Exception $e) {
            // Handle login error
            $error = $e->getMessage();
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
</head>

<body>
    <div class="container login-container">
        <div class="form-wrapper">
            <form action="" method="post">
                <h1 class="form-header">Sign up</h1>
                <div class="fullname-field">
                    <div class="input-box">
                        <input class="input-box__field" placeholder="First Name" type="text" name="firstname"
                            id="firstname">
                    </div>
                    <div class="input-box">
                        <input class="input-box__field" placeholder="Last Name" type="text" name="lastname"
                            id="lastname">
                    </div>
                </div>
                <div class="account-type-field input-box">
                    <p class="account-type-field-label">Account Type</p>
                    <div class="account-types">
                        <div class="account-type-wrapper">
                            <input type="radio" name="account_type" id="customer-type">
                            <label for="customer-type">Customer</label>
                        </div>
                        <div class="account-type-wrapper">
                            <input type="radio" name="account_type" id="merchant-type">
                            <label for="merchant-type">Merchant</label>
                        </div>
                    </div>

                </div>
                <div class="input-box">
                    <input class="input-box__field" placeholder="Email" type="email" name="email" id="email">
                </div>

                <div class="input-box">
                    <input class="input-box__field" placeholder="Password" type="password" name="password"
                        id="password">
                </div>

                <div class="input-box">
                    <input class="input-box__field" placeholder="Confim Password" type="password"
                        id="password-confirm">
                </div>

                <div class="btn-wrapper">
                    <button class="btn btn-dark" type="submit" name="submit">
                        Sign Up
                    </button>
                </div>
            </form>
            <p class="new-account-text">
                Already have account? <a class="new-account-link" href="login.php">Sign in</a>
            </p>
        </div>
    </div>

    <script>
        const passwordField = document.querySelector("#password");
        const passwordConfirmField = document.querySelector("#password-confirm");
        passwordConfirmField.addEventListener("input", function() {
            if (passwordConfirmField.value === passwordField.value) {
                console.log("password matched")
            }
        })
    </script>
</body>

</html>