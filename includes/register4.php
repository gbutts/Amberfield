<?php # Script 8.5 - register4.php
    session_start(); // Start the session.
    $pname = 'Register an Animal on the Database';
    $version  = 'v3.0.6';
    $vdate = 'October 2008';
    $page_title = 'Amberfield :: '.$pname;

// Add 'status' field for revised animals table

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
	include ('includes/calscript.html');
	
// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	require_once ('../mysqli_connect.php'); // Connect to the db.

	$errors = array(); // Initialize an error array.

	// Check for an animal name.
	if (empty($_POST['animal_name'])) {
		$errors[] = 'You forgot to enter an animal name.';
	} else {
		$an = mysqli_real_escape_string($dbc, trim($_POST['animal_name']));
    }

	// Check for a stud id.
	if (empty($_POST['stud_id'])) {
		$errors[] = 'You forgot to enter a Stud ID.';
	} else {
		$si = mysqli_real_escape_string($dbc, trim($_POST['stud_id']));
	}

	// Check for a tattoo.
	if (empty($_POST['tattoo'])) {
		$errors[] = 'You forgot to enter a tattoo.';
	} else {
		$t = mysqli_real_escape_string($dbc, trim($_POST['tattoo']));
	}

	// Check for date of birth.
	if (empty($_POST['dob'])) {
		$errors[] = 'You forgot to enter a date of birth.';
	} else {
		$dob =  mysqli_real_escape_string($dbc, trim($_POST['dob']));
	}

	// Check for sex.
	if (empty($_POST['sex'])) {
		$errors[] = 'You forgot to enter the animal\'s gender.';
	} else {
		$s =  mysqli_real_escape_string($dbc, trim($_POST['sex']));
	}

	// Check for status.
	if (empty($_POST['role'])) {
		$errors[] = 'You forgot to enter the animal\'s status.';
	} else {
		$ro =  mysqli_real_escape_string($dbc, trim($_POST['role']));
	}


	if (empty($errors)) { // If everything's OK.

		// Register the user in the database...

        // Make the query:
    		$q = "INSERT INTO animal (aname, stud_id, tattoo, dob, sex, role) VALUES ('$an', '$si', '$t', '$dob', '$s', '$ro')";
    		$r = @mysqli_query ($dbc, $q); // Run the query.
    		if ($r) { // If it ran OK.

    			// Print a message:
    			echo '<h1>Thank you!</h1>
    				  <p>' . "$an" . ' is now registered on the database.</p><p><br /></p>
					  <div id="smallink">
					  <a href="register.php" title="Register a New Animal">Register a New Animal</a>
					  </div>';

    		} else { // If it did not run OK.

    			// Public message:
    			echo '<h1>System Error</h1>
    			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';

    			// Debugging message:
    			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';


    	 } // End of if ($r) IF.

		mysqli_close($dbc); // Close the database connection.

		// Include the footer and quit the script:
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

<h1>Register a Stud Animal</h1>
  <form name = "register" action="register.php" method="post">
    <table summary="form" align="center" border="0" width ="400px">
      <tr>
        <td align="right">Animal Name:</td>
        <td valign="top"><input type="text" name="animal_name" size="15" maxlength="20" value="<?php if (isset($_POST['animal_name'])) echo $_POST['animal_name']; ?>" /></td>
      </tr>
      <tr>
        <td align="right">Stud ID:</td>
        <td valign="top"><select name="stud_id" STYLE="width: 120px"><option selected>Select...</option>
        <?php dropdown('stud_id', 'stud_id', 'stud', 'stud_id'); ?>
        </select></td>
      </tr>
      <tr>
  	    <td align="right">Tattoo:</td>
        <td valign="top"><input type="text" name="tattoo" size="4" maxlength="4" value="<?php if (isset($_POST['tattoo'])) echo $_POST['tattoo']; ?>"  /> </td>
      </tr>
      <tr>
  	    <td align="right">Date of Birth <small>(YYYY-MM-DD)</small>:</td>
        <td  valign="bottom"><input type="date" name="dob" size="15" maxlength="10" value="<?php if (isset($_POST['dob'])) echo $_POST['dob']; ?>" /><a href="javascript:showCal('Calendar1')" style="text-decoration:none">&nbsp;&nbsp;<img src="images/calendar.jpg" width="16" height="15" border="0"  /></a>
		</td>
      </tr>
      <tr>
  	    <td align="right" >Sex (M,F):</td>
        <td valign="top"><input type="text" name="sex" size="4" maxlength="1" value="<?php if (isset($_POST['sex'])) echo $_POST['sex']; ?>" /></td>
      </tr>
      <tr>
  	    <td align="right">Status:</td>
        <td valign="top"><select name="role" STYLE="width: 120px"><option selected>Select...</option>
        <?php dropdown('role_id', 'role', 'roles', 'role'); ?>
        </select></td>
      </tr>
      <tr>
        <td><br /><br /><br /></td>
    	<td><input type="submit" name="submit" value="Register" />
    	<input type="hidden" name="submitted" value="TRUE" />
        <input type="reset" name="clear" value="Clear" /></td>
      </tr>
    </table>
  </form>

<?php
    include ('includes/footer.html');
?>