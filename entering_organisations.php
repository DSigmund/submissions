<?php
$id = $_GET["id"];
$type = $_GET["type"];
// Create CSV for Form by ID

if($type == "xml") { header("Content-type: text/xml; charset=utf-8"); }
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

$result0 = $mysql->query("SELECT FormName FROM `nilev_rsform_forms` WHERE FormId=".$id);
$row0 = $result0->fetch_assoc();
$title = $id." - ".$row0["FormName"];

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

$organisations = array();
$sum_count = 0;
$sum_minutes = 0;
// 2. Create new array with values
foreach ($entries as $entry => $value) {
  $name = "";
  if($value["EnteredBy"] == "Producing Organisation") {
    $name = $value["CompanyProducing copy"] . $value["CompanyProducing"];
  } else {
    $name = $value["NameTelecaster"];
  }
  $organisations[$name]["name"] = $name;

  $country = "";
  if($value["EnteredBy"] == "Producing Organisation") {
    $country = $value["CountryProducing copy"] . $value["CountryProducing"];
  } else {
    $country = $value["Country_Telecaster"];
  }
  $organisations[$name]["country"] = $country;

  if($organisations[$name]["count"]) {
    $organisations[$name]["count"]++;
  } else {
    $organisations[$name]["count"] = 1;  
  }
  $sum_count++;
  if($organisations[$name]["minutes"]) {  
    $organisations[$name]["minutes"] += $value["DurationInMinutes"];
  } else {
    $organisations[$name]["minutes"] = $value["DurationInMinutes"];  
  }
  $organisations[$name]["entries"][] = $value;
  $sum_minutes += $value["DurationInMinutes"];
}

?>
<?php if($type == "xml"): ?>
<xml>
  <?php foreach ($organisations as $org => $value):?>
    <organisation>
      <name><?php echo $value["name"];?></name>
      <country><?php echo $value["country"];?></country>
      <count><?php echo $value["count"];?></count>
      <minutes><?php echo $value["minutes"];?></minutes>
      <entries>
        <?php foreach ($value["entries"] as $key => $entry):?>
          <entry>
            <?php foreach ($entry as $key => $v):?>
              <?php
                $valid_key = htmlspecialchars(trim(str_replace(" ", "_", preg_replace("~[\r\n]~", " ",$key))));
                echo "<".$valid_key.">";
                echo  $v;
                echo "</".$valid_key.">";
              ?>
            <?php endforeach; ?>
          </entry>
        <?php endforeach; ?>
      </entries>
    </organisation>
  <?php endforeach; ?>
</xml>
<?php else: ?>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="UTF-8">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-sortable.css">
  <title>PJ <?php echo PJ_YEAR;?> - Entering Organisations for Form <?php echo $title; ?></title>
</head>
<body>
  <div class="container">
    <h1>PJ <?php echo PJ_YEAR;?>- Entering Organisations for Form <?php echo $title; ?></h1>
    <p class="alert alert-info"><?php echo count($organisations);?> Organisations with <?php echo $sum_count;?>&nbsp;Entries with <?php echo $sum_minutes;?> minutes</p>
    <hr/>
    <table class="table table-striped table-hover table-bordered">
      <thead>
        <tr>
          <th>Organisation</th>
          <th>Country</th>
          <th data-defaultsort="desc">Submission ID</th>
          <th>Programme</th>
          <th>Count</th>
          <th>Minutes</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($organisations as $org => $value):?>
          <tr>
            <th><?php echo $value["name"];?></th>
            <th><?php echo $value["country"];?></th>
            <th></th>
            <th></th>
            <th><?php echo $value["count"];?></th>
            <th><?php echo $value["minutes"];?></th>
          </tr>
          <?php foreach ($value["entries"] as $key => $v):?>
            <tr>
              <td></td>
              <td><?php echo $v["data_id"];?></td>
              <td><?php echo $v["TitleInEnglish"];?></td>
              <td></td>
              <td><?php echo $v["DurationInMinutes"];?></td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <th colspan="3" style="text-align:right;">SUM</th>
            <th><?php echo $value["count"];?></th>
            <th><?php echo $value["minutes"];?></th>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <hr/>
    <h1>Single Organisations</h1>
    <?php foreach ($organisations as $org => $value):?>
      <h2><?php echo $value["name"];?> (<?php echo $value["country"];?>)<small><?php echo $value["count"];?>&nbsp;Entries with <?php echo $value["minutes"];?> minutes</small></h2>
      <table class="table table-striped table-hover sortable">
        <thead>
          <tr>
            <th data-defaultsort="desc">Submission ID</th>
            <th>Programme</th>
            <th>Minutes</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($value["entries"] as $key => $v):?>
            <tr>
              <td><?php echo $v["data_id"];?></td>
              <td><?php echo $v["TitleInEnglish"];?></td>
              <td><?php echo $v["DurationInMinutes"];?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th style="text-align:right;">SUM</th>
            <th><?php echo $value["count"];?></th>
            <th><?php echo $value["minutes"];?></th>
          </tr>
        </tfoot>
      </table>
      <hr/>
    <?php endforeach; ?>



    <h1>Organisations List</h1>
    <table class="table table-striped table-hover sortable">
      <thead>
        <tr>
          <th data-defaultsort="desc">Name</th>
          <th>Country</th>
          <th>Programme</th>
          <th>Count</th>
          <th>Minutes</th>
        </tr>
        <tbody>
          <?php foreach ($organisations as $org => $value):?>
            <tr>
              <td><?php echo $value["name"];?></td>
              <td><?php echo $value["country"];?></td>
              <td><?php echo $value["count"];?></td>
              <td><?php echo $value["minutes"];?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th style="text-align:right;">SUM</th>
            <th><?php echo $sum_count;?></th>
            <th><?php echo $sum_minutes;?></th>
          </tr>
        </tfoot>
      </thead>
    </table>
  </div>
  <script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/moment.min.js"></script>
  <script src="js/bootstrap-sortable.js"></script>
</body>
</html>
<?php endif; ?>