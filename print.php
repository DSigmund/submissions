<?php
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$formid = isset($_GET["formid"]) ? intval($_GET["formid"]) : 0;

require_once('config.php');
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->set_charset("utf8"); 

if ($mysql->connect_error) {
  die("Connection failed: " . $mysql->connect_error);
}

$stmt = $mysql->prepare("SELECT post_title FROM Td6PNmU6_posts WHERE post_type = 'wpcf7_contact_form' AND id = ?");
$stmt->bind_param("i", $formid);
$stmt->execute();
$result0 = $stmt->get_result();
$title = $formid . " - No title";

if ($row0 = $result0->fetch_assoc()) {
    $title = $formid . " - " . $row0["post_title"];
}

$stmt = $mysql->prepare("SELECT name, value FROM Td6PNmU6_cf7_vdata_entry WHERE data_id = ? AND name NOT LIKE '\\_%' ORDER BY name ASC");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$fields = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $fields[$row["name"]] = htmlspecialchars($row["value"]);
  }
} else {
  die("No Results...");
}

$enterer = "Unknown";
if (isset($fields["EnteredBy"]) && $fields["EnteredBy"] == "Producing Organisation" && isset($fields["company-producing"])) {
  $enterer = $fields["company-producing"];
} elseif (isset($fields["name-telecaster"])) {
  $enterer = $fields["name-telecaster"];
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title><?php echo htmlspecialchars($id . "_" . $fields["title-in-english"] . "_" . $enterer); ?></title>
    <style>
      th { width: 200px; }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-8">
          <h2><?php echo htmlspecialchars($id . "_" . $fields["title-in-english"] . "_" . $enterer); ?></h2>
        </div>
        <div class="col-4">
          <p class="alert alert-info text-right"><?php echo $title; ?></p>
        </div>
      </div>
      <table class="table">
        <?php foreach ($fields as $key => $value): ?>
        <tr>
          <th><?php echo htmlspecialchars($key); ?></th>
          <td><?php echo htmlspecialchars($value); ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </body>
</html>
