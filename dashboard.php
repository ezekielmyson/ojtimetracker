<?php
include 'includes/session.inc.php';
include 'includes/general.inc.php';
include 'includes/timelog.inc.php';

if (!isUserLoggedin()) {
    header("Location: index.php");
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
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/main.css">
    <title>OMH Cebu IT Timetracker | OJT</title>
</head>

<body>

    <header class="index-header-container">
        <h1 class="text-shadow">OMH Cebu IT Timetracker | OJT</h1>
        <div class="index-header-profile-container">
            <p class="index-header-profile-container-label">Currently logged as :</p>
            <p class="loggedin-user">
                <?php

                if (isset($_SESSION['loggedin']) && isset($_SESSION['userid']) && isset($_SESSION['email'])) {
                    echo "<p class='loggedin-user'>" . $_SESSION['email'] . "</p>";
                    echo "<button class='logoutbtn' onclick=\"location.href='logout.php'\" type=\"button\">Logout</button>";
                }
                ?>
            </p>
        </div>
    </header>

    <main class="main-container">

        <div class="main-container-wrapper">

            <div class="main-container-header">
                <!-- <p>Today is : <span class="bold"><?php echo $today; ?></span></p> -->
                <p>Today is : &nbsp;</p>
                <div class="main-container-clock" id="date"></div>&nbsp;
                <div class="main-container-clock" id="time"></div>
                <div class="main-container-header-hours">
                    <p>Total # of hours : <span class="bold p-title"><?php echo getTotalNoOfHours($_SESSION['userid']); ?> Hours</span></p>
                </div>
                <div class="main-container-header-buttons">
                    <form action="includes/timelog.inc.php" method="post">
                        <?php
                        if (!userHasTimeIn($_SESSION['userid']) && !userHasTimeOut($_SESSION['userid'])) {
                            echo "<button name='clockin'>Clock in</button>";
                        }

                        if (userHasTimeIn($_SESSION['userid']) && !userHasTimeOut($_SESSION['userid'])) {
                            echo "<button name='clockout'>Clock out</button>";
                        }

                        if (userHasTimeIn($_SESSION['userid']) && userHasTimeOut($_SESSION['userid'])) {
                            echo "<p>You are done for today please come back tomorrow!</p>";
                        }
                        ?>
                    </form>

                </div>
            </div>

            <div class="main-container-content">
              
                 <?php include 'includes/pagination.inc.php'; ?>

            </div>


        </div>

    </main>

    <footer>
        <p>All Rights Reserved.&#169; 2025 aldinm@omegahms.com</p>
    </footer>


    <!-- Insert JS Script for TIME -->

    <script>
        let is24Hour = false;

        function updateClock() {
            const now = new Date();
            let hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            const day = now.getDate();
            const month = (now.getMonth() + 1);
            const year = now.getFullYear();

            let timeString;
            if (is24Hour) {
                timeString = `${hours}:${minutes}:${seconds}`;
            } else {
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                timeString = `${hours}:${minutes}:${seconds} ${ampm}`;
            }
            document.getElementById('time').textContent = timeString;
            document.getElementById('date').textContent = `${month}/${day}/${year}`;
        }

        setInterval(updateClock, 1000); // Update every second
        updateClock(); // Initial call to display time
    </script>

</body>

</html>