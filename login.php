<?php
require_once 'models/User.php';
session_start();

// Check if the form is submitted
if (isset($_POST['submit'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $keepMeLoggedIn = isset($_POST['keepLoggedCheck']);

        try {
            $user = new User();
            $loggedInUser = $user->login($email, $password);
            
            // Store user information in the session
            $_SESSION['user_id'] = $loggedInUser['user_id'];
            $_SESSION['first_name'] = $loggedInUser['first_name'];
            $_SESSION['email'] = $loggedInUser['email'];
            $_SESSION['user_role'] = $user->getUserRoles($loggedInUser['user_id']);
            
            // If "Keep me logged in" is checked, set cookies
            if ($keepMeLoggedIn) {
                setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/"); // Expiration time: 30 days
                setcookie("password", $password, time() + (30 * 24 * 60 * 60), "/");
            }

            // Redirect to the dashboard
            header("Location: ./");
            exit;

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
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container login-container">
        <div class="form-wrapper">
            <form action="" method="post">
                <h1 class="form-header">Sign in</h1>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <div class="input-box">
                    <input class="input-box__field" type="email" placeholder="Email" name="email" id="email" required>
                </div>
                <div class="input-box">
                    <input class="input-box__field" type="password" placeholder="Password" name="password" id="password" required>
                </div>
                <div class="login-form-bottom">
                    <div>
                        <input type="checkbox" name="keepLoggedCheck" id="keepLoggedCheck" value="yes">
                        <label for="keepLoggedCheck">Keep me logged in</label>
                    </div>
                    <a href="">Forgot your password</a>
                </div>
                <button class="btn btn-dark" type="submit" name="submit">Login</button>
            </form>
            <p class="new-account-text">
                New to eStock? <a class="new-account-link" href="register.php">Sign up</a>
            </p>
        </div>
    </div>
</body>
</html>
