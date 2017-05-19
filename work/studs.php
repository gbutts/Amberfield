<?php # Script 8.6 - studs2.php 
// This script retrieves all the records from the studs table.
// This is studs2.php - 'web page' icons fixed.

    session_start(); // Start the session.
    $pname = 'View the Available Studs';
    $version  = 'v4.1.0';
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

// Page header:
echo '<h1>Available Studs</h1>';

	require_once ('../cgi-bin/mysqli_connect.php');
		
// Make the query:
$q = "SELECT sname, stud_id, w_add FROM stud ORDER BY stud_id ASC";
$r = @mysqli_query ($dbc, $q); // Run the query.

// Count the number of returned rows:
$records = mysqli_num_rows($r);

if ($records > 0) { // If it ran OK, display the records.

	// Table header.
	echo '<table align="center" cellspacing="0" cellpadding="3" width="70%">
	<tr bgcolor="#f0e68c"><td align="center"><b>Stud Name</b></td><td align="center"><b>Tattoo</b></td><td align="center"><b>Web Site</b></td></tr>';
	
	// Fetch and print all the records:
	$bg = '#ddefff'; // Set the initial background color.
	
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$bg = ($bg=='#ffffdd' ? '#ffffff' : '#ffffdd'); // Switch the background color.

		// Work out if there's a stud book URL
		if ($row['w_add'] != NULL) {
			$wa = $row['w_add'];
			$wapix = "wp1.png";
		} else {
			$wa = "no_wa.php";
			$wapix = "wp0.png";
		}


		echo '<tr bgcolor="' . $bg . '">
		<td align="center">' . $row['sname'] . '</td>
		<td align="center">' . $row['stud_id'] . '</td>
		<td align="center"><a title="Web Address" href="' . $wa . '" target="_blank"><img src=
    	"images/' . $wapix . '" border="0" alt="Web Address" width="25" height="25" /></a></td>
		</tr>';
	}

	echo '</table>'; // Close the table.

	echo '<div id="centre">'; //Centres buttons
	// Print how many animals there are:
	echo "<br /><p>There are currently $records studs.</p>\n";
	echo '</div>';
	
	mysqli_free_result ($r); // Free up the resources.	

} else { // If no records were returned.

	echo '<p class="error">There are currently no studs.</p>';

}

mysqli_close($dbc); // Close the database connection.

include ('includes/footer.html');
?>
