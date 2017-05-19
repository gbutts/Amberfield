<?php
// GRB  get_age3.inc.php
// Determine age, in years for those older than 12 months,
// and months for those under
// Now copes with a Dec-Jan split.

function GetAge($DOB, $DOD) {

	// Get current date
	$CD = date("Y-n-d");
	list($cY,$cm,$cd) = explode("-",$CD);
	
	// Get date of birth
	list($bY,$bm,$bd) = explode("-",$DOB);
	// is there a date of death?
	
	if ($DOD!="" && $DOD != "0000-00-00") {

	// Animal is dead
		list($dY,$dm,$dd) = explode("-",$DOD);
			if ($bY == $dY) {
     			$months = $dm - $bm;
	     		if ($months == 0 || $months > 1) {
	     			return "$months months";
	     		} else
	    			return "$months month";
			} else 
   				$years = ( $dm.$dd < $bm.$bd ? $dY-$bY-1 : $dY-$bY );
	     		if ($years == 0 || $years > 1) {
	     			return "$years years";
				} else { 
	    			return "$years year";
				}

	} else {

	// Animal is alive
		if ($bY != "" && $bY != "0000") {	

	     	if ($bY == $cY) {
				// Birth year is current year
	     		$months = $cm - $bm;
		     		if ($months == 0 || $months > 1) {
		     			return "$months months";
		     		} else 
		    			return "$months month";
			} else if ($cY - $bY == 1 && $cm - $bm < 12) {
				// Born within 12 months, either side of 01 Jan
					//Determine days and therefore proportion of month
					if ($cd - $bd > 0) {
						$xm = 0;
					} else { 
						$xm = 1;
					}
				$months = 12 - $bm + $cm - $xm;
		     		if ($months == 0 || $months > 1) {
		     			return "$months months";
		     		} else { 
		    			return "$months month";
					}
			} 

			// Animal older than 12 months, return in years
			$years = (date("md") < $bm.$bd ? date("Y")-$bY-1 : date("Y")-$bY );
     		if ($years == 0 || $years > 1) {
     			return "$years years";
			} else { 
    			return "$years year";
			}
			
		} else	
    	return "No Date of Birth!";
	}	
}
?>
