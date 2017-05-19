<?php # Based on Script 8.6 - view_users.php #1
// Further based on gstar_results.php
// This script retrieves all the records from the Genestar table.
// status_filter6.php - Include 'stud'
session_start(); // Start the session.
	$pname = 'Classification Results';
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

include ('includes/dropdown.inc.php');
include ('includes/get_age.inc.php');
include ('includes/conv_num.inc.php');
include ('includes/header.html');

require_once ('../cgi-bin/mysqli_connect.php'); // Connect to the db.

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	$ro = mysqli_real_escape_string($dbc,trim($_POST['role_id']));
	$st = mysqli_real_escape_string($dbc,trim($_POST['stud']));

		// If request is 'Stud'
		if ($st == 'Y') {

		// Build query
		// Call '1' from 'roles.stud'
		$q0 = "SELECT CONCAT(stud.sname, ' ', animal.aname) AS name,
				CONCAT(animal.stud_id, animal.tattoo) AS tatt,
					dob, dod, role 
				FROM stud, animal, roles 
				WHERE 
					animal.stud_id = stud.stud_id AND 
					animal.role_id = roles.role_id AND 
					roles.stud LIKE '1'  
				ORDER BY dob ASC";

		} else {
		
		// Build query
		// Call '$ro' from 'roles.role_id'
		$q0 = "SELECT CONCAT(stud.sname, ' ', animal.aname) AS name,
				CONCAT(animal.stud_id, animal.tattoo) AS tatt,
				dob, dod, role 
				FROM stud, animal, roles 
				WHERE 
					animal.stud_id = stud.stud_id AND 
					animal.role_id = roles.role_id AND 
					roles.role_id LIKE '$ro'  
				ORDER BY dob ASC";
		}

	// Get the status
		$q1 = "SELECT role FROM roles WHERE role_id LIKE '$ro'"; 
		$r1 = @mysqli_query ($dbc, $q1); // Run the query.
		$row = mysqli_fetch_array($r1, MYSQLI_ASSOC);

	// Get name of Status
		$status = $row['role'];

	// Run the query:
		$r0 = @mysqli_query ($dbc, $q0); // Run the query.

	// Count the number of returned rows:
		$records = mysqli_num_rows($r0);

		// Table header.
		echo '<h1>Classification Results</h1>
				<form action="status_filter.php" method="post">
				<fieldset><legend>Status: <b>' . $status . '</b></legend><br />
					<table align="center" cellspacing="0" cellpadding="3" width="90%">
					<tr bgcolor="#f0e68c"><td align="left" width="180px"><b>Animal Name</b></td>
						<td align="left"><b>Tattoo&nbsp;</b></td>
						<td align="right"><b>DOB&nbsp;&nbsp;</b></td>
						<td align="center"><b>Age</b></td>
					</tr>';

					// Set the initial background color.
					$bg = '#ddefff'; 
					
					// Fetch and print all the records:
					while ($row = mysqli_fetch_array($r0, MYSQLI_ASSOC)) {
					$DB = $row['dob'];
					$db = date('d M y', strtotime($DB));	// Aussie format

					$tt = $row['tatt'];
					$DD = $row['dod'];
					
					$bg = ($bg=='#ffffdd' ? '#ffffff' : '#ffffdd'); // Switch the background color.
					
						echo '<tr bgcolor="' . $bg . '">
								<td align="left">' . $row['name'] . '</td>
								<td align="left">' . $tt . '</td>
								<td align="right">' . $db . '</td>
								<td align="center">' . GetAge($DB,$DD) .'</td>
							 </tr>
						';
						// TODO GetAge() needs to be fixed for calf age due new year.
						// Check when a 2009 calf is added. Suspect its age will be reported in Months.
						// Reckon age in months when DoB in current year.
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
		echo "<br /><p>There $prep $wrecords $status $noun.</p>\n";
		
		// Lower links
		include ('includes/anfoot.html');
		include ('includes/footer.html');
		exit();
	
	mysqli_free_result ($r0); // Free up the resources.
	mysqli_close($dbc); // Close the database connection.
}
?>

<h1>Filter by Classification</h1>
	<form action="status_filter.php" method="post">
		<fieldset><legend>Pick an Animal Classification</legend><br />
		<table summary="form" align="center" border = "0" width ="60%">
			<tr>
				<td align="right">Status:</td>
				<td valign="top"><select name="role_id" STYLE="width: 120px"><option selected>Select...</option>
				<?php dropdown('role_id', 'role', 'roles', 'role'); ?>
				</select></td>
			</tr>
	 	    <tr>
			    <td align="right"><br />Stud Animals @ AMF</td>
			    <td><br /><input type="checkbox" name="stud" value="Y" /></td>
		    </tr>
			<tr>
				<td></td>
				<td><br /><br /><input type="submit" name="submit" value="Submit" />
				<input type="hidden" name="submitted" value="TRUE" />
				<input type="reset" name="clear" value="Clear" /></td>
			</tr>
		</table>
		</fieldset>
	</form>

<?php
	include ('includes/anfoot.html');
	include ('includes/footer.html');
?>
