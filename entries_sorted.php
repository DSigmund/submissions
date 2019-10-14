<?php
$id = $_GET["id"];
if(isset($_GET["category"])) {
  $cat = $_GET["category"];
} else {
  $cat = "all";
}
if(isset($_GET["status"])) {
  $status = $_GET["status"];
} else {
  $status = "all";
}

// Create CSV for Form by ID

header("Content-type: text/xml; charset=utf-8");
// header("Content-Disposition: attachment; filename=".$id.".xml");
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



$output = "<xml>";
// 3. create lines with values
foreach ($entries as $entry => $value) {
  // TODO: preselection. loop and skip if cat is wrong "Category"
  $skip = false;
  foreach ($value as $key => $v) {
    if($cat != "all" && $key == "category" && $v != str_replace("_", " ", $cat)) {
      $skip = true;
    }
    if($status != "all" && $key == "status" && $v[0] != $status) {
      $skip = true;
    }
  }
  if($skip == true) {
    continue;
  }
  $output .= "<entry>";
  foreach ($value as $key => $v) {
    $valid_key = htmlspecialchars(trim(str_replace(" ", "_", preg_replace("~[\r\n]~", " ",$key))));
    $output .= "<".$valid_key.">";
    $output .=  "<![CDATA[".unescape($v)."]]>";
    $output .= "</".$valid_key.">";
  }

  $output .= "<mediabar>".$value["DVD"]."</mediabar>";
  
  $output .= "<entering-name>";
  $output .= ($value["entered-by"]== "Telecaster / Digital Distributor") ? $value["name-telecaster"] : $value["name-producing-company"];
  $output .= "</entering-name>";

  $output .= "<entering-street>";
  $output .= ($value["entered-by"]== "Telecaster / Digital Distributor") ? $value["street-telecaster"] : $value["street-producing-company"];
  $output .= "</entering-street>";

  $output .= "<entering-city>";
  $output .= ($value["entered-by"]== "Telecaster / Digital Distributor") ? $value["city-telecaster"] : $value["city-producing-company"];
  $output .= "</entering-city>";

  $output .= "<entering-zipcode>";
  $output .= ($value["entered-by"]== "Telecaster / Digital Distributor") ? $value["zip-telecaster"] : $value["zip-producing-company"];
  $output .= "</entering-zipcode>";

  $output .= "<entering-country>";
  $output .= ($value["entered-by"]== "Telecaster / Digital Distributor") ? $value["country-telecaster"] : $value["country-producing-company"];
  $output .= "</entering-country>";

  $output .= "<entering-contact>";
  $output .= ($value["entered-by"]== "Telecaster / Digital Distributor") ? $value["contact-telecaster-first-name"] ." ". $value["contact-telecaster-last-name"] : $value["contact-first-name"] . " " . $value["contact-last-name"];
  $output .= "</entering-contact>";

  $output .= "<entering-contactFirstName>";
  $output .= ($value["entered-by"]== "Telecaster / Digital Distributor") ? $value["contact-telecaster-last-name"] : $value["contact-first-name"] ;
  $output .= "</entering-contactFirstName>";

  $output .= "<entering-contactLastName>";
  $output .= ($value["entered-by"]== "Telecaster / Digital Distributor") ? $value["contact-telecaster-last-name"] : $value["contact-last-name"];
  $output .= "</entering-contactLastName>";

  $output .= "<entering-email>";
  $output .= ($value["entered-by"]== "Telecaster / Digital Distributor") ? $value["contact-telecaster-email"] : $value["contact-email"];
  $output .= "</entering-email>";

  $output .= "<entering-phone>";
  $output .= ($value["entered-by"]== "Telecaster / Digital Distributor") ? $value["contact-telecaster-phone"] : $value["contact-phone"];
  $output .= "</entering-phone>";

  

  $output .= "</entry>";
}
$output .= "</xml>";
// 4. echo values
echo $output;

// 5. die
die();
?>