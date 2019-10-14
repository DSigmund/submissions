<?php
function unescape($input) {
  $output = html_entity_decode($input);
  $output = str_replace("\\", "", $output);
  return $output;
}
?>