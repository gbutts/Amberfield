<?php 
// GRB Leeched from:
// http://forums.mysql.com/read.php?52,211014,211014
//
//  Function:   conv_num 
//  Arguments:  int 
//  Returns:    string 
//  Description: 
//      Converts a given integer (in range [0..1T-1], inclusive) into 
//      alphabetical format ("one", "two", etc).

// Returns with Capitals. Use 'strtolower(conv_num($foo))' for lower case. 
 

function conv_num($number) { 
    if (($number < 0) || ($number > 999999999)) { 
		return "$number"; 
	} 

    $Gn = floor($number / 1000000);  // Millions (giga) 
    $number -= $Gn * 1000000; 
    $kn = floor($number / 1000);     // Thousands (kilo) 
    $number -= $kn * 1000; 
    $Hn = floor($number / 100);      // Hundreds (hecto) 
    $number -= $Hn * 100; 
    $Dn = floor($number / 10);       // Tens (deca) 
    $n = $number % 10;               // Ones 

    $res = ""; 

    if ($Gn) { 
		$res .= convert_number($Gn) . " Million"; 
	} 

    if ($kn) { 
		$res .= (empty($res) ? "" : " ") . 
			conv_num($kn) . " Thousand"; 
	} 

    if ($Hn) { 
		$res .= (empty($res) ? "" : " ") . 
			conv_num($Hn) . " Hundred"; 
	} 

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", 
        "Nineteen"); 
    $tens = array("", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", 
        "Seventy", "Eighty", "Ninety"); 

    if ($Dn || $n) { 
		if (!empty($res)) { 
			$res .= " and "; 
		} 

		if ($Dn < 2) { 
			$res .= $ones[$Dn * 10 + $n]; 
		} else { 
			$res .= $tens[$Dn]; 

			if ($n) { 
				$res .= "-" . $ones[$n]; 
			} 
		} 
	} 

    if (empty($res)) { 
		$res = "Zero"; 
	} 

    return $res; 
} 

?> 