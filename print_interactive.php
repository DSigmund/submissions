<?php
// Show one nice entry for interactive
?>
<?php
$id = $_GET["id"];
$formid = "15";
require_once('config.php');
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->query("SET NAMES utf8"); 
if ($mysql->connect_error) {
  die("Connection failed: " . $mysql->connect_error);
} 
$result0 = $mysql->query("SELECT post_title FROM Td6PNmU6_posts WHERE post_type=\"wpcf7_contact_form\" AND id=".$formid);
$row0 = $result0->fetch_assoc();
$title = $formid." - ".$row0["post_title"];

$sql = "SELECT name, value FROM Td6PNmU6_cf7_vdata_entry WHERE data_id=".$id." AND name NOT LIKE \"\_%\" ORDER BY name ASC";
$result = $mysql->query($sql);

$fields = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $fields[$row["name"]] = $row["value"];
  }
} else {
  die("No Results...");
}
$enterer = "";
if($fields["EnteredBy"] == "Producing Organisation") {
  $enterer = $fields["CompanyProducing"] . $fields["CompanyProducing copy"];
} else {
  $enterer = $fields["NameTelecaster"];
}

?>
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title><?php echo $id;?>: <?php echo $fields["EntryTitle"]; ?></title>
    <style>
      th {width:200px;}
      @media print {
        td {
          font-size: 12pt;
          /* These are technically the same, but use both */
          overflow-wrap: break-word;
          word-wrap: break-word;

          -ms-word-break: break-all;
          /* This is the dangerous one in WebKit, as it breaks things wherever */
          word-break: break-all;
          /* Instead use this non-standard one: */
          word-break: break-word;

          /* Adds a hyphen where the word breaks, if supported (No Blink) */
          -ms-hyphens: auto;
          -moz-hyphens: auto;
          -webkit-hyphens: auto;
          hyphens: auto;
        }
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-8">
          <h2><?php echo $id;?>: <?php echo $fields["EntryTitle"]; ?></h2>
        </div>
        <div class="col-4">
          <p class="alert alert-info text-right"><?php echo $title; ?></p>
        </div>
      </div>
      <table class="table">
        <tr>
          <th>Entry-related URLs, apps etc</th>
          <td><?php echo $fields["Entry_related_URLs_etc"]; ?></td>
        </tr>
        <tr>
          <th>Specific age-group targeted</th>
          <td><?php echo $fields["SpecificAgeOfAudience"]; ?></td>
        </tr>
        <tr>
          <th>Related children's TV programme</th>
          <td><?php echo $fields["Related_Programmes_Series"]; ?></td>
        </tr>
        <tr>
          <th>Entering Organisation Company Name</th>
          <td><?php echo $fields["CompanyEntering"]; ?></td>
        </tr>
        <tr>
          <th>City</th>
          <td><?php echo $fields["CityEntering"]; ?></td>
        </tr>
        <tr>
          <th>Country</th>
          <td><?php echo $fields["CountryEntering"]; ?></td>
        </tr>
        <tr>
          <th>Contact First Name</th>
          <td><?php echo $fields["FirstNameEntering"]; ?></td>
        </tr>
        <tr>
          <th>Contact Family Name</th>
          <td><?php echo $fields["LastNameEnteringEntering"]; ?></td>
        </tr>
        <tr>
          <th>Email</th>
          <td><?php echo $fields["emailEntering"]; ?></td>
        </tr>
        <tr>
          <th>Telecaster of related programme/Company name</th>
          <td><?php echo $fields["CompanyTelecaster"]; ?></td>
        </tr>
        <tr>
          <th>City</th>
          <td><?php echo $fields["City_Telecaster"]; ?></td>
        </tr>
        <tr>
          <th>Country</th>
          <td><?php echo $fields["Country_Telecaster"]; ?></td>
        </tr>
        <tr>
          <th>Contact First Name</th>
          <td><?php echo $fields["FirstNameTelecaster"]; ?></td>
        </tr>
        <tr>
          <th>Contact Family Name</th>
          <td><?php echo $fields["LastNameTelecaster"]; ?></td>
        </tr>
        <tr>
          <th>Email</th>
          <td><?php echo $fields["email_Telecaster"]; ?></td>
        </tr>
        <tr>
          <th>Infos for preselection purposes</th>
          <td><?php echo str_replace("\n", "<br>", $fields["InfoAboutEntry"]); ?></td>
        </tr>
        <tr>
          <th>Language of submission/presentation</th>
          <td><?php echo $fields["HowSubmitted"]; ?></td>
        </tr>
        <tr>
          <th>Summary of main features</th>
          <td><?php echo str_replace("\n", "<br>", $fields["SummaryEntry"]); ?></td>
        </tr>
        <tr>
          <th>Info on distribution and audience</th>
          <td><?php echo str_replace("\n", "<br>", $fields["DistributionEntry"]); ?></td>
        </tr>
        <tr>
          <th>Info on target age and interaction</th>
          <td><?php echo str_replace("\n", "<br>", $fields["TargetAge_Entry"]); ?></td>
        </tr>
        <tr>
          <th>Info on objectives</th>
          <td><?php echo str_replace("\n", "<br>", $fields["Objectives_Entry"]); ?></td>
        </tr>
        <tr>
          <th>Info on finalist presentation:</th>
          <td><?php echo str_replace("\n", "<br>", $fields["Finalist_Entry"]); ?></td>
        </tr>
      </table>
    </div>
  </body>
</html>