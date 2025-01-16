<?php
require_once 'models/User.php';
session_start();
$title = "Register";
if (isset($_POST['submit'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstname =  htmlentities($_POST['firstname']);
        $lastname =  htmlentities($_POST['lastname']);
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
</head>

<body>
    <div class="wrapper login-container">
        <div class="form-wrapper">
            <form action="" method="post">
                <h1 class="form-header">Sign up</h1>
                <div class="fullname-field">
                    <div class="input-box">
                        <input class="input-box__field" placeholder="First Name" type="text" name="firstname"
                            id="firstname" required>
                    </div>
                    <div class="input-box">
                        <input class="input-box__field" placeholder="Last Name" type="text" name="lastname"
                            id="lastname" required>
                    </div>
                </div>
                <div class="account-type-field input-box">
                    <p class="account-type-field-label">Account Type</p>
                    <div class="account-types">
                        <div class="account-type-wrapper">
                            <input type="radio" name="account_type" id="customer-type" value="Customer" required>
                            <label for="customer-type">Customer</label>
                        </div>
                        <div class="account-type-wrapper">
                            <input type="radio" name="account_type" id="merchant-type" value="Merchant">
                            <label for="merchant-type">Merchant</label>
                        </div>
                    </div>

                </div>
                <div class="input-box">
                    <input class="input-box__field" placeholder="Email" type="email" name="email" id="email" required>
                    <p class="input-error-msg" id="emailMsg"></p>
                </div>

                <div class="input-box">
                    <input class="input-box__field" placeholder="Password" type="password" name="password"
                        id="password" required>
                </div>

                <div class="input-box">
                    <input class="input-box__field" placeholder="Confim Password" type="password"
                        id="password-confirm" required>
                </div>

                <div class="btn-wrapper">
                    <button class="btn btn-dark btn-100" type="submit" name="submit" id="submit">
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