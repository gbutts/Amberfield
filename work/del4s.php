<?php # Based on Script 8.6 - view_users.php #1
// This script retrieves a for sale record and deletes it.
// This is del4s2.php -  tidy still required.
// It is called from upd4s.php only.

session_start(); // Start the session.
$pname = 'Delete a For Sale Record';
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

// Determine where we are at in the form

if (isset($_POST['submitted'])) {

require_once ('../cgi-bin/mysqli_connect.php'); // Connect to the db.


	// Check for sale_id.
	if (empty($_POST['del'])) {
		$errors[] = 'Didnt get a sale id.';
	} else {
		$sa1 = mysqli_real_escape_string($dbc, trim($_POST['del']));
    }

	if (empty($errors)) { // If everything's OK.
	
		// Construct the query:
		$q1 = "DELETE FROM 4sale WHERE 4sale.sale_id = '$sa1' LIMIT 1";
	
		$r1 = @mysqli_query ($dbc, $q1); // Run the query.

			// Add a list of those remaining.
			// Construct the query:
			$q2 = "SELECT 4sale.animal_id, aname FROM 4sale JOIN animal ON 4sale.animal_id = animal.animal_id";
			$r2 = mysqli_query($dbc, $q2);  // Run the query.

			echo '<h1>The following animals are still For Sale.</h1>';			
			// Table header.
			echo '<table align="center" cellspacing="0" cellpadding="3" width="80%">
					<tr>
						<td align="left"><b>Animal Name</b></td>
					</tr>';
					
					while ($row = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
					echo '<tr><td align="left">' . $row['aname'] . '</td></tr>';
				
					} // End of WHILE loop.
		
			echo '</table>'; // Close the table.

			echo '<div id="smallink">
			<a href="upd4s.php" title="Amend Another Sale">Amend Another Sale</a><br /><br />
			<a href="reg4s.php" title="Add Another Sale">Add Another Sale</a>
			</div>';
			
			include ('includes/footer.html');

	} else { // Report the errors.

		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
			}
			echo '</p><p>Please try again.</p><p><br /></p>';

			echo '<div id="smallink">
			<a href="upd4s.php" title="Amend Another Sale">Amend Another Sale</a><br /><br />
			<a href="reg4s.php" title="Add Another Sale">Add Another Sale</a>
			</div>';
			
	} // End of if ($r) IF.
		
	mysqli_close($dbc); // Close the database connection.
	exit();

// End of the first Submit conditional.
}

require_once ('../mysqli_connect.php'); // Connect to the db.

	// Determine if we've been sent a deletion...
	if (isset($_GET['del']) && is_numeric($_GET['del'])) { // Already been determined.
		$del_an = $_GET['del'];

		// Construct the query:
		$q0 = "SELECT animal.aname 
		FROM animal JOIN 4sale 
		ON animal.animal_id = 4sale.animal_id 
		WHERE 4sale.sale_id LIKE '$del_an'";
	
		$r0 = @mysqli_query ($dbc, $q0); // Run the query.
		// Count the number of returned rows:
		$num = mysqli_num_rows($r0);

		// Fetch and print all the records:
		while ($row = mysqli_fetch_array($r0, MYSQLI_ASSOC)) {

			// Determine the current sale
			$aa = $row['aname'];
		}

		echo '<h1>Delete a For Sale Entry</h1>
			<form action="del4s.php" method="post">
			<fieldset><legend>Delete Now</legend><br />
				<table summary="form" align="center" width ="80%" border="0">
					<tr>
						<td></td>
						<td align="right">Delete:</td>
						<td valign="top"><strong>' . $aa . '</strong></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="4" align="center"><br />Press <i>Delete</i> to delete the record permanently.</td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2" align="center"><input type="submit" name="submit" value="Delete" /><br />
						<input type="hidden" name="submitted" value="TRUE" /></td>
						<input type="hidden" name="del" value="' . $del_an . '" /></td>
						<td></td>
					</tr>
				</table>
			</fieldset>
			</form>

	<div id="smallink">
	<a href="reg4s.php" title="Register a New Sale">Register a New Sale</a>
	</div>';
	}
include ('includes/footer.html');
?>
