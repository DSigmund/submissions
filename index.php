<?php
require_once('config.php');
?>
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>PJ <?php echo PJ_YEAR;?> - Entries</title>
  </head>
  <body>
    <div class="container">
      <h1>PJ <?php echo PJ_YEAR;?> - Entries</h1>
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th> 
            <th>Form</th>
            <th>Submissions</th>
            <th>Excel-File</th>
            <th>Entering Organisations</th>
            <th>Excel-File</th>
          </tr>
          <tbody>
            <tr>
              <th><?php echo CAT_MAIN_TV;?></th>
              <td>Main TV Categories Entry Form</td>
              <td><a href="submissions.php?id=<?php echo CAT_MAIN_TV;?>" target="_blank">Submissions</a></td>
              <td><a class="" href="xls/pj<?php echo PJ_YEAR;?>_main_tv_categories.xlsx">Download Submissions Excel</a></td>
              <td><a href="entering_organisations.php?id=<?php echo CAT_MAIN_TV;?>&type=html" target="_blank">Entering Organisations</a></td>
              <td><a class="" href="xla/PJ<?php echo PJ_YEAR;?>_entering_organisations_main_tv.xlsx">Download EO Excel</a></td>
            </tr>
            <tr>
              <th><?php echo CAT_SHORTS;?></th>
              <td>Shorts Entry Form</td>
              <td><a href="submissions.php?id=<?php echo CAT_SHORTS;?>" target="_blank">Submissions</a></td>
              <td></td>
              <td><a href="entering_organisations.php?id=<?php echo CAT_SHORTS;?>&type=html" target="_blank">Entering Organisations</a></td>
              <td></td>
            </tr>
            <tr>
              <th><?php echo CAT_INTERACTIVITY;?></th>
              <td>Interactivity Entry Form</td>
              <td><a href="submissions.php?id=<?php echo CAT_INTERACTIVITY;?>" target="_blank">Submissions</a></td>
              <td></td>
              <td><a href="entering_organisations.php?id=<?php echo CAT_INTERACTIVITY;?>&type=html" target="_blank">Entering Organisations</a></td>
              <td></td>
            </tr>
            <tr>
              <th><?php echo CAT_PERSONAL;?></th>
              <td>Personal Registration Form</td>
              <td><a href="submissions.php?id=<?php echo CAT_PERSONAL;?>" target="_blank">Submissions</a></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </thead>
      </table>
      <hr/>
      <h2>Anleitung für Excel-Nutzung</h2>
      <h3>Einrichtung</h3>
      <ol>
        <li>Excel-Datei öffnen</li>
        <li>Daten -> Aus anderen Quellen -> XML-Datei Import</li>
        <li>URL: https://prixjeunesse.de/submissions/entries.php?id=[ID] (ID=siehe oben)</li>
        <lI>fertig</lI>
      </ol>
      <h3>Update der Daten</h3>
      <ol>
        <li>Daten -> aktualisieren</li>
      </ol>
      <h3>Sortierte Daten</h3>
      <ol>
        <li>Wie "Einrichtung"</li>
        <li>Ausnahme: Link ist: https://prixjeunesse.de/submissions/entries_sorted.php?id=12&status=3&category=7_-_10_Non_Fiction
          <ul>
            <li>Status: Wie Bearbeitungsstatus, aber nur die Nummer</li>
            <li>Category: Wie Angegeben, Nur "_" statt Leerzeichen</li>
          </ul>
        </li>
      </ol>
    </div>
  </body>
</html>