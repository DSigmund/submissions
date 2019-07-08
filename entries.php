<?php
$id = $_GET["id"];
// Create CSV for Form by ID

header("Content-type: text/xml; charset=utf-8");
// header("Content-Disposition: attachment; filename=".$id.".xml");
header("Pragma: no-cache");
header("Expires: 0");

// 0. require config and connect to database
require_once('config.php');
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
    $entries[$row["data_id"]][$row["name"]] = htmlspecialchars(trim(preg_replace("~[\r\n]~", " ",$row["value"])));
  }
} else {
  echo "0 results";
}
$mysql->close();
/*echo "<pre>";
print_r($entries);
echo "</pre>";*/

$output = "<xml>";
// 2. create header-line from this
/*$headers = array_keys($entries[array_keys($entries)[0]]);
for ($j=0; $j < count($headers); $j++) { 
  $output .= $headers[$j]."|";
}
$output = rtrim($output, "|");
$output .= "\n";
*/
// 3. create lines with values
foreach ($entries as $entry => $value) {
  $output .= "<entry>";
  foreach ($value as $key => $v) {
    $valid_key = htmlspecialchars(trim(str_replace(" ", "_", preg_replace("~[\r\n]~", " ",$key))));
    $output .= "<".$valid_key.">";
    $output .= $v;
    $output .= "</".$valid_key.">";
  }
  $output .= "</entry>";
}
$output .= "</xml>";
// 4. echo values
echo $output;

// 5. die
die();
?>