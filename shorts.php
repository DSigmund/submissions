<?php

require_once('config.php');
require_once('functions.php');

$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->set_charset("utf8");

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Check if CAT_SHORTS is defined and handle potential issues with dynamic SQL
if (!defined('CAT_SHORTS')) {
    die('CAT_SHORTS constant is not defined.');
}

$stmt = $mysql->prepare("SELECT data_id, name, value FROM Td6PNmU6_cf7_vdata_entry WHERE cf7_id = ? AND name NOT LIKE '\\_%' ORDER BY data_id ASC, name ASC");
$stmt->bind_param('i', CAT_SHORTS);
$stmt->execute();
$result = $stmt->get_result();

$entries = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_id = $row["data_id"];
        $entries[$data_id]["data_id"] = $data_id;
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
    <title>PJ <?php echo defined('PJ_YEAR') ? PJ_YEAR : "Year Not Set"; ?> - Entries - Shorts</title>
</head>
<body>
<div class="container-fluid">
    <h1>PJ <?php echo defined('PJ_YEAR') ? PJ_YEAR : "Year Not Set"; ?> - Entries - Shorts</h1>
    <table class="table table-striped table-hover sortable">
        <thead>
        <tr>
            <th data-defaultsort="asc">Number</th>
            <th>Status</th>
            <th>Title in English</th>
            <th>Entered By</th>
            <th>Entering Name</th>
            <th>Duration</th>
            <th>Photos</th>
            <th>upload link</th>
            <th>upload quality</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($entries as $entry => $value): ?>
            <?php if (isset($value['status']) && $value['status'][0] != '3') continue; ?>
            <tr>
                <td data-value="<?php echo num2Sort($value["reg-number"]); ?>"><?php echo htmlspecialchars($value["reg-number"]); ?></td>
                <td><?php echo htmlspecialchars($value["status"]); ?></td>
                <td><?php echo htmlspecialchars($value["title-in-english"]); ?></td>
                <td><?php echo htmlspecialchars($value["entered-by"]); ?></td>
                <td><?php echo ($value["entered-by"] == "Broadcaster / Content Distributor") ? htmlspecialchars($value["name-telecaster"]) : htmlspecialchars($value["name-producing-company"]); ?></td>
                <td><?php echo htmlspecialchars($value["duration-in-minutes"]); ?></td>
                <td><?php echo htmlspecialchars($value["photos"]); ?></td>
                <td><?php echo htmlspecialchars($value["upload-link"]); ?></td>
                <td><?php echo htmlspecialchars($value["upload-quality"]); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-sortable.js"></script>
</body>
</html>
