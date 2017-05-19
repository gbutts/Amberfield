<?php # Script 8.5 - studchamps2.php
// rollcall2.php

    $pname = 'Roll Call';
    $version  = 'v4.3.0'; // ALCA link fixed
	$vdate = date("d F Y", getlastmod());
    $page_title = 'Amberfield :: '.$pname;

    include ('includes/dropdown.inc.php');
	include ('work/includes/conv_num.inc.php');
    include ('includes/header.html');

	require_once ('cgi-bin/mysqli_connect.php'); // Connect to the db.

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize an error array. *This appears to work. *Don't touch.

	// Load the main variables.
		$yr = mysqli_real_escape_string($dbc, trim($_POST['year']));
		$sn = mysqli_real_escape_string($dbc, trim($_POST['since']));
		$an = mysqli_real_escape_string($dbc,trim($_POST['animal_id']));
		$mm = mysqli_real_escape_string($dbc,trim($_POST['sire_id']));
		$ff = mysqli_real_escape_string($dbc,trim($_POST['dam_id']));

	// Load the others.
		$sx = mysqli_real_escape_string($dbc, trim($_POST['sex']));
		$so = mysqli_real_escape_string($dbc, trim($_POST['own']));
		$am = mysqli_real_escape_string($dbc, trim($_POST['amf']));

    // Sort the checkbox
		($sn=="") ? $sn="N" : $sn="Y";
		($so=="") ? $so="%" : $so="Y";
		($am=="") ? $si="%" : $si="AMF";

	if (empty($errors)) { // If everything's OK.

//Results description
		//Since years
		IF ($yr=="%") { 
			$s = " ";
		} elseif ($sn == "Y") { 
		 	$s = "born since $yr"; 
		} else {
			$s = "born in $yr";
		}	
			
		//Sex	
		IF ($sx=="%") {
			$a="Animals"; 
		} elseif ($sx == "M") {
			$a="Bulls";
		} elseif ($sx == "F") {
			$a="Heifers and Dams";
		} else {
			$a="Steers";
		}
		
		//Progeny of
		IF ($mm !== "%" && $ff !== "%") {
			$q10 = "SELECT aname FROM animal WHERE animal_id LIKE '$mm'";
			$r10 = @mysqli_query ($dbc, $q10); // Run the query.
			$row = mysqli_fetch_array($r10, MYSQLI_ASSOC);
			$b = $row['aname'];
			$q11 = "SELECT aname FROM animal WHERE animal_id LIKE '$ff'";
			$r11 = @mysqli_query ($dbc, $q11); // Run the query.
			$row = mysqli_fetch_array($r11, MYSQLI_ASSOC);
			$d = $row['aname'];
			$p=" who are the progeny of $b and $d.";
			
		} elseif ($mm !== "%") {
			$q10 = "SELECT aname FROM animal WHERE animal_id LIKE '$mm'";
			$r10 = @mysqli_query ($dbc, $q10); // Run the query.
			$row = mysqli_fetch_array($r10, MYSQLI_ASSOC);
			$n = $row['aname'];
			$p=" who are the progeny of $n.";
		} elseif ($ff !== "%") {
			$q10 = "SELECT aname FROM animal WHERE animal_id LIKE '$ff'";
			$r10 = @mysqli_query ($dbc, $q10); // Run the query.
			$row = mysqli_fetch_array($r10, MYSQLI_ASSOC);
			$n = $row['aname'];
			$p=" who are the progeny of $n.";
		} else {
			$p = "";
		}	

		//Location
		IF ($so == "Y" && $si == "AMF") {
			$x = "Bred and owned by Amberfield.";
		} elseif ($so == "Y") {
			$x = "Owned by Amberfield.";
		} elseif ($si == "AMF") {
			$x = "Bred by Amberfield.";
		} else {
			$x = "";	
		}	

//End of results description

//Query for since
	IF ($sn == 'N') {

		// Build query for the specific year
 		//http://dev.mysql.com/doc/refman/5.5/en/date-and-time-functions.html#function_year
        $q9 = "SELECT sex, dob, role, 
        		animal_id AS aa, sbook AS sb,
				(SELECT aname FROM animal WHERE animal_id = 
					(SELECT sireo FROM animal WHERE animal_id LIKE aa
					)
				) AS dad,
				(SELECT aname FROM animal WHERE animal_id = 
					(SELECT damo FROM animal WHERE animal_id LIKE aa
					)
				) AS mum,
        		CONCAT(stud.sname,' ',animal.aname) AS name, 
				CONCAT(animal.stud_id,animal.tattoo) AS tatt
			FROM animal, stud, roles 
			WHERE
				animal.stud_id = stud.stud_id && 
				animal.role_id = roles.role_id && 
				YEAR(dob) LIKE '$yr' && 
				animal.animal_id LIKE '$an' && 
				animal.sireo LIKE '$mm' && 
				animal.damo LIKE '$ff' && 
				animal.sex LIKE '$sx' && 
				animal.here LIKE '$so' && 
				animal.stud_id LIKE '$si'
				ORDER BY dob ASC";

		// Run the query:
		$r9 = @mysqli_query ($dbc, $q9); // Run the query.

		// Count the number of returned rows:
		$records = mysqli_num_rows($r9);
		$rr = $r9; 			//Set the right query for table

	} else {

		// Build query for since the year
        $q8 = "SELECT sex, dob, role,
        		animal_id AS aa, sbook AS sb,
				(SELECT aname FROM animal WHERE animal_id = 
					(SELECT sireo FROM animal WHERE animal_id LIKE aa
					)
				) AS dad,
				(SELECT aname FROM animal WHERE animal_id = 
					(SELECT damo FROM animal WHERE animal_id LIKE aa
					)
				) AS mum,
        		CONCAT(stud.sname,' ',animal.aname) AS name, 
				CONCAT(animal.stud_id,animal.tattoo) AS tatt 
			FROM animal, stud, roles 
			WHERE 
				animal.stud_id = stud.stud_id && 
				animal.role_id = roles.role_id && 
				YEAR(dob) >= '$yr' && 
				animal.animal_id LIKE '$an' && 
				animal.sireo LIKE '$mm' && 
				animal.damo LIKE '$ff' && 
				animal.sex LIKE '$sx' && 
				animal.here LIKE '$so' && 
				animal.stud_id LIKE '$si'
			ORDER BY dob ASC";

		// Run the query:
		$r8 = @mysqli_query ($dbc, $q8); // Run the query.
	
		// Count the number of returned rows:
		$records = mysqli_num_rows($r8);
		$rr = $r8; 			//Set the right query for table
	}
	
	mysqli_close($dbc);

    echo '<h1>Roll Call</h1><br />';
	echo '<h3>' . $a . ' ' . $s . ' ' . $p . ' ' . $x . '</h3>';

	// Table header.
    echo '<div class="champs1">';
    echo '<br /><table align="center" border="1" cellspacing="0" cellpadding="3" summary="RollCall" width="80%">
	<tr align="center" bgcolor="#000000">
		<td><strong>Animal</strong></td>
		<td><strong>Tattoo</strong></td>
		<td><strong>DOB</strong></td>
		<td><strong>Sex</strong></td>
		<td><strong>Role</strong></td>
		<td><strong>Sire</strong></td>
		<td><strong>Dam</strong></td>
		<td><strong>Studbook</strong></td>
	</tr>';

		// Fetch and print all the records:
		while ($row = mysqli_fetch_array($rr, MYSQLI_ASSOC)) {

				// Get correct gender
				  if ($row['sex'] == 'M') { $sx1 = 'Male';
				} elseif ($row['sex'] == 'S') { $sx1 = 'Steer'; 
					} else {                     	
				    		$sx1 = 'Female';
				  		}

				//Get correct DOB format
				$db = $row['dob'];
				$DOB = date("d M Y",strtotime($db));		

			// Work out if there's a stud book URL
			if ($row['sb'] != NULL) {
				$sbook = $row['sb'];
				$sbpix = "sb1.png";
				$sbalt = "Go to ALCA Stud Book";
			} else {
				$sbook = "no_sb.php";
				$sbpix = "sb0.png";
				$sbalt = "No Stud Book entry";
			}

            // Construct the row
    		echo '<tr>
    				<td>' . $row['name'] . '</td>
    				<td align="center">'. $row['tatt'] . '</td>
    				<td align="center">' . $DOB . '</td>
    				<td>' . $sx1 . '</td>
    				<td>' . $row['role'] . '</td>
    				<td>' . $row['dad'] . '</td>
    				<td>' . $row['mum'] . '</td>
					<td align="center"><a title="' . $sbalt . '" href="' . $sbook . '" target="_blank"><img src=
					"images/' . $sbpix . '" border="0" alt="Stud Book" width="30" height="30" /></a></td>
    			  </tr>';
        }
        echo '</table>'; // Close the table.
        echo '</div>';

		// Print how many animals there are.
			// Get the English correct.
			if ($records == 1) {
				$prep = "is";
				$noun = "record";
			} else {
				$prep = "are";
				$noun = "records";
			}
			// Convert numbers to lower case words
			$wrecords = strtolower(conv_num($records));
		
		// Print the statement
		echo "<br /><p class='copyright'>There $prep $wrecords $noun in this category.</p>\n";


        // Link to reload page
        echo '<p class="smallinkL"><a href="rollcall.php" title="Conduct another search">Search Again</a><br /></p>';

    } else { // Errors not empty.

		// Public message:
		echo '<h1>System Error</h1>
		<p class="error">Your request could not be completed due to a system error. We apologise for any inconvenience.</p>';

		// Debugging message:
		echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q0 . '</p>';

    }

    // Include the footer and quit the script:
    include ('includes/navbar.html');
    include ('includes/footer.html');
    exit();

	mysqli_free_result ($rr); // Free up the resources.
	mysqli_close($dbc);

} // End of the main Submit conditional.
?>

