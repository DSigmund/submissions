<?php
$id = $_GET["id"];  // Ensure to validate and sanitize this parameter

$delimiter = "¶";

ini_set("memory_limit", "1024M");

// Set headers for downloading the file as CSV
header("Content-type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=entries_csv_" . htmlspecialchars($id) . ".csv");
header("Pragma: no-cache");
header("Expires: 0");

// Require config and database connection
require_once('config.php');
require_once('functions.php');
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->set_charset("utf8");

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Prepare SQL statement to prevent SQL injection
$stmt = $mysql->prepare("SELECT data_id, name, value FROM Td6PNmU6_cf7_vdata_entry WHERE cf7_id = ? AND name NOT LIKE '\\_%' ORDER BY data_id ASC, name ASC");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$entries = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_id = $row["data_id"];
        $name = $row["name"];
        $value = $row["value"];

        // Filtering out unwanted fields
        if ($name != "g-recaptcha-response" && $name != "dsgvo") {
            $entries[$data_id]["data_id"] = $data_id;
            $entries[$data_id][$name] = trim(preg_replace("~[\r\n]~", " ", $value));
        }
    }
} else {
    echo "0 results";
    $mysql->close();
    die();
}

// Initialize output variable
$output = "";

// Generate headers from the keys of the first entry
$headers = array_keys($entries[array_keys($entries)[0]]);
$output .= implode($delimiter, $headers) . "\n";

// Generate rows for each entry
foreach ($entries as $entry => $fields) {
    $line = [];
    foreach ($headers as $header) {
        $line[] = array_key_exists($header, $fields) ? escape_csv($fields[$header]) : "";
    }
    $output .= implode($delimiter, $line) . "\n";
}

// Output the results
echo $output;

// Close the database connection
$mysql->close();
die();

// Function to escape CSV specific characters
function escape_csv($value) {
    $value = str_replace('"', '""', $value);  // Escape double quotes
    if (strpos($value, '"') !== false || strpos($value, $delimiter) !== false || strpos($value, "\n") !== false) {
        $value = '"' . $value . '"';  // Enclose in double quotes if necessary
    }
    return $value;
}
?>
