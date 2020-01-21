<?php
$id = $_GET["id"];

ini_set("memory_limit","1024M");

// Create CSV for Form by ID

header("Content-type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=entries_csv_".$id.".csv");
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
    $entries[$row["data_id"]][$row["name"]] = htmlspecialchars(trim(preg_replace("~[\r\n]~", " ",$row["value"])));
  }
} else {
  echo "0 results";
}
$mysql->close();
/*echo "<pre>";
print_r($entries);
echo "</pre>";*/


// 2. create header-line from this
$headers = array_keys($entries[array_keys($entries)[0]]);
for ($j=0; $j < count($headers); $j++) { 
  $output .= $headers[$j]."|";
}
$output = rtrim($output, "|");
$output .= "\n";

// 3. create lines with values
foreach ($entries as $entry => $value) {
  foreach ($value as $key => $v) {
    $output .= unescape($v)."|";
  }
  $output = rtrim($output, "|");
  $output .= "\n";
}
// 4. echo values
echo $output;

// 5. die
die();
?>