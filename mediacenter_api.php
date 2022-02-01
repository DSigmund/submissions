<?php
$id = 2353;

$delimeter = ";";

ini_set("memory_limit","1024M");

define( 'DB_NAME', 'webdad_wordpress' );

/** MySQL database username */
define( 'DB_USER', 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', '38T?jjp5' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

$token_given = $_GET["token"];
$secret = "IA3JYH0oQNUS4ULVO31jZ3FvuNWQDqDY";

if ($token_given != $secret) {
  die('');
}

header("Content-type: text/csv; charset=utf-8");
header("Pragma: no-cache");
header("Expires: 0");

// 0. require config and connect to database
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

$output .= "id".$delimeter."family-name".$delimeter."first-name".$delimeter."email".$delimeter;
$output .= "participation".$delimeter."voting";  
$output .= "\n";


// 3. create lines with values
foreach ($entries as $entry => $value) {
  $output .= unescape($value["data_id"]).$delimeter;
  $output .= unescape($value["family-name"]).$delimeter;
  $output .= unescape($value["first-name"]).$delimeter;
  $output .= unescape($value["contact-email"]).$delimeter;
  $output .= unescape($value["voting"]).$delimeter;

  $output = rtrim($output, $delimeter);
  $output .= "\n";
}
// 4. echo values
echo $output;

// 5. die
die();

function unescape($input) {
  $output = str_replace("\\", "", $input);
  $output = str_replace("’", "\'", $output);
  $output = str_replace("|", ";", $output);
  $output =  html_entity_decode($output);
  // $output = htmlspecialchars_decode($output);
  $output = str_replace("&quot;", "\"", $output);
  $output = str_replace("&amp;", "&", $output);
  $output = str_replace("\'", "'", $output);
  return $output;
}
function excelNumber($input) {
  return str_replace(".", ",", $input);
}
function cat2Sort($category) {
  switch($category) {
    case "Up to 6 Years Fiction": return 0;
    case "Up to 6 Years Non-Fiction": return 1;
    case "7 - 10 Years Fiction": return 2;
    case "7 - 10 Years Non-Fiction": return 3;
    case "11 - 15 Years Fiction": return 4;
    case "11 - 15 Years Non-Fiction": return 5;
    default: return -1;
  }
}
function status2Sort($status) {
  return $status[0];
}
function num2Sort($num) {
  // IV-1
  $parts = explode("-", $num);
  $front = 0;
  switch($parts[0]) {
    case "I": $front =  1;break;
    case "II": $front =  2;break;
    case "III": $front =  3;break;
    case "IV": $front =  4;break;
    case "V": $front =  5;break;
    case "VI": $front =  6;break;
    default: $front =  0;
  }
  return ($front * 1000) + $parts[1];
}
?>