<h1>Roll Call</h1>

<div class="champs">
	<h3>Our Herd</h3>	
	
	<form action="rollcall.php" method="post">
	 <fieldset>
	  <legend>Select the details</legend><br />
	   <table summary="calves" align="center" width="500px" border="0" cellpadding="1">
		  <tr>
		    <td align="right">Birth Year:&nbsp;&nbsp;</td>
		    <td valign="middle"><select name="year" style="width: 60px"><option value = '%' selected="selected">All</option>
		    <?php dropdown('year', 'year', 'years', 'year'); ?></select>
		    &nbsp;&nbsp;<input type="checkbox" name="since" value="N" />&nbsp;&nbsp;Since then?
		    </td>
		  </tr>
		  <tr>
		    <td align="right">Animal Name:&nbsp;&nbsp;</td>
		    <td valign="middle"><select name="animal_id" style="width: 110px"><option value='%' selected="selected">All</option>
		    <?php dropdown('animal_id', 'aname', 'animal', 'aname'); ?>
		    </select></td>
		  </tr>
		  <tr>
		    <td align="right">Sire Name:&nbsp;&nbsp;</td>
		    <td valign="middle"><select name="sire_id" style="width: 110px"><option value='%' selected="selected">All</option>
		    <?php dropdown2('animal_id', 'aname', 'animal', 'M'); ?>
		    </select></td>
		  </tr>
		  <tr>
		    <td align="right">Dam Name:&nbsp;&nbsp;</td>
		    <td valign="middle"><select name="dam_id" style="width: 110px"><option value='%' selected="selected">All</option>
		    <?php dropdown2('animal_id', 'aname', 'animal', 'F'); ?>
		    </select></td>
		  </tr>
		  <tr>
		    <td align="right">Sex:&nbsp;&nbsp;</td>
		    <td>
		        <input type="radio" name="sex" value="%" checked="checked" />All&nbsp;
		        <input type="radio" name="sex" value="M" />Male&nbsp;
		        <input type="radio" name="sex" value="F" />Female
		        <input type="radio" name="sex" value="S" />Steer
		    </td>
		  </tr>
		  <tr>
		    <td></td>
		    <td><input type="checkbox" name="amf" value="AMF" />&nbsp;&nbsp;Bred by Amberfield?</td>
		  </tr>
		  <tr>
		    <td></td>
		    <td><input type="checkbox" name="own" value="N" />&nbsp;&nbsp;Still Owned by Amberfield?</td>
		  </tr>
		  <tr>
		    <td></td>
			<td class="champs"><input type="submit" class="button1" name="submit" value="Submit" />
			<input type="hidden" name="submitted" value="TRUE" /></td>
		  </tr>
	   </table><br />
	 </fieldset>
	</form>
		
	<br /><br />
	<p align="justify">We are proud to be producing quality Lowline stock. To date, we have produced over 
	50 stud and about 30 first cross calves.</p>
	
	<p align="justify">In the past when we had a smaller herd, photos and details of our calves appeared on this page.
	We now provide access to our database which enables you to search our herd. </p>

	<p align="justify">Since we stopped showing in late-2008, we have been focussing on meat production. We still have four
    first cross cows, but mainly raise our stud calves for meat also. Most bull calves are steered and only the 
    best heifers are kept.</p>
    
    <p align="justify">We no longer register all progeny, just those who may be sold as stud animals. All of our registered 
    herd is listed on the <acronym title="Australian Lowline Cattle Association">ALCA</acronym> site. Click on the Stud Book or 
    
    <a title="Amberfield ALCA Herd List" href=
    "http://abri.une.edu.au/online/cgi-bin/i4.dll?1=37333A34&amp;2=2428&amp;3=56&amp;5=2B3C2B3C3A&amp;8=4853&amp;9=5051595C" 
    target="_blank">here</a>.</p>

	<table summary="studbook" align="center" width="500px" border="0" cellpadding="20">
		<tr>
			<td align="center"><a title="AMF Stud Book" href=
				"http://abri.une.edu.au/online/cgi-bin/i4.dll?1=37333A34&amp;2=2428&amp;3=56&amp;5=2B3C2B3C3A&amp;8=4853&amp;9=5051595C" target="_blank">
			<img src="images/studbookshad.png" alt="Stud Book" width="306" height="292" hspace="10" vspace="10" />
			</a></td>
		</tr>
   </table>
	
</div>    <!--Close 'champs'-->

<?php
    include ('includes/navbar.html');
    include ('includes/footer.html');
?>
