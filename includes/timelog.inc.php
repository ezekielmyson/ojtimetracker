<?php

// Set default timezone to local
date_default_timezone_set("Asia/Manila");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $todate = date("Y-m-d");
    $timestamp = time();
    $timein = date("H:i:s", $timestamp);

    include 'dbhandler.inc.php';

    if (isset($_POST['clockin'])) {

        $sqlclockin = $connection->prepare("INSERT INTO timelog (date, date_time_in, users_id) VALUES (:today, :timein, :usersid);");
        $sqlclockin->bindParam(':today', $todate);
        $sqlclockin->bindParam(':timein', $timein);
        $sqlclockin->bindParam(':usersid', $_SESSION['userid']);

        // insert clockin to db
        $sqlclockin->execute();

        if ($sqlclockin->rowCount() > 0) {
            $sqlclockin = '';
            header("Location: ../dashboard.php");
            exit;
        }
    }

    if (isset($_POST['clockout'])) {

        $timestamp = time();
        $timeout = date("H:i:s", $timestamp);

        $usertimein = getUserTimeIn($_SESSION['userid'], $todate);

        $usertimeinstamp = new DateTime($usertimein);
        $usertimeoutstamp = new DateTime($timeout);
        $timedifference = $usertimeinstamp->diff($usertimeoutstamp);

        $hours = $timedifference->h + ($timedifference->d * 24);
        $minutes = $timedifference->i;
        $seconds = $timedifference->s;

        $totalhours = $hours + ($minutes / 60) + ($seconds / 3600);

        $sqlclockout = $connection->prepare("
            UPDATE timelog
            SET date_time_out = :timeout, date_total_time = :totaltime
            WHERE date = :date AND users_id = :userid;
        ");

        $sqlclockout->bindParam(':timeout', $timeout);
        $sqlclockout->bindParam(':date', $todate);
        $sqlclockout->bindParam(':userid', $_SESSION['userid']);
        $sqlclockout->bindParam(':totaltime', round($totalhours, 2));
        $sqlclockout->execute();

        if ($sqlclockout->rowCount() > 0) {
            $sqlclockout = '';
            header("Location: ../dashboard.php");
            exit;
        }
    }
}

function userHasTimeIn($userid)
{
    include 'dbhandler.inc.php';

    $todate = date("Y-m-d");

    # check if the user has already logged in for today
    $sqlcheckdatetoday = $connection->prepare("SELECT date_time_in from timelog WHERE date = :today AND users_id = :userid");
    $sqlcheckdatetoday->bindParam(':today', $todate);
    $sqlcheckdatetoday->bindParam(':userid', $userid);
    $sqlcheckdatetoday->execute();
    $user = $sqlcheckdatetoday->fetch();

    if ($sqlcheckdatetoday->rowCount() > 0 && $user['date_time_in'] != '00:00:00') {
        $sqlcheckdatetoday = '';
        return true;
        exit;
    } else {
        $sqlcheckdatetoday = '';
        return false;
        exit;
    }
}

function userHasTimeOut($userid)
{
    include 'dbhandler.inc.php';

    $todate = date("Y-m-d");

    # check if the user has already logged in for today
    $sqlcheckouttoday = $connection->prepare("SELECT date_time_out from timelog WHERE date = :today AND users_id = :userid");
    $sqlcheckouttoday->bindParam(':today', $todate);
    $sqlcheckouttoday->bindParam(':userid', $userid);
    $sqlcheckouttoday->execute();
    $user = $sqlcheckouttoday->fetch();

    if ($sqlcheckouttoday->rowCount() > 0 && $user['date_time_out'] != '00:00:00') {
        $sqlcheckouttoday = '';
        return true;
        exit;
    } else {
        $sqlcheckouttoday = '';
        return false;
        exit;
    }
}

function getUserTimeIn($userid, $date)
{

    include 'dbhandler.inc.php';

    $sqlgettimein = $connection->prepare("SELECT date_time_in FROM timelog WHERE date = :todate AND users_id = :userid");
    $sqlgettimein->bindParam(':todate', $date);
    $sqlgettimein->bindParam(':userid', $userid);
    $sqlgettimein->execute();
    $user = $sqlgettimein->fetch();

    return $user['date_time_in'];
}

function getTotalNoOfHours($userid)
{

    include 'dbhandler.inc.php';

    $sqlgethours = $connection->prepare("SELECT date_total_time FROM timelog WHERE users_id = :userid;");
    $sqlgethours->bindParam(':userid', $userid);
    $sqlgethours->execute();

    $allhours = $sqlgethours->fetchAll(PDO::FETCH_ASSOC);
    $sum = 0.0 ;
    foreach ($allhours as $row) {
        if (isset($row['date_total_time']) && is_numeric($row['date_total_time'])) {
            $sum += (float) $row['date_total_time'];
        }
    }
    $sqlgethours = '';
    return $sum;
}
