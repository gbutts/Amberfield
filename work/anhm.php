<?php # Based on Script 8.6 - view_users.php #1
// Based on animals13.php
// This script retrieves all the active records from the animals table.
// This is anhm2.php - adds age

session_start(); // Start the session.
$pname = 'Stud Animals Still Active';
$version  = 'v4.0.2';
	$vdate = date("d F Y", getlastmod());
$page_title = 'Amberfield :: '.$pname;

// TODO - Add option for live at home.

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

// Page header:
echo '<h1>Active Stud Animals</h1>';

require_once ('../cgi-bin/mysqli_connect.php'); // Connect to the db.

// Make the query:
$q0 = "SELECT CONCAT(stud.sname, ' ', animal.aname) AS name, 
		dob AS dr, 
		dod AS dd, 
		sbook AS sb, 
		role AS ro
		FROM stud, animal, roles 
		WHERE animal.stud_id = stud.stud_id
		AND animal.role_id = roles.role_id 
		AND roles.role_id NOT LIKE '3' 
		AND roles.role_id NOT LIKE '5'
		AND roles.role_id NOT LIKE '6'
		ORDER BY dr ASC";
$r0 = @mysqli_query ($dbc, $q0); // Run the query.

// Count the number of returned rows:
$records1 = mysqli_num_rows($r0);

	// Table header.
	echo '<table align="center" cellspacing="0" cellpadding="3" width="95%">
	<tr bgcolor="#f0e68c">
		<td align="left"><b>Animal Name</b></td>
		<td align="center"><b>Date of Birth</b>
		<td align="center"><b>Age</b>
		</td><td align="center"><b>Status</b></td>
		<td align="center"><b>Stud Book</b></td>
	</tr>';

	// Fetch and print all the records:

	$bg = '#ddefff'; // Set the initial background color.
	
	while ($row = mysqli_fetch_array($r0, MYSQLI_ASSOC)) {
	
	$bg = ($bg=='#ffffdd' ? '#ffffff' : '#ffffdd'); // Switch the background color.

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
	
	echo '<tr bgcolor="' . $bg . '">
		<td align="left">' . $row['name'] . '</td>
		<td align="center">' . $row['dr'] . '</td>
		<td align="center">' . GetAge($row['dr'],$row['dd']) . '</td>
		<td align="center">' . $row['ro'] . '</td>
		<td align="center"><a title="' . $sbalt . '" href="' . $sbook . '" target="_blank"><img src=
		"images/' . $sbpix . '" border="0" alt="Stud Book" width="25" height="25" /></a></td>
		</tr>';
} // End of WHILE loop.

	echo '</table>'; // Close the table.

// Print how many animals there are.
	
	// Convert numbers to lower case words
	$wrecords = strtolower(conv_num($records1));

	echo "<br /><i>There are $wrecords stud animals active on the database.</i><br />";
	
	mysqli_free_result ($r0); // Free up the resources.	
	mysqli_close($dbc); // Close the database connection.


echo '<div id="smallink">';

echo '<a href="animals.php?d=100&r=' . $records . '" title="Show All">Show All</a>&nbsp;&nbsp;';
echo '<a href="animals.php?d=10&r=' . $records . '" title="Show 10">Show 10</a>';
echo '</div>';

include ('includes/anfoot.html');
include ('includes/footer.html');
?>
