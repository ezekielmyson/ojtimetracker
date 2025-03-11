<?php

$dbuser = "aldin";
$dbpass = "qweasd123!@#";
$dbhost = "127.0.0.1";
$dbname = "timetracker";

// Connect to the database

try {
    $connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Failed database connection : " . $e->getMessage());
}