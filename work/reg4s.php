<?php # Script 8.5 - reg4s.php
// Based on register5.php
// This script inputs For Sale data.
// This is reg4s3.php - Input OK
    session_start(); // Start the session.
    $pname = 'Register a Sale';
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
	//include ('includes/calscript.html');
	
// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	require_once ('../mysqli_connect.php'); // Connect to the db.

	$errors = array(); // Initialize an error array.

	// Check for an animal name.
	if (empty($_POST['an_id'])) {
		$errors[] = 'You forgot to enter an animal name.';
	} else {
		$an = mysqli_real_escape_string($dbc, trim($_POST['an_id']));
    }

	// Check for a description.
	if (empty($_POST['description'])) {
		$errors[] = 'You forgot to enter a Description.';
	} else {
		$des = mysqli_real_escape_string($dbc, trim($_POST['description']));
	}

	// Check for a price.
	if (empty($_POST['price'])) {
		$errors[] = 'You forgot to enter a tattoo.';
	} else {
		$pri = mysqli_real_escape_string($dbc, trim($_POST['price']));
	}
	
	$note = mysqli_real_escape_string($dbc, trim($_POST['notes']));

	if (empty($errors)) { // If everything's OK.

		// Register the user in the database...

        // Make the query:
    		$q0 = "INSERT INTO 4sale (animal_id, description, price, notes) VALUES ('$an', '$des', '$pri', '$note')";
    		$r0 = @mysqli_query ($dbc, $q0); // Run the query.
    		if ($r0) { // If it ran OK.

			$q2 = "SELECT aname FROM animal WHERE animal_id LIKE '$an'";
			$r2 = mysqli_query($dbc, $q2);  // Run the query.
			$row = mysqli_fetch_array($r2, MYSQLI_ASSOC);
			$ab = $row['aname'];

    			// Print a message:
    			echo '<h1>Thank you!</h1>
    				  <p>' . "$ab" . ' is now registered on the For Sale database.</p><p><br /></p>
					  <div id="smallink">
					  <a href="reg4s.php" title="Add Another Sale">Add Another Sale</a><br /><br />
					  <a href="upd4s.php" title="Amend a Sale">Amend a Sale</a>
					  </div>';

    		} else { // If it did not run OK.

    			// Public message:
    			echo '<h1>System Error</h1>
    			<p class="error">A problem occurred.</p>';

    			// Debugging message:
    			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';


    	 } // End of if ($r) IF.

		mysqli_close($dbc); // Close the database connection.

		// Include the footer and quit the script:
		include ('includes/4sfoot.html');
		include ('includes/footer.html');
		exit();


	} else { // Report the errors.

		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';

	} // End of if (empty($errors)) IF.

	mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.

// GRB added to drive dropdown
require_once ('../mysqli_connect.php'); // Connect to the db.
?>

<h1>Register Details of a Sale</h1>
  <form name = "Register4Sale" action="reg4s.php" method="post">
	<fieldset>
	<legend>Register a Sale</legend><br />
    <table summary="form" align="center" border="0" width ="400px">
      <tr>
        <td align="right">Name:</td>
        <td valign="top"><select name="an_id" STYLE="width: 120px"><option selected>Select...</option> 
        <?php dropdown3('animal_id', 'aname', 'roles.role_id', 'aname', '%'); ?>
        </select></td>
      </tr>
      <tr>
        <td align="right">Description:</td>
        <td valign="top"><textarea name="description" align="left" cols="40" rows="10"></textarea><br></td>
      </tr>
      <tr>
  	    <td align="right">Price:</td>
        <td valign="top"><input type="text" name="price" size="4" maxlength="6"  /> </td>
      </tr>
      <tr>
  	    <td align="right">Notes:</td>
        <td valign="top"><textarea name="notes" align="left" cols="40" rows="3"></textarea><br></td>
      </tr>
      <tr>
        <td><br /><br /><br /></td>
    	<td><input type="submit" name="submit" value="Register" />
    	<input type="hidden" name="submitted" value="TRUE" />
        <input type="reset" name="clear" value="Clear" /></td>
      </tr>
    </table>
  </fieldset>
  </form>

<?php
	include ('includes/4sfoot.html');
	include ('includes/footer.html');
?>