<?php

if(isset($_GET["category"])) {
  $cat = $_GET["category"];
} else {
  $cat = "all";
}

// 0. require config and connect to database
require_once('config.php');
require_once('functions.php');
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->query("SET NAMES utf8"); 
if ($mysql->connect_error) {
  die("Connection failed: " . $mysql->connect_error);
} 

header("Content-type: text/plain; charset=utf-8");
header("Content-Disposition: attachment; filename=2020_maintv_".$cat.".csv");

$sql = "SELECT data_id, name, value FROM Td6PNmU6_cf7_vdata_entry WHERE cf7_id=".CAT_MAIN_TV." AND name NOT LIKE \"\\_%\" ORDER BY data_id ASC, name ASC";

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
?>
SubmissionId|Category|Nummerierung|Nummer final|Programme Title|EnteredBy|entering_name|entering_country|Length|Upload_link|Upload_Eingang_und_Qualitaet|Script|Photos|Technik_Check|PermissionforCopyBar|Telecaster_Confirmation|Summary_reviewed|Comment
<?php
foreach ($entries as $entry => $value) {
  $skip = false;
  foreach ($value as $key => $v) {
    if($cat != "all" && $key == "category" && $v != str_replace("_", " ", $cat)) {
      $skip = true;
    }
    if($key == "status" && $v[0] != "3") {
      $skip = true;
    }
  }
  if($skip) {
    continue;
  }
  $telecaster = $value["entered-by"]== "Telecaster / Digital Distributor";

  echo $value["data_id"];
  echo "|";
  echo $value["category"];
  echo "|";
  echo $value["number-provisional"];
  echo "|";
  echo $value["number-final"];
  echo "|";
  echo unescape($value["title-in-english"]);
  echo "|";
  echo unescape($value["entered-by"]);
  echo "|";
  echo $telecaster ? unescape($value["name-telecaster"]) : unescape($value["name-producing-company"]);
  echo "|";
  echo $telecaster ? unescape($value["country-telecaster"]) : unescape($value["country-producing-company"]);
  echo "|";
  echo excelNumber($value["duration-in-minutes"]);
  echo "|";
  echo unescape($value["upload-link"]);
  echo "|";
  echo unescape($value["upload-quality"]);
  echo "|";
  echo unescape($value["script"]);
  echo "|";
  echo unescape($value["photos"]);
  echo "|";
  echo unescape($value["technik-check"]);
  echo "|";
  echo unescape($value["copy-bar"]);
  echo "|";
  echo unescape($value["telecaster-confirmation"]);
  echo "|";
  echo unescape($value["summary-reviewed"]);
  echo "|";
  echo unescape($value["comment"]);
  echo "\n";
}
?>