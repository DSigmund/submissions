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
  $enterer = $fields["company-producing"];
} else {
  $enterer = $fields["name-telecaster"];
}

?>
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title><?php echo $id;?>_<?php echo $fields["title-in-english"]; ?>_<?php echo $enterer; ?></title>
    <style>
      th {width:200px;}
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-8">
          <h2><?php echo $id;?>_<?php echo $fields["title-in-english"]; ?>_<?php echo $enterer; ?></h2>
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
              <td><?php echo $fields["category"]; ?></td>
            </tr>
          <?php endif; ?>
          <tr>
            <th>Age of Audience</th>
            <td><?php echo $fields["age-of-audience"]; ?></td>
          </tr>
          <tr>
            <th>Title In English</th>
            <td><?php echo $fields["title-in-english"]; ?></td>
          </tr>
          <tr>
            <th>Original Title</th>
            <td><?php echo $fields["original-title"]; ?></td>
          </tr>
          <tr>
            <th>Producer</th>
            <td><?php echo $fields["producer"]; ?></td>
          </tr>
          <tr>
            <th>Director</th>
            <td><?php echo $fields["director"]; ?></td>
          </tr>
          <tr>
            <th>Duration in Minutes </th>
            <td><?php echo $fields["duration-in-minutes"]; ?></td>
          </tr>
          <?php if($formid == CAT_MAIN_TV): ?>
            <tr>
              <th>Datetime First Telecast</th>
              <td><?php echo $fields["date-of-first-telecast"] . " " . $fields["time-of-first-telecast"]; ?></td>
            </tr>
            <tr>
              <th>Episode</th>
              <td><b><?php echo $fields["episode-no"] . "</b>/" . $fields["episode-series"] . " (" . $fields["how-often"] . ")"; ?></td>
            </tr>
          <?php endif; ?>
          <?php if($formid == CAT_SHORTS): ?>
            <tr>
              <th>Date of First Telecast</th>
              <td><?php echo $fields["date-of-first-telecast"]; ?></td>
            </tr>
            <tr>
              <th>Episode</th>
              <td><b><?php echo $fields["episode-no"] . "</b>/" . $fields["episode-series"] . " (" . $fields["how-often"] . ")"; ?></td>
            </tr>
          <?php endif; ?>
          <tr>
            <th>Entered By</th>
            <td><?php echo $fields["entered-by"]; ?></td>
          </tr>
          <tr>
            <th>Name Telecaster</th>
            <td><?php echo $fields["name-telecaster"]; ?></td>
          </tr>
          <tr>
            <th>Country Telecaster</th>
            <td><?php echo $fields["country-telecaster"]; ?></td>
          </tr>
          <tr>
            <th>Contact Telecaster</th>
            <td><?php echo $fields["contact-telecaster-first-name"]; ?> <?php echo $fields["contact-telecaster-last-name"]; ?></td>
          </tr>
          <tr>
            <th>Email Telecaster</th>
            <td><?php echo $fields["contact-telecaster-email"]; ?></td>
          </tr>
          <tr>
            <th>Company Producing</th>
            <td><?php echo $fields["name-producing-company"]; ?></td>
          </tr>
          <tr>
            <th>Country Producing </th>
            <td><?php echo $fields["country-producing-company"]; ?></td>
          </tr>
          <tr>
            <th>Contact Producing</th>
            <td><?php echo $fields["contact-first-name"]; ?> <?php echo $fields["contact-last-name"]; ?></td>
          </tr>
          <tr>
            <th>Email Producing </th>
            <td><?php echo $fields["contact-email"]; ?></td>
          </tr>
          <tr>
            <th>Brief Summary</th>
            <td><?php echo $fields["summary"]; ?></td>
          </tr>
          <?php if($formid == CAT_MAIN_TV): ?>
            <tr>
              <th>Info About Programme </th>
              <td><?php echo $fields["info"]; ?></td>
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