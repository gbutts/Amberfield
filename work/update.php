<?php # Based on Script 8.6 - view_users.php #1
// This script retrieves a record and updates animal's the status.
// This is update3.php - Final tidy
session_start(); // Start the session.
	$pname = 'Update Results';
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
include ('includes/header.html');

require_once ('../cgi-bin/mysqli_connect.php'); // Connect to the db.

// Determine where we are at in the form

if (isset($_POST['submitted_2'])) {

	// Check for status.
	$nr =  mysqli_real_escape_string($dbc, trim($_POST['newrole']));
	if ($nr == "Select...") {
		$errors[] = 'You forgot to enter the animal\'s new status.';
	} 
	
	if (empty($errors)) { // If everything's OK.

		// Determine id of animal...
		$aa = $_GET['aa'];

		// Get the animal's name
		$q3 = "SELECT aname FROM animal WHERE animal_id LIKE '$aa'"; 
   		$r3 = @mysqli_query ($dbc, $q3); // Run the query.
		$row = mysqli_fetch_array($r3, MYSQLI_ASSOC);
		$ab = $row['aname'];

		// Update the role in the database...
		// Make the query:
		$q1 = "UPDATE animal SET role_id = '$nr' WHERE animal_id = '$aa'";
   		$r1 = @mysqli_query ($dbc, $q1); // Run the query.

   		if ($r1) { // If it ran OK.

   			// Print a message:
   			echo '<h1>Done</h1>
   			  <p>' . "$ab" . ' has been updated on the database.</p><p><br /></p>';

   		} else { // If it did not run OK.

   			// Public message:
   			echo '<h1>System Error</h1>
   			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';

   			// Debugging message:
   			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q1 . '</p>';
		}
		
	} else { // Report the errors.

		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
			}
			echo '</p><p>Please try again.</p><p><br /></p>';

	} // End of if (empty($errors)) IF.

	include ('includes/anfoot.html');
	include ('includes/footer.html');

	mysqli_close($dbc); // Close the database connection.
	exit();

// End of the first Submit conditional.
} elseif (isset($_POST['submitted_1'])) { 

	$an = mysqli_real_escape_string($dbc,trim($_POST['animal_id']));
	if ($an == "Select...") {
		$errors[] = 'You forgot to enter an animal.';
	} 
	
	if (empty($errors)) { // If everything's OK.

		// Construct the query:
		$q0 = "SELECT aname, role FROM animal JOIN roles ON animal.role_id = roles.role_id WHERE animal.animal_id LIKE '$an'";

		$r0 = @mysqli_query ($dbc, $q0); // Run the query.
		// Count the number of returned rows:
		$num = mysqli_num_rows($r0);

		if ($num > 0) { // If it ran OK, display the records.

			// Table header.
			echo '<h1>Update an Animal\'s Status</h1>';
			echo '<form action="update.php?aa=' . $an . '" method="post">
			<fieldset>
				<legend>Status</legend><br />
				<table align="center" width = "400px" border="0">';
	
					// Fetch and print all the records:
					while ($row = mysqli_fetch_array($r0, MYSQLI_ASSOC)) {
	
						// Determine the current role
						$aa = $row['aname'];
						$cs = $row['role'];
	
						echo '<tr>
								<td align="center" width="40%"><b>' . strtoupper($aa) . '</b></td>
								<td align="center"><b>Current</b></td>
								<td align="center"><b>New</b></td>
							  </tr>
							  <tr>
								<td></td>
								<td align="center">' . $cs . '</td>
								<td  align="center" valign="top">
									<select name="newrole" STYLE="width: 80px"><option selected>Select...</option>';
								dropdown('role_id', 'role', 'roles', 'role');
								echo '</select></td>
							 </tr>';
					}
					echo '<tr>
						<td><br /><br /></td>
						<td align="center"><input type="submit" name="submit" value="Submit" />
						<input type="hidden" name="submitted_2" value="TRUE" /></td>
						<td></td>
					  </tr>';
				echo '</table>';	   // Close the table.
			echo '</fieldset>'; 	// Close the field

			mysqli_free_result ($r0); // Free up the resources.

		} else { // If no records were returned.
	
			$q2 = "SELECT aname FROM animal WHERE animal_id LIKE '$an'";
			$r2 = mysqli_query($dbc, $q2);  // Run the query.
			$row = mysqli_fetch_array($r2, MYSQLI_ASSOC);
			$ab = $row['aname'];
			echo '<p class="error">' . "$ab" . ' is not registered on the database. Try again.</p>';
	
		}
	} else { // Report the errors.

		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
			}
			echo '</p><p>Please try again.</p><p><br /></p>';

	} // End of if (empty($errors)) IF.

	include ('includes/anfoot.html');
	include ('includes/footer.html');


	mysqli_close($dbc); // Close the database connection.
	exit();

// End of the second Submit conditional.
}
?>

<h1>Update an Animal</h1>
	<form action="update.php" method="post">
	<fieldset><legend>Pick an Animal</legend><br />
		<table summary="form" align="center" width ="60%">
			<tr>
				<td align="right">Animal Name:</td>
				<td valign="top"><select name="animal_id" STYLE="width: 120px"><option selected>Select...</option>
				<?php dropdown('animal_id', 'aname', 'animal', 'aname'); ?>
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
	include ('includes/anfoot.html');
	include ('includes/footer.html');
?>
