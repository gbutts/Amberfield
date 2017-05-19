<?php # Script 8.6 - This script builds on studs.php 
// This script retrieves cycle dates and predicts birth date.
// This is cycle1.php - initial.

    session_start(); // Start the session.
    $pname = 'Birth Predictions';
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
    include ('includes/header.html');
	include ('includes/conv_num.inc.php');

// Page header:
echo '<h1>Predicted Births</h1>';

require_once ('../cgi-bin/mysqli_connect.php'); // Connect to the db.
		
// Make the query:
	$q0 = "SELECT CONCAT(stud.sname, ' ', animal.aname) AS name,
	cycle.animal_id, cycdate, quality
	FROM stud, animal, cycle  
	WHERE animal.stud_id = stud.stud_id
	AND cycle.animal_id = animal.animal_id 
	ORDER BY cycdate ASC";
	$r0 = @mysqli_query ($dbc, $q0); // Run the query.

// Count the number of returned rows:
$records = mysqli_num_rows($r0);

if ($records > 0) { // If it ran OK, display the records.

	// Table header.
	echo '<table align="center" cellspacing="0" cellpadding="3" width ="600px">
	<tr bgcolor="#f0e68c">
		<td align="left"><b>Name</b></td>
		<td align="center"><b>Cycle</b></td>
		<td align="center"><b>Check</b></td>
		<td align="center"><b>Birth</b></td>
		<td align="center"><b>Quality</b></td>
	</tr>';
	
	// Fetch and print all the records:
	$bg = '#ddefff'; // Set the initial background color.
	
	while ($row = mysqli_fetch_array($r0, MYSQLI_ASSOC)) {
		$bg = ($bg=='#ffffdd' ? '#ffffff' : '#ffffdd'); // Switch the background color.

		// Work out key dates
		$cd = $row['cycdate'];		// Cycle date from table
		$CD = strtotime($cd);		// Formatted to an integer
		$cd = date('d M y', $CD);	// Aussie format

															// From: http://www.hawkee.com/snippet/1434/
		$nc = date('d M y', strtotime("+19 days", $CD)); 	// Adds 19 days to integer date (from 1/1/70)
		$bd = date('d M y', strtotime("+274 days", $CD));	// Adds 274 days
		
		echo '<tr bgcolor="' . $bg . '">
			<td align="left">' . $row['name'] . '</td>
			<td align="center">' . $cd . '</td>
			<td align="center">' . $nc . '</td>
			<td align="center">' . $bd . '</td>
			<td align="center"><img src="images/' . $row['quality'] . 's.png" width="31" height="18" alt="' . $row['quality'] . '" /></td>
		</tr>';
	} // End WHILE loop

	echo '</table>'; // Close the table.

	echo '<div id="centre">'; //Centres buttons
	// Convert numbers to lower case words
	$wrecords = strtolower(conv_num($records));

	// Print how many animals there are:
	echo "<br /><p>There are currently $wrecords recorded cycles.</p><br />";
	echo '</div>';
	
	mysqli_free_result ($r0); // Free up the resources.	

} else { // If no records were returned.

	echo '<p class="error">There are currently no recorded cycles.</p><br />';

}

mysqli_close($dbc); // Close the database connection.

// Table for quality definitions
echo '<fieldset><legend><b>Quality Definitions</b></legend><br />
	   <table align="center" cellspacing="0" cellpadding="3" width="550px">
		<tr>
			<td width="15%"><img src="images/0s.png" width="31" height="18" alt="0 Stars" /></td>
			<td>Circumstantial evidence (eg tail out, minor bull interest) only.</td>
		</tr>	
		<tr>
			<td><img src="images/1s.png" width="31" height="18" alt="1 Star" /></td>
			<td>Substantial evidence (eg mounting, slime).</td>
		</tr>	
		<tr>
			<td><img src="images/2s.png" width="31" height="18" alt="2 Stars" /></td>
			<td>Confirmed (eg several missed cycles, preg test, knees).</td>
		</tr>	
	 </table>
	</fieldset><br /><br />';

include ('includes/cycfoot.html');
include ('includes/footer.html');
?>
