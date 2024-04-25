<?php
ini_set("memory_limit", "1024M");

header("Content-Type: text/html; charset=UTF-8");
header("Pragma: no-cache");
header("Expires: 0");

require_once('config.php');
require_once('functions.php');

$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->set_charset("utf8");

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Ensure CAT_PERSONAL is defined
if (!defined('CAT_PERSONAL')) {
    die('The category constant CAT_PERSONAL is not defined.');
}
// Store the constant in a variable
$catPersonal = CAT_PERSONAL;

$stmt = $mysql->prepare("SELECT data_id, name, value FROM Td6PNmU6_cf7_vdata_entry WHERE cf7_id = ? AND name NOT LIKE '\\_%' ORDER BY data_id ASC, name ASC");
$stmt->bind_param('i', $catPersonal);
$stmt->execute();
$result = $stmt->get_result();

$entries = array();
while ($row = $result->fetch_assoc()) {
    if (!in_array($row["name"], ["g-recaptcha-response", "dsgvo"])) {
        $dataId = $row["data_id"];
        $entries[$dataId]["data_id"] = $dataId;
        $entries[$dataId][$row["name"]] = trim(preg_replace("~[\r\n]~", " ", $row["value"]));
    }
}

if (empty($entries)) {
    echo "0 results";
    $mysql->close();
    exit;
}

$mysql->close();

// Group entries by organisation
$organisedEntries = [];
foreach ($entries as $dataId => $values) {
    $organisation = $values["organisation"] ?? 'Unknown';
    $organisedEntries[$organisation][] = $values;
}

// Sort organisations alphabetically
uksort($organisedEntries, 'strcmp');
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-sortable.css">
    <title>PJ <?php echo defined('PJ_YEAR') ? PJ_YEAR : 'Year Not Set'; ?> - Voters by Organisation</title>
</head>
<body>
<div class="container-fluid">
    <h1>PJ <?php echo defined('PJ_YEAR') ? PJ_YEAR : 'Year Not Set'; ?> - Voters by Organisation</h1>
    <hr/>
    <h2>Organisations with More Than 3 Voters:</h2>
    <ul>
        <?php
        $found = false;
        foreach ($organisedEntries as $organisation => $dataEntries) {
            if (count($dataEntries) > 3) {
                echo "<li>" . htmlspecialchars($organisation) . " (" . count($dataEntries) . " entries)</li>";
                $found = true;
            }
        }
        if (!$found) {
            echo "<li>None, all good!</li>";
        }
        ?>
    </ul>
    <hr/>
    <?php
    foreach ($organisedEntries as $organisation => $dataEntries) {
        echo "<h2>" . htmlspecialchars($organisation) . " (" . count($dataEntries) . ")</h2>";
        echo "<ul>";
        foreach ($dataEntries as $entry => $values) {
            echo "<li>";
            echo htmlspecialchars($values["first-name"]) . " " . htmlspecialchars($values["family-name"]) . ": ";
            echo "<ul>";
            echo "<li>Involved: " . htmlspecialchars($values["involved"]) . "</li>";
            echo "<li>Voting: " . (isset($values["voting"]) ? htmlspecialchars($values["voting"]) : "Not specified") . "</li>";
            echo "</ul>";
            echo "</li>";
        }
        echo "</ul>";
        echo "<hr/>";
    }
    ?>
</div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-sortable.js"></script>
</body>
</html>
