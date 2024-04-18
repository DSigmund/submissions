<?php

$delimiter = "¶";

ini_set("memory_limit", "1024M");

// Setting headers for CSV file output
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

// Check if CAT_PERSONAL is defined and assigned properly
if (!defined('CAT_PERSONAL')) {
    die("CAT_PERSONAL not defined.");
}

// Prepare SQL statement to prevent SQL injection
$stmt = $mysql->prepare("SELECT data_id, name, value FROM Td6PNmU6_cf7_vdata_entry WHERE cf7_id = ? AND name NOT LIKE '\\_%' ORDER BY data_id ASC, name ASC");
$stmt->bind_param('i', CAT_PERSONAL);
$stmt->execute();
$result = $stmt->get_result();

$entries = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_id = $row["data_id"];
        if (!in_array($row["name"], ["g-recaptcha-response", "dsgvo"])) {  // Exclude specific fields
            $entries[$data_id][$row["name"]] = trim(preg_replace("~[\r\n]~", " ", $row["value"]));
        }
    }
} else {
    echo "0 results";
    $mysql->close();
    exit;
}

$mysql->close();

// Initialize output with headers
$output = "data_id" . $delimiter . "family-name" . $delimiter . "first-name" . $delimiter . "contact-email" . $delimiter . "arrival" . $delimiter . "departure" . $delimiter;
$output .= "zip" . $delimiter . "city" . $delimiter . "country" . $delimiter;
$output .= "department" . $delimiter . "job-title" . $delimiter . "organisation" . $delimiter;
$output .= "involved" . $delimiter . "phone" . $delimiter . "submit_time" . $delimiter;
$output .= "participation" . $delimiter . "voting" . $delimiter . "\n";

// Generate lines with values
foreach ($entries as $entry => $value) {
    foreach (["data_id", "family-name", "first-name", "contact-email", "arrival", "departure", "zip", "city", "country", "department", "job-title", "organisation", "involved", "phone", "submit_time", "participation", "voting"] as $field) {
        $output .= (isset($value[$field]) ? escapeCsv($value[$field], $delimiter) : "") . $delimiter;
    }
    $output = rtrim($output, $delimiter);
    $output .= "\n";
}

echo $output;

die();

function escapeCsv($value, $delimiter) {
    $value = str_replace('"', '""', $value);  // Escape double quotes
    if (strpos($value, '"') !== false || strpos($value, $delimiter) !== false || strpos($value, "\n") !== false) {
        $value = '"' . $value . '"';  // Enclose fields containing delimiter, double quotes, or newlines in double quotes
    }
    return $value;
}

?>
