<?php
require_once('config.php');
require_once('functions.php');

$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->set_charset("utf8"); 

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
} 

// Assuming CAT_MAIN_TV is a constant defined in 'config.php'
$stmt = $mysql->prepare("SELECT data_id, name, value FROM Td6PNmU6_cf7_vdata_entry WHERE cf7_id = ? ORDER BY data_id ASC, name ASC");
$stmt->bind_param("i", CAT_MAIN_TV);
$stmt->execute();
$result = $stmt->get_result();

$entries = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data_id = $row["data_id"];
        foreach ($row as $key => $value) {
            if ($key != "data_id") {
                $entries[$data_id][$key] = htmlspecialchars(trim(preg_replace("~[\r\n]~", " ", $value)));
            }
        }
    }
} else {
    echo "0 results";
    $mysql->close();
    exit;
}

$mysql->close();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-sortable.css">
    <title>PJ <?php echo PJ_YEAR;?> - Entries - Main TV</title>
</head>
<body>
<div class="container-fluid">
    <h1>PJ <?php echo PJ_YEAR; ?> - Entries - Main TV</h1>
    <table class="table table-striped table-hover sortable">
        <thead>
        <tr>
            <th data-defaultsort="asc">Number Provisional</th>
            <th>Title in English</th>
            <th>Category</th>
            <th>Entered By</th>
            <th>Entering Name</th>
            <th>Duration</th>
            <th>Photos</th>
            <th>Script</th>
            <th>summary reviewed</th>
            <th>technik check</th>
            <th>telecaster confirmation</th>
            <th>upload link</th>
            <th>upload quality</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($entries as $entry => $value) {
            if (isset($value["status"]) && $value["status"][0] != "3") {
                continue; // Skip the entry if the status is not '3'
            }
            $telecaster = ($value["entered-by"] == "Broadcaster / Content Distributor");
            $catsort = cat2Sort($value["category"]);
            $numsort = num2Sort($value["number-provisional"]);
            ?>
            <tr>
                <td data-value="<?php echo $numsort; ?>"><?php echo $value["number-provisional"]; ?></td>
                <td><?php echo $value["title-in-english"]; ?></td>
                <td data-value="<?php echo $catsort; ?>"><?php echo $value["category"]; ?></td>
                <td><?php echo $value["entered-by"]; ?></td>
                <td><?php echo $telecaster ? $value["name-telecaster"] : $value["name-producing-company"]; ?></td>
                <td><?php echo $value["duration-in-minutes"]; ?></td>
                <td><?php echo $value["photos"]; ?></td>
                <td><?php echo $value["script"]; ?></td>
                <td><?php echo $value["summary-reviewed"]; ?></td>
                <td><?php echo $value["technik-check"]; ?></td>
                <td><?php echo $value["telecaster-confirmation"]; ?></td>
                <td><?php echo $value["upload-link"]; ?></td>
                <td><?php echo $value["upload-quality"]; ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-sortable.js"></script>
</body>
</html>
