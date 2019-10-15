<?php
function unescape($input) {
  $output = str_replace("\\", "", $input);
  return html_entity_decode($output);
}
?>