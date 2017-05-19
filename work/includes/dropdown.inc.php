<?php
// GRB  dropdown.inc.php
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

// Used for gstar_predict.php to sort dropdown by gender
function dropdown2($a, $b, $c, $d) {
 global $dbc, $r;
  $q = "SELECT $a, $b FROM $c WHERE sex LIKE '$d' ORDER BY $b ASC";
  $r = mysqli_query ($dbc, $q);
     while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
     echo "<option value=\"$row[0]\">$row[1]</option>\n";
     }
mysqli_free_result($r);
}

// Used for register.php to sort dropdown by gender and location
function dropdown6($a, $b, $c, $d) {
 global $dbc, $r;
  $q = "SELECT $a, $b FROM $c WHERE sex LIKE '$d' && here LIKE 'Y' ORDER BY $b ASC";
  $r = mysqli_query ($dbc, $q);
     while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
     echo "<option value=\"$row[0]\">$row[1]</option>\n";
     }
mysqli_free_result($r);
}

// Forms a dropdown of only active animals with optional gender
// Used in 'reg4s.php' & 'regcyc.php' ('%' for wildcard)
function dropdown3($a, $b, $c, $d, $e) {
 global $dbc, $r;
  $q = "SELECT $a, $b, $c 
		FROM animal, roles 
		WHERE animal.role_id = roles.role_id 
		AND roles.role_id NOT LIKE '3' 
		AND roles.role_id NOT LIKE '5'
		AND roles.role_id NOT LIKE '6'
		AND sex = '$e'
		ORDER BY $d ASC";
  $r = mysqli_query ($dbc, $q);
     while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
     echo "<option value=\"$row[0]\">$row[1]</option>\n";
     }
mysqli_free_result($r);
}

// Forms a dropdown of active sales
// used in 'upd4s.php'
function dropdown4($a, $b, $c, $d) {
 global $dbc, $r;
  $q = "SELECT $a, $b, $c 
		FROM animal, 4sale 
		WHERE animal.animal_id = 4sale.animal_id 
		ORDER BY $d ASC";
  $r = mysqli_query ($dbc, $q);
     while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
     echo "<option value=\"$row[0]\">$row[1]</option>\n";
     }
mysqli_free_result($r);
}

// Forms a dropdown of active cycle records
// used in 'upcyc.php'
function dropdown5($a, $b, $c, $d) {
 global $dbc, $r;
  $q = "SELECT $a, $b, $c 
		FROM animal, cycle 
		WHERE animal.animal_id = cycle.animal_id 
		ORDER BY $d ASC";
  $r = mysqli_query ($dbc, $q);
     while ($row = mysqli_fetch_array ($r, MYSQLI_NUM)) {
     echo "<option value=\"$row[0]\">$row[1]</option>\n";
     }
mysqli_free_result($r);
}



// Used for Register a Champion
// See p94
// global line added after assistance from the 'phorum'.
function grbyear() {
 global $dbc, $r;
// Make the years pull-down menu:
	echo '<select name="year">';
	for ($year = 2000; $year <= 2010; $year++) {
		echo "<option value=\"$year\">$year</option>\n";
	}
	echo '</select>';
}



?>