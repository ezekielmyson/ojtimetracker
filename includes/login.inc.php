<?php

// Include Files
include 'session.inc.php';
include 'general.inc.php';
include 'dbhandler.inc.php';

if (isUserLoggedin()) {
    header("Location: ../dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Get all the data submitted
    $email = $_POST['email'];
    $password = $_POST['password'];


    // validate user input

    if (empty($email) || empty($password)) {
        $errors = "Please fill in the blank fields!";
        $_SESSION['errors'] = $errors;
        $errors = '';
        header("Location: ../index.php");
        exit;
    }

    if (isNotValidEmail($email)) {
        $errors = "Please use a valid email!";
        $_SESSION['errors'] = $errors;
        $errors = '';
        header("Location: ../index.php");
        exit;
    }

    $sqlstatement = $connection->prepare("SELECT * FROM users_login WHERE email = :email");
    $sqlstatement->bindParam(':email', $email);
    $sqlstatement->execute();

    $user = $sqlstatement->fetch();

    // check for password to match email
    if ($sqlstatement->rowCount() > 0 && password_verify($password, $user['userpassword'])) {
        $_SESSION['userid'] = $user['users_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['loggedin'] = true;
        $sqlstatement = '';

        // redirect to the dashboard
        header("Location: ../dashboard.php");

        exit;
    } else {
        $errors = "Inccorect Login!";
        $_SESSION['errors'] = $errors;
        $errors = '';
        header("Location: ../index.php");
        exit;
    }
} else {
    // illegally accessing this page
    header("Location: ../register.php");
    exit;
}
