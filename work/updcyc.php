<?php # Based on Script 8.6 - view_users.php #1 & upd4s4.php
// This script retrieves a cycle record and updates or deletes it.
// This is updcyc1.php - Initial.

session_start(); // Start the session.
$pname = 'Update Cycle';
$version  = 'v4.0.2';
$vdate = 'January 2009';
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
include ('includes/header.html');

// Determine where we are at in the form

if (isset($_POST['submitted_2'])) {

	require_once ('../cgi-bin/mysqli_connect.php');

	$errors = array(); // Initialize an error array.

	// Check for cyc_id.
	if (empty($_POST['cyc_id1'])) {
		$errors[] = 'Didnt get a cycle id.';
	} else {
		$cy1 = mysqli_real_escape_string($dbc, trim($_POST['cyc_id1']));
    }

	$an1 = mysqli_real_escape_string($dbc, trim($_POST['an1']));
	$si1 = mysqli_real_escape_string($dbc, trim($_POST['si1']));
	$cq1 = mysqli_real_escape_string($dbc, trim($_POST['cqual1']));
	$not1 = mysqli_real_escape_string($dbc, trim($_POST['notes1']));

	if (empty($errors)) { // If everything's OK.

		// Update the database...
        // Make the query:
    		$q3 = "UPDATE cycle 
    		SET quality = '$cq1', notes = '$not1'
    		WHERE cycle_id = '$cy1'
    		LIMIT 1";
    		$r3 = @mysqli_query ($dbc, $q3); // Run the query.
    		if ($r3) { // If it ran OK.

    			// Print a message:
    			echo '<h1>Success</h1>
    				  <p>The entry for ' . "$an1" . ' has been amended on the cycle database.</p><p><br /></p>';

				// Construct the query:
//				$q4 = "SELECT aname, sire, quality, notes 
				$q4 = "SELECT aname, quality, notes 
				FROM animal JOIN cycle 
				ON animal.animal_id = cycle.animal_id 
				WHERE cycle.cycle_id LIKE '$cy1'";
		
				$r4 = @mysqli_query ($dbc, $q4); // Run the query.
				// Count the number of returned rows:
				$num = mysqli_num_rows($r4);
		
				if ($num > 0) { // If it ran OK, display the records.
		
					// Table header.
					echo '<fieldset>
						<legend>Entry Now Reads</legend><br />
						<table align="center" width = "500px" border="0">';
			
							// Fetch and print all the records:
							while ($row = mysqli_fetch_array($r4, MYSQLI_ASSOC)) {
			
								// Determine the current sale
								$aa2 = $row['aname'];
								//$si2 = $row['sire'];
								$cq2 = $row['quality'];
								$no2 = $row['notes'];
			
			      				echo '<tr>
							        <td align="right">Name:</td>
							        <td align="left"><b>' .strtoupper($aa2) . '</b></ br></td>
					      		  </tr>
							      <tr>
							        <td valign="top" align="right">Sire:</td>
							        <td align="left"><p>' . $si1 . '</p></ br></td>
							      </tr>
							      <tr>
							  	    <td valign="top" align="right">Quality:</td>
							        <td align="left"><p>' . $cq2 . '</p></ br></td>
							      </tr>
							      <tr>
							  	    <td valign="top" align="right">Notes:</td>
							        <td align="left"><p>' . $no2 . '</p></ br></td>
							      </tr>';
							}
		
					echo '</table>';	   // Close the table.
					echo '</fieldset>'; 	// Close the field
						
				include ('includes/cycfoot.html');
				include ('includes/footer.html');
		
		    	} else { // If it did not run OK.
		
		    			// Public message:
		    			echo '<h1>System Error</h1>
		    			<p class="error">A problem occurred.</p>';
		
		    			// Debugging message:
		    			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q4 . '</p>';
				}

    	} // End of if ($r) IF.
    }	
	mysqli_close($dbc); // Close the database connection.
	exit();

// End of the first Submit conditional.
} elseif (isset($_POST['submitted_1'])) { 

	require_once ('../mysqli_connect.php'); // Connect to the db.

	$cy = mysqli_real_escape_string($dbc,trim($_POST['cyc1']));
	if ($cy == "Select...") {
		$errors[] = 'You forgot to enter a cycle.';
	} 
	
	if (empty($errors)) { // If everything's OK.

		// Construct the query:
		$q0 = "SELECT aname, cycdate, sire, quality, notes 
		FROM animal JOIN cycle 
		ON animal.animal_id = cycle.animal_id 
		WHERE cycle.cycle_id LIKE '$cy'";

		$r0 = @mysqli_query ($dbc, $q0); // Run the query.
		// Count the number of returned rows:
		$num = mysqli_num_rows($r0);

		if ($num > 0) { // If it ran OK, display the records.

			// Table header.
			echo '<h1>Update a cycle</h1>';
			echo '<form action="updcyc.php?cy=' . $cy . '" method="post">
			<fieldset>
				<legend>Cycle Status</legend><br />
				<table align="center" width = "500px" border="0">';
	
					// Fetch and print all the records:
					while ($row = mysqli_fetch_array($r0, MYSQLI_ASSOC)) {
	
						// Determine the current cycle
						$aa = $row['aname'];
						$si = $row['sire'];
						$cq = $row['quality'];
						$no = $row['notes'];

						// Work out key dates
						$cd = $row['cycdate'];		// Cycle date from table
						$CD = strtotime($cd);		// Formatted to an integer
						$cd = date('d M y', $CD);	// Aussie format

						// Determine Sire
						$qs1 = "SELECT aname FROM animal 
						WHERE animal_id LIKE '$si'";
						$rs1 = mysqli_query($dbc, $qs1);  // Run the query.
						$row = mysqli_fetch_array($rs1, MYSQLI_ASSOC);
						$sr = $row['aname'];
						
						mysqli_free_result ($rs1); // Free up the resources.

	
	      				echo '<tr>
					        <td align="right">Name:</td>
					        <td align="left"><b>' .strtoupper($aa) . '</b></ br></td>
			      		  </tr>
					      <tr>
					        <td valign="top" align="right">Cycle Date:</td>
					        <td align="left">' . $cd . '</ br></td>
					      </tr>
					      <tr>
					  	    <td valign="top" align="right">Sire:</td>
					        <td align="left">' . $sr . '</ br></td>
					      </tr>
					      <tr>
					  	    <td valign="top" align="right">Quality:</td>
					        <td align="left">
					        <textarea name="cqual1" align="left" cols="3" rows="1">' . $cq . '</textarea></ br></td>
					      </tr>
					      <tr>
					  	    <td valign="top" align="right">Notes:</td>
					        <td align="left">
					        <textarea name="notes1" align="left" cols="40" rows="3">' . $no . '</textarea></ br></td>
					      </tr>';
					}

					echo '<tr>
							<td><br /><br /></td>
							<td align="center"><input type="submit" name="submit" value="Submit" />
							<input type="hidden" name="submitted_2" value="TRUE" /></td>
							<input type="hidden" name="an1" value="' . $aa . '" /></td>
							<input type="hidden" name="si1" value="' . $sr . '" /></td>
							<input type="hidden" name="cyc_id1" value="' . $cy . '" /></td>
							<td></td>
						  </tr>';
				echo '</table>';	   // Close the table.
			echo '</fieldset>'; 	// Close the field
			
			echo '<br /><p><strong>To delete ' . $aa . '\'s cycle entry, click <a href="delcyc.php?del=' . $cy . '" title="Delete">here.</a></strong></p>';
			
			include ('includes/cycfoot.html');
			include ('includes/footer.html');
	
			mysqli_free_result ($r0); // Free up the resources.

		} else { // If no records were returned.
	
			$q1 = "SELECT aname FROM animal JOIN cycle 
			ON animal.animal_id = cycle.animal_id 
			WHERE cycle.cycle_id LIKE '$cy'";

			$r1 = mysqli_query($dbc, $q1);  // Run the query.
			$row = mysqli_fetch_array($r1, MYSQLI_ASSOC);
			$ab = $row['aname'];
			echo '<p class="error">' . "$ab" . ' is not registered as a cycle. Try again.</p>';
	
		}
	} else { // Report the errors.

		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
			}
			echo '</p><p>Please try again.</p><p><br /></p>';
		include ('includes/cycfoot.html');
		include ('includes/footer.html');

	} // End of if (empty($errors)) IF.

	mysqli_close($dbc); // Close the database connection.
	exit();

// End of the second Submit conditional.
}

require_once ('../mysqli_connect.php'); // Connect to the db.

?>

<h1>Update a Cycle</h1>
	<form action="updcyc.php" method="post">
	<fieldset><legend>Choose a cow who has cycled</legend><br />
		<table summary="form" align="center" width ="60%">
			<tr>
				<td align="right">Cycle:</td>
				<td valign="top"><select name="cyc1" STYLE="width: 120px"><option selected>Select...</option>
				<?php dropdown5('cycle_id', 'aname', 'animal.animal_id', 'aname'); ?>
				</select></td>
			</tr>
			<tr>
				<td></td>
				<td><br /><br /><input type="submit" name="submit" value="Submit" />
				<input type="hidden" name="submitted_1" value="TRUE" /></td>
			</tr>
		</table>
	</fieldset>
	</form>

<?php
include ('includes/cycfoot.html');
include ('includes/footer.html');
?>
