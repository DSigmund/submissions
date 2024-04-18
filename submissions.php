<?php
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;  // Sanitize and ensure $id is an integer

require_once('config.php');
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysql->set_charset("utf8");

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
} 

// Prepared statement to safely get the form name
$stmt = $mysql->prepare("SELECT post_title FROM Td6PNmU6_posts WHERE post_type = 'wpcf7_contact_form' AND id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result0 = $stmt->get_result();
$row0 = $result0->fetch_assoc();
$title = $id . " - " . ($row0 ? $row0["post_title"] : 'Unknown Title');

// Prepared statement to get entries
$sql = "SELECT e.data_id AS SubmissionId, v.created AS DateSubmitted, e.value AS Hint 
        FROM Td6PNmU6_cf7_vdata v
        JOIN Td6PNmU6_cf7_vdata_entry e ON e.data_id = v.id AND e.cf7_id = ?
        WHERE (e.name = 'title-in-english' OR e.name = 'entry-title' OR e.name = 'family-name')";
$stmt = $mysql->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
?>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-sortable.css">
    <title>PJ <?php echo defined('PJ_YEAR') ? PJ_YEAR : "Year Not Set"; ?> - Entries for Form <?php echo htmlspecialchars($title); ?></title>
</head>
<body>
<div class="container">
    <h1>PJ <?php echo defined('PJ_YEAR') ? PJ_YEAR : "Year Not Set"; ?> - Entries for Form <?php echo htmlspecialchars($title); ?></h1>
    <table class="table table-striped table-hover sortable">
        <thead>
        <tr>
            <th>Submission ID</th>
            <th data-defaultsort="desc" data-dateformat="YYYY-MM-DD HH:mm:ss">Date Submitted</th>
            <th>Title</th>
            <th data-defaultsort='disabled'>*</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td data-value="<?php echo $row["SubmissionId"]; ?>"><strong><?php echo $row["SubmissionId"]; ?></strong></td>
                    <td><?php echo $row["DateSubmitted"]; ?></td>
                    <td><?php echo htmlspecialchars($row["Hint"]); ?></td>
                    <td><a href="<?php echo $id == CAT_INTERACTIVITY ? "print_interactive.php?id={$row["SubmissionId"]}" : "print.php?id={$row["SubmissionId"]}&formid={$id}"; ?>" target="_blank">Printer</a></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No Entries Found</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/moment.min.js"></script>
<script src="js/bootstrap-sortable.js"></script>
</body>
</html>
