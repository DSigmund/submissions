<?php
$id = isset($_GET["id"]) ? $_GET["id"] : die("ID is required.");
$cat = isset($_GET["category"]) ? $_GET["category"] : "all";
$status = isset($_GET["status"]) ? $_GET["status"] : "all";

header("Content-type: text/xml; charset=utf-8");
header("Pragma: no-cache");
header("Expires: 0");

require_once('config.php');
require_once('functions.php');

$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->set_charset("utf8");

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

$stmt = $mysql->prepare("SELECT data_id, name, value FROM Td6PNmU6_cf7_vdata_entry WHERE cf7_id = ? AND name NOT LIKE '\\_%' ORDER BY data_id ASC, name ASC");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$entries = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $entries[$row["data_id"]]["data_id"] = $row["data_id"];
        foreach ($row as $key => $value) {
            if ($key != "data_id") {
                $entries[$row["data_id"]][$key] = htmlspecialchars(trim(preg_replace("~[\r\n]~", " ", $value)));
            }
        }
    }
} else {
    echo "<xml><result>0 results</result></xml>";
    $mysql->close();
    exit;
}

$mysql->close();

$output = "<?xml version='1.0' encoding='UTF-8'?>";
$output .= "<entries>";

foreach ($entries as $entry => $value) {
    $skip = false;
    if ($cat != "all" && isset($value["category"]) && $value["category"] != str_replace("_", " ", $cat)) {
        $skip = true;
    }
    if ($status != "all" && isset($value["status"]) && $value["status"][0] != $status) {
        $skip = true;
    }
    if ($skip) {
        continue;
    }

    $output .= "<entry>";
    foreach ($value as $key => $v) {
        $key = htmlspecialchars(trim(str_replace(" ", "_", preg_replace("~[\r\n]~", " ", $key))));
        $output .= "<$key><![CDATA[$v]]></$key>";
    }

    $output .= "<mediabar>".$value["DVD"]."</mediabar>";
  
    $telecaster = $value["entered-by"]== "Telecaster / Digital Distributor";

    $output .= "<entering-name>";
    $output .= $telecaster ? "<![CDATA[".unescape($value["name-telecaster"])."]]>" : "<![CDATA[".unescape($value["name-producing-company"])."]]>";
    $output .= "</entering-name>";

    $output .= "<entering-street>";
    $output .= $telecaster ? "<![CDATA[".unescape($value["street-telecaster"])."]]>" : "<![CDATA[".unescape($value["street-producing-company"])."]]>";
    $output .= "</entering-street>";

    $output .= "<entering-city>";
    $output .= $telecaster ? "<![CDATA[".unescape($value["city-telecaster"])."]]>" : "<![CDATA[".unescape($value["city-producing-company"])."]]>";
    $output .= "</entering-city>";

    $output .= "<entering-zipcode>";
    $output .= $telecaster ? "<![CDATA[".unescape($value["zip-telecaster"])."]]>" : "<![CDATA[".unescape($value["zip-producing-company"])."]]>";
    $output .= "</entering-zipcode>";

    $output .= "<entering-country>";
    $output .= $telecaster ? "<![CDATA[".unescape($value["country-telecaster"])."]]>" : "<![CDATA[".unescape($value["country-producing-company"])."]]>";
    $output .= "</entering-country>";

    $output .= "<entering-contact>";
    $output .= $telecaster ? "<![CDATA[".unescape($value["contact-telecaster-first-name"])." ".unescape($value["contact-telecaster-last-name"])."]]>" : "<![CDATA[".unescape($value["contact-first-name"]). " " .unescape($value["contact-last-name"])."]]>";
    $output .= "</entering-contact>";

    $output .= "<entering-contactFirstName>";
    $output .= $telecaster ? "<![CDATA[".unescape($value["contact-telecaster-first-name"])."]]>" : "<![CDATA[".unescape($value["contact-first-name"])."]]>" ;
    $output .= "</entering-contactFirstName>";

    $output .= "<entering-contactLastName>";
    $output .= $telecaster ? "<![CDATA[".unescape($value["contact-telecaster-last-name"])."]]>" : "<![CDATA[".unescape($value["contact-last-name"])."]]>";
    $output .= "</entering-contactLastName>";

    $output .= "<entering-email>";
    $output .= $telecaster ? "<![CDATA[".unescape($value["contact-telecaster-email"])."]]>" : "<![CDATA[".unescape($value["contact-email"])."]]>";
    $output .= "</entering-email>";

    $output .= "<entering-phone>";
    $output .= $telecaster ? "<![CDATA[".unescape($value["contact-telecaster-phone"])."]]>" : "<![CDATA[".unescape($value["contact-phone"])."]]>";
    $output .= "</entering-phone>";

    $output .= "</entry>";
}
$output .= "</entries>";
echo $output;

die();
?>
