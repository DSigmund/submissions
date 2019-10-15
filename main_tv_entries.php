<?php

// 0. require config and connect to database
require_once('config.php');
require_once('functions.php');
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->query("SET NAMES utf8"); 
if ($mysql->connect_error) {
  die("Connection failed: " . $mysql->connect_error);
} 

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
<!DOCTYPE>
<html lang="de">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>PJ <?php echo PJ_YEAR;?> - Entries - Main TV</title>
  </head>
  <body>
    <div class="container">
      <h1>PJ <?php echo PJ_YEAR;?> - Entries - Main TV</h1>
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Number Provisional</th> 
            <th>Title in English</th>
            <th>Entered By</th>
            <th>Entering Name</th>
            <th>Duration</th>
            <th>Photos</th>
            <th>Script</th>
            <th>summary reviewed</th>
            <th>technik check</th>
            <th>telecaster confirmation</th>
            <th>upload link</th>
            <th>upload quality</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // 3. create lines with values
          foreach ($entries as $entry => $value) {
            $skip = false;
            foreach ($value as $key => $v) {
              if($key == "status" && $v[0] != "3") {
                $skip = true;
              }
            }
            if($skip) {
              continue;
            }
            $telecaster = $value["entered-by"]== "Telecaster / Digital Distributor";
          ?>
          <tr>
            <td><?php echo unescape($value["number-provisional"]);?></td>
            <td><?php echo unescape($value["title-in-english"]);?></td>
            <td><?php echo unescape($value["entered-by"]);?></td>
            <td><?php echo $telecaster ? unescape($value["name-telecaster"]) : unescape($value["name-producing-company"]);?></td>
            <td><?php echo unescape($value["duration-in-minutes"]);?></td>
            <td><?php echo unescape($value["photos"]);?></td>
            <td><?php echo unescape($value["script"]);?></td>
            <td><?php echo unescape($value["summary-reviewed"]);?></td>
            <td><?php echo unescape($value["technik-check"]);?></td>
            <td><?php echo unescape($value["telecaster-confirmation"]);?></td>
            <td><?php echo unescape($value["upload-link"]);?></td>
            <td><?php echo unescape($value["upload-quality"]);?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </body>
</html>
