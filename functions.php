<?php
function unescape($input) {
  $output = html_entity_decode($input);
  return $output;
}
?>