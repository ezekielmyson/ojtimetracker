<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Include Files
    include 'session.inc.php';
    include 'general.inc.php';
    include 'dbhandler.inc.php';

    // Get all the data submitted
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

    // Declare variables for error handling
    $errors = '';
    $notify = '';

    if (isInputEmpty($email, $password, $repassword)) {
        $errors = "Please fill in the blank fields!";
        $_SESSION['errors'] = $errors;
        $errors = '';
        header("Location: ../register.php");
        exit;
    }

    if (isNotValidEmail($email)) {
        $errors = "Please use a valid email!";
        $_SESSION['errors'] = $errors;
        $errors = '';
        header("Location: ../register.php");
        exit;
    }

    if (isPasswordNotMatch($password, $repassword)) {
        $errors = "Passwords does not match!";
        $_SESSION['errors'] = $errors;
        $errors = '';
        header("Location: ../register.php");
        exit;
    }

    try {

        $checkEmail = $connection->prepare("SELECT email FROM users_login WHERE email = :email");
        $checkEmail->bindParam(':email', $email);
        $checkEmail->execute();

        if ($checkEmail->rowCount() > 0) {
            $checkEmail = '';
            $errors = "Email already exist!";
            $_SESSION['errors'] = $errors;
            $errors = '';
            header("Location: ../register.php");
            exit;
        }

        $queryStatement = $connection->prepare("INSERT INTO users_login(email, userpassword) VALUES (:email, :userpassword);");

        $options = ['cost' => 12];

        $hashPassword = password_hash($password, PASSWORD_BCRYPT, $options);

        $queryStatement->bindParam(':email', $email);
        $queryStatement->bindParam(':userpassword', $hashPassword);
        $queryStatement->execute();

        if ($queryStatement->rowCount() > 0) {
            $queryStatement = '';
            $notify = "User successfully created!";
            $_SESSION['notify'] = $notify;
            $notify = '';
            header("Location: ../register.php");
            exit;
        }
    } catch (PDOException $e) {
        die("SQL query failed : " . $e->getMessage());
    }
} else {
    // illegally accessing this page
    header("Location: ../register.php");
}
