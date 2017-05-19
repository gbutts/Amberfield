<?php # Based on Script 8.6 - view_users.php #1
// This script retrieves all the records from the animals table.
// This is animals14.php - add age.

session_start(); // Start the session.
$pname = 'Animals Registered on the Database';
$version  = 'v4.1.0'; //20 per page
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
include ('includes/header.html');

// Page header:
echo '<h1>Registered Animals</h1>';

require_once ('../cgi-bin/mysqli_connect.php'); // Connect to the db.

// Number of records to show per page:
if (isset($_GET['d']) && is_numeric($_GET['d'])) { // Already been determined.
		$display = $_GET['d'];
	} else { // Set the display length.
		$display = 20;
}

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
		$pages = $_GET['p'];
	} else { // Need to determine.

		// Count number of records
		$q0 = "SELECT COUNT(animal_id) FROM animal";
		$r0 = @mysqli_query ($dbc, $q0);
		$row = @mysqli_fetch_array ($r0, MYSQLI_NUM);
		$records = $row[0];

		// Calculate the number of pages...
		if ($records > $display) { // More than 1 page.
				$pages = ceil ($records/$display);
			} else {
				$pages = 1;
		}
	mysqli_free_result ($r0); // Free up the resources.	
} // End of page IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
		$start = $_GET['s'];
	} else {
		$start = 0;
}

// Determine number of animals...
if (isset($_GET['r']) && is_numeric($_GET['r'])) {
	$records = $_GET['r'];
} else { // Need to determine.

	// Count number of records
	$q2 = "SELECT COUNT(animal_id) FROM animal";
	$r2 = @mysqli_query ($dbc, $q2);
	$row = @mysqli_fetch_array ($r2, MYSQLI_NUM);
	$records = $row[0];
	mysqli_free_result ($r2); // Free up the resources.	
} // End of Animals IF
	
// Make the query:
$q1 = "SELECT CONCAT(stud.sname, ' ', animal.aname) AS name, 
		dob AS dr,
		dod AS dd, 
		sbook AS sb, 
		role AS ro
		FROM stud, animal, roles 
		WHERE animal.stud_id = stud.stud_id
		AND animal.role_id = roles.role_id 
		ORDER BY dr ASC LIMIT $start, $display";
$r1 = @mysqli_query ($dbc, $q1); // Run the query.

	// Table header.
	echo '<table align="center" cellspacing="0" cellpadding="3" width="95%">
	<tr bgcolor="#f0e68c">
		<td align="left"><b>Animal Name</b></td>
		<td align="center"><b>DOB</b>
		<td align="center"><b>Age</b>
		</td><td align="center"><b>Status</b></td>
		<td align="center"><b>Stud Book</b></td>
	</tr>';

	// Fetch and print all the records:

	$bg = '#ddefff'; // Set the initial background color.
	
	while ($row = mysqli_fetch_array($r1, MYSQLI_ASSOC)) {

		$DB = $row['dr'];
		$DoB = new DateTime($DB);
		$BD = $DoB->format('d M y'); // Format date into Aussie way

	
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
		<td align="center">' . $BD . '</td>
		<td align="center">' . GetAge($row['dr'],$row['dd']) . '</td>
		<td align="center">' . $row['ro'] . '</td>
		<td align="center"><a title="' . $sbalt . '" href="' . $sbook . '" target="_blank"><img src=
		"images/' . $sbpix . '" border="0" alt="Stud Book" width="25" height="25" /></a></td>
		</tr>';
} // End of WHILE loop.

	echo '</table>'; // Close the table.

	echo '<div id="centre">'; //Centres buttons

	// Print records on this page
	$firstrec = $start + 1;
	$endrec = $start + $display; //Add up total records
	if ($endrec > $records) {
		$endrec = $records;
	}

	echo "<br /><i>Results $firstrec - $endrec of $records animals registered on the database.</i><br />";
	
	mysqli_free_result ($r1); // Free up the resources.	
	mysqli_close($dbc); // Close the database connection.

// Make the links to other pages, if necessary.
if ($pages > 1) {
	
	// Add some spacing and determine what page the script is on:
	echo '<br />';
	$current_page = ($start/$display) + 1;

	// If we're not on the first page, make the Previous button live:
	if ($current_page != 1) {
		echo '<a title="Previous" href="animals.php?s=' . ($start - $display) . '&p=' . $pages . '&r=' . $records . '"><img src=
		"images/prev.1.png" border="0" alt="Previous" width="69" height="31" /></a>';
	} else {
		echo '<img src="images/prev.0.png" border="0" width="69" height="31" />';
	}	
	
	// Make all the numbered pages:
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a title="' . $i . '" href="animals.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&r=' . $records . 					'"><img src="images/' .	$i . '.1.png" border="0" alt="' . $i . '" width="31" height="31" /></a>';
		} else {
			echo '<img src="images/' . $current_page . '.2.png" border="0" alt="' . $i . '" width="31" height="31" />';
		}
	} // End of FOR loop.
	
	// If it's not the last page, make a Next button:
	if ($current_page != $pages) {
		echo '<a title="Next" href="animals.php?s=' . ($start + $display) . '&p=' . $pages . '&r=' . $records . '"><img src=
		"images/next.1.png" border="0" alt="Next" width="69" height="31" /></a>';
	} else {
		echo '<img src="images/next.0.png" border="0" width="69" height="31" />';
	}	
	
} // End of links section.
	echo '</div>'; //ends centred glass buttons

echo '<div id="smallink">';

echo '<a href="animals.php?d=100&r=' . $records . '" title="Show All">Show All</a>&nbsp;&nbsp;';
echo '<a href="animals.php?d=10&r=' . $records . '" title="Show 10">Show 10</a>';
echo '</div>';

include ('includes/footer.html');
?>
