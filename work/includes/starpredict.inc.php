<?php
// GRB starpredict.inc.php

// Determine most likely case from two GeneStar variables.
function lcase($sb, $db) {

  switch ($sb) {

    // Sire GeneStar is zero
    case 0:
      if ($db == '0') { return 0;
         } else {
            return 1;
         }
      break;

    // Sire GeneStar is one
    case 1:
      if ($db == '0') { return 0;
         } elseif ($db == '1') {
            return 1;
         } else {
            return 2;
         }
      break;

    // Sire GeneStar is two
    case 2:
      if ($db == '0') { return 1;
         } elseif ($db == '1') {
            return 1;
         } else {
            return 2;
         }
      break;
  }
}

// Determine best case from two GeneStar variables.
function bcase($sb, $db) {

  switch ($sb) {

    // Sire GeneStar is zero
    case 0:
      if ($db == '0') { return 0;
         } else {
            return 1;
         }
      break;

    // Sire GeneStar is one or two
    case 1:
    case 2:
      if ($db == '0') { return 1;
         } else {
            return 2;
         }
      break;
  }
}

// Determines worst case from two GeneStar variables.
function wcase($sb, $db) {

  switch ($sb) {

    // Sire GeneStar is two
    case 2:
      if ($db == '2') { return 2;
         } else {
            return 1;
         }
      break;

    // Sire GeneStar is one or zero
    case 1:
    case 0:
      if ($db == '2') { return 1;
         } else {
            return 0;
         }
      break;
  }
}
?>