<?php
function unescape($input) {
  $output = html_entity_decode($input);
  $output = preg_replace("\\", "",output);
  return $output;
}
?>