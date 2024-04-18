	<?php

	//$id = 2353; //old
//$id = 2737; // current
$id = 2764; //latecomer

	$delimeter = "|";

	ini_set("memory_limit","1024M");

	// Create CSV for Form by ID

	header("Content-type: text/plain; charset=utf-8");
	//header("Content-Disposition: attachment; filename=dsgvo_export.csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	// 0. require config and connect to database
	require_once('config.php');
	require_once('functions.php');
	$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$mysql->query("SET NAMES utf8"); 
	if ($mysql->connect_error) {
	  die("Connection failed: " . $mysql->connect_error);
	} 

	$sql = "SELECT data_id, name, value FROM Td6PNmU6_cf7_vdata_entry WHERE cf7_id=".$id." AND name NOT LIKE \"\\_%\" ORDER BY data_id ASC, name ASC";

	$result = $mysql->query($sql);

	$entries = array();
	if ($result->num_rows > 0) {
	  while($row = $result->fetch_assoc()) {
		// 1. Fill Named array with fields
		$entries[$row["data_id"]]["data_id"] = $row["data_id"];
		if($row["name"] != "g-recaptcha-response" && $row["name"] != "dsgvo") {
		  $entries[$row["data_id"]][$row["name"]] = trim(preg_replace("~[\r\n]~", " ",$row["value"]));
		}
	  }
	}
	$mysql->close();

	$output = "";
	$output .= "data_id".$delimeter;
    $output .= "family-name".$delimeter;
    $output .= "first-name".$delimeter;
    $output .= "contact-email".$delimeter;
    $output .= "arrival_date".$delimeter;
    $output .= "departure_date".$delimeter;
    $output .= "participation-type".$delimeter;
	$output .= "participate-days".$delimeter;
    $output .= "city".$delimeter;
    $output .= "country".$delimeter;
	$output .= "department".$delimeter;
    $output .= "job-title".$delimeter;
    $output .= "organisation".$delimeter;
	$output .= "involved".$delimeter;
    $output .= "phone".$delimeter;
    $output .= "submit_time".$delimeter;
	$output .= "participation".$delimeter;
    $output .= "voting".$delimeter;  
	$output .= "\n";

	

	// 3. create lines with values
	foreach ($entries as $entry => $value) {
	  $Line = "";
	  $Line .= unescape($value["data_id"]).$delimeter;
	  $Line .= unescape($value["family-name"]).$delimeter;
	  $Line .= unescape($value["first-name"]).$delimeter;
	  $Line .= unescape($value["contact-email"]).$delimeter;
	  $Line .= unescape($value["arrival_date"]).$delimeter;
	  $Line .= unescape($value["departure_date"]).$delimeter;
	  $Line .= unescape($value["participation-type"]).$delimeter;
	  $Line .= unescape($value["participate-days"]).$delimeter;
	  $Line .= unescape($value["city"]).$delimeter;
	  $Line .= unescape($value["country"]).$delimeter;
	  $Line .= unescape($value["department"]).$delimeter;
	  $Line .= unescape($value["job-title"]).$delimeter;
	  $Line .= unescape($value["organisation"]).$delimeter;
	  $Line .= unescape($value["involved"]).$delimeter;
	  $Line .= unescape($value["phone"]).$delimeter;
	  $Line .= unescape($value["submit_time"]).$delimeter;
	  $Line .= unescape($value["participation"]).$delimeter;
	  $Line .= unescape($value["voting"]).$delimeter;
	  //$Line .= "https://submissions.prixjeunesse.de/submit.php?data=".base64_encode($Line);
	  $output .= $Line; //Zeile Anhängen
	  $output .= "\n";
	}
	// 4. echo values
	die($output);
	//Dummy: 2682|Grebe|Nicolas|Grebe@uberrider.de||||Munich|Germany||CEO|EGT-Kommunikatiostechnik UG||+4915770351080|2022-01-29 18:33:28|Observer||MjY4MnxHcmViZXxOaWNvbGFzfEdyZWJlQHViZXJyaWRlci5kZXx8fHxNdW5pY2h8R2VybWFueXx8Q0VPfEVHVC1Lb21tdW5pa2F0aW9zdGVjaG5payBVR3x8KzQ5MTU3NzAzNTEwODB8MjAyMi0wMS0yOSAxODozMzoyOHxPYnNlcnZlcnx8

	// 5. die
	//die();
	?>