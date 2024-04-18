<?php
$id = $_GET["id"];  // It's crucial to validate and sanitize this parameter

// Set the content type to XML
header("Content-type: text/xml; charset=utf-8");
header("Pragma: no-cache");
header("Expires: 0");

// Require the database configuration file
require_once('config.php');
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->set_charset("utf8");

// Check the connection
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Use a prepared statement to execute the SQL query safely
$stmt = $mysql->prepare("SELECT data_id, name, value FROM Td6PNmU6_cf7_vdata_entry WHERE cf7_id = ? AND name NOT LIKE '\\_%' ORDER BY data_id ASC, name ASC");
$stmt->bind_param("i", $id);  // 'i' specifies that the variable type is integer
$stmt->execute();
$result = $stmt->get_result();

$entries = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Fill the named array with fields, sanitizing the value to prevent issues in XML output
        $entries[$row["data_id"]]["data_id"] = $row["data_id"];
        $entries[$row["data_id"]][$row["name"]] = htmlspecialchars(trim(preg_replace("~[\r\n]~", " ", $row["value"])));
    }
} else {
    echo "<xml><result>0 results</result></xml>";
    $mysql->close();
    die();
}

// Start XML output
$output = "<?xml version='1.0' encoding='UTF-8'?>";
$output .= "<entries>";

// Generate entries for XML
foreach ($entries as $entry => $value) {
    $output .= "<entry>";
    foreach ($value as $key => $v) {
        $valid_key = htmlspecialchars(trim(str_replace(" ", "_", preg_replace("~[\r\n]~", " ", $key))));
        $output .= "<$valid_key>$v</$valid_key>";
    }
    $output .= "</entry>";
}

$output .= "</entries>";
echo $output;

// Close the database connection and exit the script
$mysql->close();
die();
?>
