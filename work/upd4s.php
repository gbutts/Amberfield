<?php # Based on Script 8.6 - view_users.php #1
// This script retrieves a for sale record and updates or deletes it.
// This is upd4s4.php - Amend now amends not duplicates, feedback on amendment provided, tidy still required.

session_start(); // Start the session.
$pname = 'Update For Sale';
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

include ('includes/dropdown.inc.php');
include ('includes/header.html');

// Determine where we are at in the form

if (isset($_POST['submitted_2'])) {

	require_once ('../cgi-bin/mysqli_connect.php');

	$errors = array(); // Initialize an error array.

	// Check for sale_id.
	if (empty($_POST['sale_id1'])) {
		$errors[] = 'Didnt get a sale id.';
	} else {
		$sa1 = mysqli_real_escape_string($dbc, trim($_POST['sale_id1']));
    }

	// Check for an animal name.
	if (empty($_POST['an1'])) {
		$errors[] = 'You forgot to enter an animal name.';
	} else {
		$an1 = mysqli_real_escape_string($dbc, trim($_POST['an1']));
    }

	// Check for a description.
	if (empty($_POST['description1'])) {
		$errors[] = 'You forgot to enter a Description.';
	} else {
		$des1 = mysqli_real_escape_string($dbc, trim($_POST['description1']));
	}

	// Check for a price.
	if (empty($_POST['price1'])) {
		$errors[] = 'You forgot to enter a price.';
	} else {
		$pri1 = mysqli_real_escape_string($dbc, trim($_POST['price1']));
	}
	
	$not1 = mysqli_real_escape_string($dbc, trim($_POST['notes1']));

	if (empty($errors)) { // If everything's OK.

		// Update the database...
        // Make the query:
    		$q3 = "UPDATE 4sale 
    		SET description = '$des1', price = '$pri1', notes = '$not1'
    		WHERE sale_id = '$sa1'
    		LIMIT 1";
    		$r3 = @mysqli_query ($dbc, $q3); // Run the query.
    		if ($r3) { // If it ran OK.

    			// Print a message:
    			echo '<h1>Success</h1>
    				  <p>The entry for ' . "$an1" . ' has been amended on the For Sale database.</p><p><br /></p>';

				// Construct the query:
				$q4 = "SELECT aname, description, price, notes 
				FROM animal JOIN 4sale 
				ON animal.animal_id = 4sale.animal_id 
				WHERE 4sale.sale_id LIKE '$sa1'";
		
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
								$ds2 = $row['description'];
								$pr2 = $row['price'];
								$no2 = $row['notes'];
			
			      				echo '<tr>
							        <td align="right">Name:</td>
							        <td align="left"><b>' .strtoupper($aa2) . '</b></ br></td>
					      		  </tr>
							      <tr>
							        <td valign="top" align="right">Description:</td>
							        <td align="left"><p>' . $ds2 . '</p></ br></td>
							      </tr>
							      <tr>
							  	    <td valign="top" align="right">Price:</td>
							        <td align="left"><p>' . $pr2 . '</p></ br></td>
							      </tr>
							      <tr>
							  	    <td valign="top" align="right">Notes:</td>
							        <td align="left"><p>' . $no2 . '</p></ br></td>
							      </tr>';
							}
		
					echo '</table>';	   // Close the table.
					echo '</fieldset>'; 	// Close the field
		
					include ('includes/4sfoot.html');
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

	$sa = mysqli_real_escape_string($dbc,trim($_POST['sale1']));
	if ($sa == "Select...") {
		$errors[] = 'You forgot to enter a sale.';
	} 
	
	if (empty($errors)) { // If everything's OK.

		// Construct the query:
		$q0 = "SELECT aname, description, price, notes 
		FROM animal JOIN 4sale 
		ON animal.animal_id = 4sale.animal_id 
		WHERE 4sale.sale_id LIKE '$sa'";

		$r0 = @mysqli_query ($dbc, $q0); // Run the query.
		// Count the number of returned rows:
		$num = mysqli_num_rows($r0);

		if ($num > 0) { // If it ran OK, display the records.

			// Table header.
			echo '<h1>Update a Sale Description</h1>';
			echo '<form action="upd4s.php?sa=' . $sa . '" method="post">
			<fieldset>
				<legend>Sale Status</legend><br />
				<table align="center" width = "500px" border="0">';
	
					// Fetch and print all the records:
					while ($row = mysqli_fetch_array($r0, MYSQLI_ASSOC)) {
	
						// Determine the current sale
						$aa = $row['aname'];
						$ds = $row['description'];
						$pr = $row['price'];
						$no = $row['notes'];
	
	      				echo '<tr>
					        <td align="right">Name:</td>
					        <td align="left"><b>' .strtoupper($aa) . '</b></ br></td>
			      		  </tr>
					      <tr>
					        <td valign="top" align="right">Description:</td>
					        <td align="left">
					        <textarea name="description1" align="left" cols="40" rows="10">' . $ds . '</textarea></ br></td>
					      </tr>
					      <tr>
					  	    <td valign="top" align="right">Price:</td>
					        <td align="left">
					        <textarea name="price1" align="left" cols="6" rows="1">' . $pr . '</textarea></ br></td>
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
							<input type="hidden" name="sale_id1" value="' . $sa . '" /></td>
							<td></td>
						  </tr>';
				echo '</table>';	   // Close the table.
			echo '</fieldset>'; 	// Close the field
			
			echo '<br /><p><strong>To delete ' . $aa . '\'s For Sale entry, click <a href="del4s.php?del=' . $sa . '" title="Delete">here.</a></strong></p>';
			
			include ('includes/4sfoot.html');
			include ('includes/footer.html');
	
			mysqli_free_result ($r0); // Free up the resources.

		} else { // If no records were returned.
	
			$q1 = "SELECT aname FROM animal JOIN 4sale 
			ON animal.animal_id = 4sale.animal_id 
			WHERE 4sale.sale_id LIKE '$sa'";

			$r1 = mysqli_query($dbc, $q1);  // Run the query.
			$row = mysqli_fetch_array($r1, MYSQLI_ASSOC);
			$ab = $row['aname'];
			echo '<p class="error">' . "$ab" . ' is not for sale. Try again.</p>';
	
		}
	} else { // Report the errors.

		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
			}
			echo '</p><p>Please try again.</p><p><br /></p>';

			echo '<div id="smallink">';
			echo '<a href="upd4s.php" title="Amend or Delete Another">Amend or Delete Another</a><br /><br />';
			echo '<a href="reg4s.php" title="Register a New Sale">Register a New Sale</a>';
			echo '</div>';

	} // End of if (empty($errors)) IF.

	mysqli_close($dbc); // Close the database connection.
	exit();

// End of the second Submit conditional.
}

require_once ('../mysqli_connect.php'); // Connect to the db.

?>

<h1>Update a For Sale Entry</h1>
	<form action="upd4s.php" method="post">
	<fieldset><legend>Choose a For Sale Entry</legend><br />
		<table summary="form" align="center" width ="60%">
			<tr>
				<td align="right">Sale:</td>
				<td valign="top"><select name="sale1" STYLE="width: 120px"><option selected>Select...</option>
				<?php dropdown4('sale_id', 'aname', 'animal.animal_id', 'aname'); ?>
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
include ('includes/4sfoot.html');
include ('includes/footer.html');
?>
