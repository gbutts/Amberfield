<?php
// GRB My attempt at dropdown.inc.php
// global line added after assistance from the 'phorum'.

function dropdown($a, $b, $c, $d) {
 global $dbc, $r;
  $q = "SELECT $a, $b FROM $c ORDER BY $d ASC";
  $r = mysqli_query ($dbc, $q);
     while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
     echo "<option value=\"$row[0]\">$row[1]</option>\n";
     }
mysqli_free_result($r);
}

// Used for rollcall.php to sort dropdown by gender and not 'calf'
function dropdown2($a, $b, $c, $d) {
 global $dbc, $r;
//  $q = "SELECT $a, $b FROM $c WHERE sex LIKE '$d' ORDER BY $b ASC";
  $q = "SELECT $a, $b FROM $c WHERE sex LIKE '$d' && role_id != 4 ORDER BY $b ASC";
  $r = mysqli_query ($dbc, $q);
     while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
     echo "<option value=\"$row[0]\">$row[1]</option>\n";
     }
mysqli_free_result($r);
}

?>