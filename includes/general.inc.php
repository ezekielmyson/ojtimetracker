<?php

function isInputEmpty($email, $password, $password2)
{
    if (empty($email) || empty($password || empty($password2))) {
        return true;
    } else {
        return false;
    }
}

function isNotValidEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function isPasswordNotMatch($password, $password2)
{
    if ($password !== $password2) {
        return true;
    } else {
        return false;
    }
}

function isUserLoggedin() {

    if (isset($_SESSION['userid']) && isset($_SESSION['loggedin']) && isset($_SESSION['email'])) {
        return true;
    } else {
        return false;
    }

}