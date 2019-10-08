<?php
// Show table of entries for form by ID
$id = $_GET["id"];
require_once('config.php');
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->query("SET NAMES utf8"); 
if ($mysql->connect_error) {
  die("Connection failed: " . $mysql->connect_error);
} 

// get formname
$result0 = $mysql->query("SELECT post_title FROM Td6PNmU6_posts WHERE post_type=\"wpcf7_contact_form\" AND id=".$id);
$row0 = $result0->fetch_assoc();
$title = $id." - ".$row0["post_title"];
$sql = "SELECT e.data_id AS SubmissionId, v.created AS DateSubmitted, e.value AS Hint 
        FROM Td6PNmU6_cf7_vdata v, Td6PNmU6_cf7_vdata_entry e 
        WHERE e.cf7_id=".$id." 
        AND e.data_id = v.id 
        AND (e.name = 'title-in-english' OR e.name = 'entry-title' OR e.name = 'family-name')";

$result = $mysql->query($sql);
?>
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-sortable.css">
    <title>PJ <?php echo PJ_YEAR;?> - Entries for Form <?php echo $title; ?></title>
  </head>
  <body>
    <div class="container">
      <h1>PJ <?php echo PJ_YEAR;?> - Entries for Form <?php echo $title; ?></h1>
      <table class="table table-striped table-hover sortable">
        <thead>
          <tr>
            <th>Submission ID</th>
            <th data-defaultsort="desc" data-dateformat="YYYY-MM-DD HH:mm:ss">Date Submitted</th>
            <th>Title</th>
            <th data-defaultsort='disabled'>*</th>
          </tr>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                  <td data-value="<?php echo $row["SubmissionId"];?>"><strong><?php echo $row["SubmissionId"];?></strong></td>
                  <td><?php echo $row["DateSubmitted"];?></td>
                  <td><?php echo $row["Hint"];?></td>
                  <?php if($id==CAT_INTERACTIVITY): ?>
                    <td><a href="print_interactive.php?id=<?php echo $row["SubmissionId"];?>" target="_blank">Printer</a></td>
                  <?php else:?>
                    <td><a href="print.php?id=<?php echo $row["SubmissionId"];?>&formid=<?php echo $id;?>" target="_blank">Printer</a></td>
                  <?php endif;?>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="4">Keine Einträge</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </thead>
      </table>
    </div>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/bootstrap-sortable.js"></script>
  </body>
</html>