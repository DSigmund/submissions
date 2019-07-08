<?php
// Show one nice entry
?>
<?php
$id = $_GET["id"];
$formid = $_GET["formid"];
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
    <title><?php echo $id;?>_<?php echo $fields["TitleInEnglish"]; ?>_<?php echo $enterer; ?></title>
    <style>
      th {width:200px;}
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-8">
          <h2><?php echo $id;?>_<?php echo $fields["TitleInEnglish"]; ?>_<?php echo $enterer; ?></h2>
        </div>
        <div class="col-4">
          <p class="alert alert-info text-right"><?php echo $title; ?></p>
        </div>
      </div>
      <table class="table">
        <?php if($formid == CAT_MAIN_TV || $formid == CAT_SHORTS): ?>
          <?php if($formid == CAT_MAIN_TV): ?>
            <tr>
              <th>Category</th>
              <td><?php echo $fields["Category"]; ?></td>
            </tr>
          <?php endif; ?>
          <tr>
            <th>Age of Audience</th>
            <td><?php echo $fields["AgeOfAudience"]; ?></td>
          </tr>
          <tr>
            <th>Title In English</th>
            <td><?php echo $fields["TitleInEnglish"]; ?></td>
          </tr>
          <tr>
            <th>Original Title</th>
            <td><?php echo $fields["OriginalTitle"]; ?></td>
          </tr>
          <tr>
            <th>Producer</th>
            <td><?php echo $fields["Producer"]; ?></td>
          </tr>
          <tr>
            <th>Director</th>
            <td><?php echo $fields["Director"]; ?></td>
          </tr>
          <tr>
            <th>Duration in Minutes </th>
            <td><?php echo $fields["DurationInMinutes"]; ?></td>
          </tr>
          <?php if($formid == CAT_MAIN_TV): ?>
            <tr>
              <th>Datetime First Telecast</th>
              <td><?php echo $fields["DateOfFirstTelecast"] . " " . $fields["TimeOfFirstTelecast"]; ?></td>
            </tr>
            <tr>
              <th>Episode</th>
              <td><b><?php echo $fields["EpisodeNo"] . "</b>/" . $fields["SeriesEpisodes"] . " (" . $fields["HowOften"] . ")"; ?></td>
            </tr>
          <?php endif; ?>
          <?php if($formid == CAT_SHORTS): ?>
            <tr>
              <th>Date of First Telecast</th>
              <td><?php echo $fields["DateOfFirstTelecast2"]; ?></td>
            </tr>
            <tr>
              <th>Episode</th>
              <td><b><?php echo $fields["EpisodeNo"] . "</b>/" . $fields["SeriesEpisodes"] . " (" . $fields["HowOften"] . ")"; ?></td>
            </tr>
          <?php endif; ?>
          <tr>
            <th>Entered By</th>
            <td><?php echo $fields["EnteredBy"]; ?></td>
          </tr>
          <tr>
            <th>Name Telecaster</th>
            <td><?php echo $fields["NameTelecaster"]; ?></td>
          </tr>
          <tr>
            <th>Country Telecaster</th>
            <td><?php echo $fields["Country_Telecaster"]; ?></td>
          </tr>
          <tr>
            <th>Contact Telecaster</th>
            <td><?php echo $fields["FirstNameTelecaster"]; ?> <?php echo $fields["LastNameTelecaster"]; ?></td>
          </tr>
          <tr>
            <th>Email Telecaster</th>
            <td><?php echo $fields["email_Telecaster"]; ?></td>
          </tr>
          <tr>
            <th>Company Producing</th>
            <td><?php echo $fields["CompanyProducing"] . $fields["CompanyProducing copy"]; ?></td>
          </tr>
          <tr>
            <th>Country Producing </th>
            <td><?php echo $fields["CountryProducing"] . $fields["CountryProducing copy"]; ?></td>
          </tr>
          <tr>
            <th>Contact Producing</th>
            <td><?php echo $fields["FirstNameProducing"] . $fields["FirstNameProducing copy"]; ?> <?php echo $fields["LastNameProducing"] . $fields["LastNameProducing copy"]; ?></td>
          </tr>
          <tr>
            <th>Email Producing </th>
            <td><?php echo $fields["emailProducing"] . $fields["emailProducing copy"]; ?></td>
          </tr>
          <tr>
            <th>Brief Summary</th>
            <td><?php echo $fields["BriefSummary"]; ?></td>
          </tr>
          <?php if($formid == CAT_MAIN_TV): ?>
            <tr>
              <th>Info About Programme </th>
              <td><?php echo $fields["InfoAboutProgramme"]; ?></td>
            </tr>
          <?php endif; ?>
        <?php else: ?>
          <?php foreach ($fields as $key => $value):?>
            <tr>
              <th><?php echo $key; ?></th>
              <td><?php echo $value; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </table>
    </div>
  </body>
</html>