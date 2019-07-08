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
    if($cat != "all" && $key == "Category" && $v != str_replace("_", " ", $cat)) {
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
    $output .= $v;
    $output .= "</".$valid_key.">";
  }

  $output .= "<mediabar>".$value["DVD"]."</mediabar>";
  
  $output .= "<entering_name>";
  $output .= ($value["EnteredBy"] == "Telecaster") ? $value["NameTelecaster"] : $value["CompanyProducing"] . $value["CompanyProducing copy"];
  $output .= "</entering_name>";

  $output .= "<entering_street>";
  $output .= ($value["EnteredBy"] == "Telecaster") ? $value["Street_Telecaster"] : $value["StreetProducing"];
  $output .= "</entering_street>";

  $output .= "<entering_city>";
  $output .= ($value["EnteredBy"] == "Telecaster") ? $value["City_Telecaster"] : $value["CityProducing"] . $value["CityProducing copy"];
  $output .= "</entering_city>";

  $output .= "<entering_country>";
  $output .= ($value["EnteredBy"] == "Telecaster") ? $value["Country_Telecaster"] : $value["CountryProducing"] . $value["CountryProducing copy"];
  $output .= "</entering_country>";

  $output .= "<entering_contact>";
  $output .= ($value["EnteredBy"] == "Telecaster") ? $value["FirstNameTelecaster"] ." ". $value["LastNameTelecaster"] : $value["FirstNameProducing"] . $value["FirstNameProducing copy"] . " " . $value["LastNameProducing"] . $value["LastNameProducing copy"];
  $output .= "</entering_contact>";

  $output .= "<entering_contactFirstName>";
  $output .= ($value["EnteredBy"] == "Telecaster") ? $value["FirstNameTelecaster"] : $value["FirstNameProducing"] . $value["FirstNameProducing copy"];
  $output .= "</entering_contactFirstName>";

  $output .= "<entering_contactLastName>";
  $output .= ($value["EnteredBy"] == "Telecaster") ? $value["LastNameTelecaster"] : $value["LastNameProducing"] . $value["LastNameProducing copy"];
  $output .= "</entering_contactLastName>";

  $output .= "<entering_email>";
  $output .= ($value["EnteredBy"] == "Telecaster") ? $value["email_Telecaster"] : $value["emailProducing"] . $value["emailProducing copy"];
  $output .= "</entering_email>";

  $output .= "<entering_phone>";
  $output .= ($value["EnteredBy"] == "Telecaster") ? $value["Phone_Telecaster"] : $value["PhoneProducing"] . $value["PhoneProducing copy"];
  $output .= "</entering_phone>";

  $output .= "<entering_zipcode>";
  $output .= ($value["EnteredBy"] == "Telecaster") ? $value["ZipCode_Telecaster"] : $value["ZipCodeProducing"] . $value["ZipCodeProducing copy"];
  $output .= "</entering_zipcode>";

  $output .= "</entry>";
}
$output .= "</xml>";
// 4. echo values
echo $output;

// 5. die
die();
?>