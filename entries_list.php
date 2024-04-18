<?php

$category = isset($_GET["category"]) ? $_GET["category"] : "all";

// Setting up headers for CSV output
header("Content-type: text/plain; charset=utf-8");
header("Content-Disposition: attachment; filename=\"2020_maintv_".htmlspecialchars($category).".csv\"");
header("Pragma: no-cache");
header("Expires: 0");

// Include configuration and functions
require_once('config.php');
require_once('functions.php');

// Establish database connection
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->set_charset("utf8");

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Prepared statement to fetch data securely
$catValue = defined('CAT_MAIN_TV') ? CAT_MAIN_TV : 0; // Ensure CAT_MAIN_TV is defined
$stmt = $mysql->prepare("SELECT data_id, name, value FROM Td6PNmU6_cf7_vdata_entry WHERE cf7_id = ? AND name NOT LIKE '\\_%' ORDER BY data_id ASC, name ASC");
$stmt->bind_param("i", $catValue);
$stmt->execute();
$result = $stmt->get_result();

$entries = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data_id = $row["data_id"];
        $name = $row["name"];
        $entries[$data_id]["data_id"] = $data_id;
        $entries[$data_id][$name] = htmlspecialchars(trim(preg_replace("~[\r\n]~", " ", $row["value"])));
    }
} else {
    echo "0 results";
    $mysql->close();
    exit;
}

$mysql->close();

// Output the header row for the CSV
echo "SubmissionId|Category|Nummerierung|Nummer final|Programme Title|EnteredBy|entering_name|entering_country|Length|Upload_link|Upload_Eingang_und_Qualitaet|Script|Photos|Technik_Check|PermissionforCopyBar|Telecaster_Confirmation|Summary_reviewed|Comment\n";

// Process each entry for output
foreach ($entries as $entry => $value) {
    $skip = false;
    foreach ($value as $key => $v) {
        if ($category != "all" && $key == "category" && $v != str_replace("_", " ", $category)) {
            $skip = true;
        }
        if ($key == "status" && $v[0] != "3") {
            $skip = true;
        }
    }
    if ($skip) continue;

    $telecaster = $value["entered-by"] == "Telecaster / Digital Distributor";

    // Output each field, ensuring proper CSV encoding
    echo formatCSV([
        $value["data_id"],
        $value["category"],
        $value["number-provisional"],
        $value["number-final"],
        $value["title-in-english"],
        $value["entered-by"],
        $telecaster ? $value["name-telecaster"] : $value["name-producing-company"],
        $telecaster ? $value["country-telecaster"] : $value["country-producing-company"],
        excelNumber($value["duration-in-minutes"]),
        $value["upload-link"],
        $value["upload-quality"],
        $value["script"],
        $value["photos"],
        $value["technik-check"],
        $value["copy-bar"],
        $value["telecaster-confirmation"],
        $value["summary-reviewed"],
        $value["comment"]
    ]);
    echo "\n";
}

function formatCSV($fields) {
    $delimiter = "|";
    array_walk($fields, function (&$field) use ($delimiter) {
        $field = '"' . str_replace('"', '""', $field) . '"';  // Enclose fields in quotes and escape quotes within fields
    });
    return implode($delimiter, $fields);
}

function excelNumber($number) {
    return preg_replace("/[^0-9]/", "", $number);
}

?>
