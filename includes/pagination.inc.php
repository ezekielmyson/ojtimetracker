<?php

include 'dbhandler.inc.php';

if (isset($_GET['page_no']) && $_GET['page_no'] !== "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}

// total rows to display
$total_records_per_page = 10;

// get the offset limit for the query
$offset = ($page_no - 1) * $total_records_per_page;

// query get all data 
$usersid = $_SESSION['userid'];

$sqlquery = $connection->prepare("SELECT * FROM timelog WHERE users_id = :userid LIMIT $offset, $total_records_per_page;");
$sqlquery->bindParam(':userid', $usersid);
$sqlquery->execute();
$result = $sqlquery->fetchAll(PDO::FETCH_ASSOC);

$previous_page = $page_no - 1;
$next_page = $page_no + 1;

$result_count = $connection->prepare("SELECT COUNT(*) as total_records FROM timelog WHERE users_id = :userid");
$result_count->bindParam(':userid', $usersid);
$result_count->execute();
$records = $result_count->fetch();
$total_records = $records['total_records'];
$total_no_pages = ceil($total_records / $total_records_per_page);

?>

<div class="pagination-container">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Time in</th>
                <th>Time out</th>
                <th>Total # of hours</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result as $row) {
            ?>
                <tr>
                    <td><?php

                        $daterec = strtotime($row['date']);
                        $newdate = date("M d, Y (D)", $daterec);
                        echo $newdate;
                        $newdate = '';
                        $daterec = '';
                        ?></td>
                    <td><?php
                        $timerec = strtotime($row['date_time_in']);
                        $newtimein = date("h:i:s A", $timerec);
                        echo $newtimein;
                        $timerec = '';
                        $newtimein = '';
                        ?></td>
                    <td><?php
                        if ($row['date_time_out'] == '00:00:00') {
                            echo $row['date_time_out'];
                        } else {
                            $timeorec = strtotime($row['date_time_out']);
                            $newtimeo = date("h:i:s A", $timeorec);
                            echo $newtimeo;
                            $timeorec = '';
                            $newtimeo = '';
                        }
                        ?></td>
                    <td><?php echo $row['date_total_time']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <nav>
        <ul class="pagination">
            <li class="page-item"><a class="page-link <?= ($page_no <= 1) ? 'disabled' : ''; ?>" <?= ($page_no > 1) ? 'href=?page_no=' . $previous_page : ''; ?>>Previous</a></li>

            <?php

            for ($counter = 1; $counter <= $total_no_pages; $counter++) {
            ?>
                <li class="page-item"><a class="page-link" href="?page_no=<?= $counter; ?>"><?= $counter; ?></a></li>
            <?php } ?>
            <li class="page-item"><a class="page-link <?= ($page_no >= $total_no_pages) ? 'disabled' : ''; ?>" <?= ($page_no < $total_no_pages) ? 'href=?page_no=' . $next_page : ''; ?>>Next</a></li>
        </ul>
        <div class="p-10">
            <strong>Page <?= $page_no; ?> of <?= $total_no_pages; ?></strong>
        </div>
    </nav>

</div>