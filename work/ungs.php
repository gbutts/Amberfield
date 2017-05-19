<?php # Based on Script 8.6 - view_users.php #1
// Further based on unreg.php
// This script retrieves all the records without a Genestar result still remaining at Amberfield.
// Filters dead, sold and steered.
// ungs1.php - Animals without a GeneStar Result
session_start(); // Start the session.
$pname = 'Animals without a GeneStar Result';
$version  = 'v4.0.2';
	$vdate = date("d F Y", getlastmod());
$page_title = 'Amberfield :: '.$pname;

// If no session value is present, redirect the user:
// Also validate the HTTP_USER_AGENT!
if (!isset($_SESSION['agent'])  OR ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT'])) ) {
	require_once ('includes/login_functions.inc.php');
	$url = absolute_url();
	header("Location: $url");
	exit();
}

include ('includes/get_age.inc.php');
include ('includes/conv_num.inc.php');
include ('includes/header.html');

	require_once ('../cgi-bin/mysqli_connect.php');

// Construct the query:
$q0 = "SELECT CONCAT(stud.sname, ' ', animal.aname) AS name, dob, roles.role_id
	FROM stud, animal, roles
	WHERE animal.role_id = roles.role_id
	AND animal.stud_id = stud.stud_id
	AND animal.animal_id NOT
	IN (
		SELECT animal.animal_id
		FROM animal, gstar
		WHERE gstar.animal_id = animal.animal_id
		)
	AND roles.role_id NOT LIKE '3' 
	AND roles.role_id NOT LIKE '5'
	AND roles.role_id NOT LIKE '6'
	ORDER BY dob ASC";

$r0 = @mysqli_query ($dbc, $q0); // Run the query.

// Count the number of returned rows:
$records = mysqli_num_rows($r0);

	// Table header.
	echo '<h1>Animals without GeneStar Results</h1>
			<fieldset><legend>No GeneStar</legend><br />
				<table align="center" cellspacing="0" cellpadding="3" width="80%">
				<tr bgcolor="#f0e68c"><td align="left" width="200px"><b>Animal Name</b></td>
					<td align="right"><b>DOB&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
					<td align="center"><b>Age</b></td>
				</tr>';

				// Set the initial background color.
				$bg = '#ddefff'; 
				
				// Fetch and print all the records:
				while ($row = mysqli_fetch_array($r0, MYSQLI_ASSOC)) {
				$DB = $row['dob'];
				$DoB = new DateTime($DB);

				// Set date of death as NULL as dead animals have already been filtered
				$DD = "";
		
				$BD = $DoB->format('d M y'); // Format date into Aussie way

				$bg = ($bg=='#ffffdd' ? '#ffffff' : '#ffffdd'); // Switch the background color.
				
					echo '<tr bgcolor="' . $bg . '">
							<td align="left">' . $row['name'] . '</td>
							<td align="right">' . $BD . '</td>
							<td align="center">' . GetAge($DB,$DD) .'</td>
						 </tr>
					';
					}
				echo '</table>';    // Close the table.
			echo '</fieldset>'; // Close the field

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
	echo "<br /><p>There $prep $wrecords animals without GeneStar results.</p>\n";
	
	// Lower links
    include ('includes/gsfoot.html');
	include ('includes/footer.html');
	exit();

mysqli_free_result ($r0); // Free up the resources.
mysqli_close($dbc); // Close the database connection.
?>
