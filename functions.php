<?php
function unescape($input) {
  $output = str_replace("\\", "", $input);
  $output = str_replace("’", "\'", $output);
  $output =  html_entity_decode($output);
  $output = str_replace("&quot;", "\"", $output);
  $output = str_replace("&amp;", "&", $output);
  $output = str_replace("\'", "'", $output);
  return $output;
}
function excelNumber($input) {
  return str_replace(".", ",", $input);
}
function cat2Sort($category) {
  switch($category) {
    case "Up to 6 Years Fiction": return 0;
    case "Up to 6 Years Non-Fiction": return 1;
    case "7 - 10 Years Fiction": return 2;
    case "7 - 10 Years Non-Fiction": return 3;
    case "11 - 15 Years Fiction": return 4;
    case "11 - 15 Years Non-Fiction": return 5;
    default: return -1;
  }
}
function status2Sort($status) {
  return $status[0];
}
function num2Sort($num) {
  // IV-1
  $parts = explode("-", $num);
  $front = 0;
  switch($parts[0]) {
    case "I": $front =  1;break;
    case "II": $front =  2;break;
    case "III": $front =  3;break;
    case "IV": $front =  4;break;
    case "V": $front =  5;break;
    case "VI": $front =  6;break;
    default: $front =  0;
  }
  return ($front * 1000) + $parts[1];
}
?>