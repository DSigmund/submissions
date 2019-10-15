<?php
function unescape($input) {
  $output = str_replace("\\", "", $input);
  $output =  html_entity_decode($output);
  $output = str_replace("&quot;", "\"", $output);
  return $output;
}
?>