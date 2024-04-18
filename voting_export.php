<?php
$id = 2353;  // Default form ID
$secondId = 2737;  // Second form ID to include

$delimiter = "|";

ini_set("memory_limit", "1024M");

header("Content-type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=personal_csv.csv");
header("Pragma: no-cache");
header("Expires: 0");

require_once('config.php');
require_once('functions.php');

$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->set_charset("utf8");

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Prepared statement to select entries
$stmt = $mysql->prepare("SELECT data_id, name, value, cf7_id FROM Td6PNmU6_cf7_vdata_entry WHERE (cf7_id = ? OR cf7_id = ?) AND name NOT LIKE '\\_%' ORDER BY data_id ASC, name ASC");
$stmt->bind_param("ii", $id, $secondId);
$stmt->execute();
$result = $stmt->get_result();

$entries = [];
while ($row = $result->fetch_assoc()) {
    $dataId = $row["data_id"];
    if (!in_array($row["name"], ["g-recaptcha-response", "dsgvo"])) {
        $entries[$dataId]["data_id"] = $dataId;
        $entries[$dataId]["form_id"] = $row["cf7_id"];
        $entries[$dataId][$row["name"]] = trim(preg_replace("~[\r\n]~", " ", $row["value"]));
    }
}

if (empty($entries)) {
    echo "0 results";
    $mysql->close();
    exit;
}

$mysql->close();

$output = "form_id" . $delimiter . "data_id" . $delimiter . "involved" . $delimiter . "voting" . "\n";

foreach ($entries as $entry => $value) {
    if (isset($value["participation"]) && $value["participation"] == "Voter") {
        $Line = "";
        $Line .= escapeCsv($value["form_id"], $delimiter) . $delimiter;
        $Line .= escapeCsv($value["data_id"], $delimiter) . $delimiter;
        $Line .= escapeCsv($value["involved"], $delimiter) . $delimiter;
        $Line .= escapeCsv($value["voting"], $delimiter) . $delimiter;
        $output .= rtrim($Line, $delimiter) . "\n";  // Append the line and remove trailing delimiter
    }
}

echo $output;
die();

function escapeCsv($string, $delimiter) {
    $escaped = str_replace('"', '""', $string);  // Double-up double quotes
    if (strpos($string, $delimiter) !== false || strpos($string, '"') !== false || strpos($string, "\n") !== false) {
        $escaped = '"' . $escaped . '"';  // Quote fields that have delimiters, double quotes, or newlines
    }
    return $escaped;
}
?>
