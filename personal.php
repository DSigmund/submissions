<?php
$id = 972;

$delimeter = "¶";

ini_set("memory_limit","1024M");

// Create CSV for Form by ID

header("Content-type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=personal_csv_.csv");
header("Pragma: no-cache");
header("Expires: 0");

// 0. require config and connect to database
require_once('config.php');
require_once('functions.php');
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->query("SET NAMES utf8"); 
if ($mysql->connect_error) {
  die("Connection failed: " . $mysql->connect_error);
} 

$sql = "SELECT data_id, name, value FROM Td6PNmU6_cf7_vdata_entry WHERE cf7_id=".$id." AND name NOT LIKE \"\\_%\" ORDER BY data_id ASC, name ASC";

$result = $mysql->query($sql);

$entries = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    // 1. Fill Named array with fields
    $entries[$row["data_id"]]["data_id"] = $row["data_id"];
    if($row["name"] != "g-recaptcha-response" && $row["name"] != "dsgvo") {
      $entries[$row["data_id"]][$row["name"]] = trim(preg_replace("~[\r\n]~", " ",$row["value"]));
    }
  }
} else {
  echo "0 results";
}
$mysql->close();

$output .= "data_id".$delimeter."family-name".$delimeter."first-name".$delimeter."contact-email".$delimeter."arrival".$delimeter."departure".$delimeter;
$output .= "zip".$delimeter."city".$delimeter."country".$delimeter;
$output .= "department".$delimeter."job-title".$delimeter."organisation".$delimeter;
$output .= "involved".$delimeter."phone".$delimeter."submit_time".$delimeter;
$output .= "participation".$delimeter."voting";  

$output .= "\n";


// 3. create lines with values
foreach ($entries as $entry => $value) {
  $output .= unescape($value["data_id"]).$delimeter;
  $output .= unescape($value["family-name"]).$delimeter;
  $output .= unescape($value["first-name"]).$delimeter;
  $output .= unescape($value["contact-email"]).$delimeter;
  $output .= unescape($value["arrival"]).$delimeter;
  $output .= unescape($value["departure"]).$delimeter;
  $output .= unescape($value["zip"]).$delimeter;
  $output .= unescape($value["city"]).$delimeter;
  $output .= unescape($value["country"]).$delimeter;
  $output .= unescape($value["department"]).$delimeter;
  $output .= unescape($value["job-title"]).$delimeter;
  $output .= unescape($value["organisation"]).$delimeter;
  $output .= unescape($value["involved"]).$delimeter;
  $output .= unescape($value["phone"]).$delimeter;
  $output .= unescape($value["submit_time"]).$delimeter;
  $output .= unescape($value["participation"]).$delimeter;
  $output .= unescape($value["voting"]).$delimeter;

  $output = rtrim($output, $delimeter);
  $output .= "\n";
}
// 4. echo values
echo $output;

// 5. die
die();
?>