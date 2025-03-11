<?php

include 'includes/session.inc.php';
include 'includes/general.inc.php';

if (isUserLoggedin()) {
    header("Location: dashboard.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/register.css">
    <title>OMH Cebu IT Timetracker | OJT</title>
</head>

<body>

    <header class="index-header-container">
        <h1 class="text-shadow">OMH Cebu IT Timetracker | OJT</h1>
    </header>

    <section>
        <div class="index-login-wrapper">
            <div class="index-login-form">
                <p class="index-login-title">Registration Information</p>
                <div class="index-login-title-border-bottom"></div>
                <form action="includes/register.inc.php" method="post">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email" required>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <label for="repassword">Re-type password</label>
                    <input type="password" name="repassword" id="repassword" placeholder="Re-type Password" required>
                    <button name="login">Register</button>
                    <a href="index.php">Already have account?</a>
                </form>
                <p class="errors">
                    <?php
                        if (isset($_SESSION['errors']) && $_SESSION['errors'] != '') {
                            echo $_SESSION['errors'];
                            $_SESSION['errors'] = '';
                        }
                    ?>
                </p>
                <p class="notif">
                    <?php
                        if (isset($_SESSION['notify']) && $_SESSION['notify'] != '') {
                            echo $_SESSION['notify'];
                            $_SESSION['notify'] = '';
                        }
                    ?>
                </p>
            </div>
        </div>
    </section>

    <footer>
        <p>All Rights Reserved.&#169; 2025 aldinm@omegahms.com</p>
    </footer>

</body>

</html>