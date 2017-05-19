<?php # Based on Script 8.6 - view_users.php #1
// Further based on gstar_results.php & unreg.php
// This script retrieves all the cycling records.
// Filters sires, dead, sold and steered.
// viewcyc1.php - Initial

session_start(); // Start the session.
$pname = 'View Cycling Records';
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
		WHERE animal.stud_id = stud.stud_id 
		AND animal.role_id = roles.role_id 
		AND sbook LIKE '' 
		AND roles.role_id NOT LIKE '3' 
		AND roles.role_id NOT LIKE '5'
		AND roles.role_id NOT LIKE '6'
		ORDER BY dob ASC";
$r0 = @mysqli_query ($dbc, $q0); // Run the query.

// Count the number of returned rows:
$records = mysqli_num_rows($r0);

// Table header.
echo '<h1>Cycling Records</h1>
			<table align="center" cellspacing="0" cellpadding="3" width="80%">
			<tr bgcolor="#f0e68c"><td align="left" width="200px"><b>Breeder Name</b></td>
				<td align="center"><b>Sire</b></td>
				<td align="center"><b>Cycle Date</b></td>
				<td align="center"><b>Check Date</b></td>
				<td align="center"><b>Due Date</b></td>
				<td align="center"><b>Quality</b></td>
			</tr>';

			// Set the initial background color.
			$bg = '#ddefff'; 
			
			// Fetch and print all the records:
			while ($row = mysqli_fetch_array($r0, MYSQLI_ASSOC)) {
			$DB = $row['dob'];
			$DoB = new DateTime($DB);
			$BD = $DoB->format('j M y'); // Format date into Aussie way

			$bg = ($bg=='#ffffdd' ? '#ffffff' : '#ffffdd'); // Switch the background color.
			
				echo '<tr bgcolor="' . $bg . '">
						<td align="left">' . $row['name'] . '</td>
						<td align="right">' . $BD . '</td>
						<td align="center">' . GetAge($DB,"") .'</td>
					 </tr>
				';
				}
echo '</table>';    // Close the table.

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
echo "<br /><p>There $prep $wrecords animals still to be registered with ALCA.</p>\n";

// Lower links
echo '<div id="smallink">';
	echo '<a href="regcyc.php" title="Register a Cycle">Register a Cycle</a>';
echo '</div>';

include ('includes/footer.html');

mysqli_free_result ($r0); // Free up the resources.
mysqli_close($dbc); // Close the database connection.
?>
