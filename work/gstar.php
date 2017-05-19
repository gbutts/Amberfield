<?php # Script 8.5 - gstar.php
    session_start(); // Start the session.
    $pname = 'Register Genestar Results';
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
    // include ('includes/yeardropdown.inc.php');
    include ('includes/header.html');


// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	require_once ('../mysqli_connect.php'); // Connect to the db.  *Move outside the if (isset)?

	$errors = array(); // Initialize an error array. *This appears to work. *Don't touch.

	// Check for an animal name.
	if (empty($_POST['animal_id'])) {
		$errors[] = 'You forgot to enter an animal name.';
    } elseif ($_POST['animal_id'] == 'Select...' ) {
		$errors[] = 'You forgot to enter an animal name.';
	} else {
		$an = mysqli_real_escape_string($dbc,trim($_POST['animal_id']));
    }

	// Check for entry below 3.
	if (($_POST['m1']) > '2') {
		$errors[] = 'M1 cannot be greater than 2.';
	} else {
		$m1 = mysqli_real_escape_string($dbc, trim($_POST['m1']));
	}
	if (($_POST['m2']) > '2') {
		$errors[] = 'M2 cannot be greater than 2.';
	} else {
		$m2 = mysqli_real_escape_string($dbc, trim($_POST['m2']));
	}
	if (($_POST['m3']) > '2') {
		$errors[] = 'M3 cannot be greater than 2.';
	} else {
		$m3 = mysqli_real_escape_string($dbc, trim($_POST['m3']));
	}
	if (($_POST['m4']) > '2') {
		$errors[] = 'M4 cannot be greater than 2.';
	} else {
		$m4 = mysqli_real_escape_string($dbc, trim($_POST['m4']));
	}
	if (($_POST['t1']) > '2') {
		$errors[] = 'T1 cannot be greater than 2.';
	} else {
		$t1 = mysqli_real_escape_string($dbc, trim($_POST['t1']));
	}
	if (($_POST['t2']) > '2') {
		$errors[] = 'T2 cannot be greater than 2.';
	} else {
		$t2 = mysqli_real_escape_string($dbc, trim($_POST['t2']));
	}
	if (($_POST['t3']) > '2') {
		$errors[] = 'T3 cannot be greater than 2.';
	} else {
		$t3 = mysqli_real_escape_string($dbc, trim($_POST['t3']));
	}
	if (($_POST['t4']) > '2') {
		$errors[] = 'T4 cannot be greater than 2.';
	} else {
		$t4 = mysqli_real_escape_string($dbc, trim($_POST['t4']));
	}
	if (($_POST['f1']) > '2') {
		$errors[] = 'F1 cannot be greater than 2.';
	} else {
		$f1 = mysqli_real_escape_string($dbc, trim($_POST['f1']));
	}
	if (($_POST['f2']) > '2') {
		$errors[] = 'F2 cannot be greater than 2.';
	} else {
		$f2 = mysqli_real_escape_string($dbc, trim($_POST['f2']));
	}
	if (($_POST['f3']) > '2') {
		$errors[] = 'F3 cannot be greater than 2.';
	} else {
		$f3 = mysqli_real_escape_string($dbc, trim($_POST['f3']));
	}
	if (($_POST['f4']) > '2') {
		$errors[] = 'F4 cannot be greater than 2.';
	} else {
		$f4 = mysqli_real_escape_string($dbc, trim($_POST['f4']));
	}

	if (empty($errors)) { // If everything's OK.

		// Register the data in the database...

        // Make the query:
    		$q0 = "INSERT INTO gstar (animal_id, M1, M2, M3, M4, T1, T2, T3, T4, F1, F2, F3, F4 ) VALUES ('$an', '$m1', '$m2', '$m3', '$m4', '$t1', '$t2', '$t3', '$t4', '$f1', '$f2', '$f3', '$f4')";
    		$r0 = @mysqli_query ($dbc, $q0); // Run the query.
    		if ($r0) { // If it ran OK.

    	    // Thank you page.
                // Grab the animals name
                $q1 = "SELECT aname FROM animal WHERE animal_id = $an";
                $r1 = mysqli_query($dbc, $q1);  // Run the query.
                mysqli_data_seek($r1, 0);       // seek to row no. 1
                $sa = mysqli_fetch_row($r1);    // fetch the row
                $ssa = $sa[0];                  // draw out the first value

            	mysqli_close($dbc);

		        echo '<h1>Thank you!</h1>';

    	        echo "<p> $ssa is now registered on the Genestar database.</p><p><br /></p>";

                echo '<div id="smallink">';
                    echo '<a href="gstar.php" title="Add Another">Add Another</a>';
                echo '</div>';

    		} else { // If it did not run OK.

    			// Public message:
    			echo '<h1>System Error</h1>
    			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';

    			// Debugging message:
    			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q2 . '</p>';


    	 } // End of if ($r1) IF.

		// Include the footer and quit the script:
		include ('includes/footer.html');
		exit();

	} else { // Report the errors.

		echo '<h2>Error!</h2>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';

	} // End of if (empty($errors)) IF.


} // End of the main Submit conditional.

// GRB added to drive dropdown
	require_once ('../mysqli_connect.php'); // Connect to the db.
?>

<h1>Register a Genestar Profile</h1>
  <form action="gstar.php" method="post">
    <table summary="form" align="center" border="0">
      <tr>
        <td colspan="2">Animal Name:</td>
        <td colspan="2" valign="top"><select name="animal_id" STYLE="width: 120px"><option selected>Select...</option>
        <?php dropdown('animal_id', 'aname', 'animal', 'aname'); ?>
        </select></td>
      </tr>
      <tr>
        <td colspan="4"><br />Marbling</td>
      </tr>
      <tr>
        <td valign="top">M1: <input type="text" name="m1" size="1" maxlength="1" value="<?php if (isset($_POST['m1'])) echo $_POST['m1']; ?>" /></td>
        <td valign="top">M2: <input type="text" name="m2" size="1" maxlength="1" value="<?php if (isset($_POST['m2'])) echo $_POST['m2']; ?>" /></td>
        <td valign="top">M3: <input type="text" name="m3" size="1" maxlength="1" value="<?php if (isset($_POST['m3'])) echo $_POST['m3']; ?>" /></td>
        <td valign="top">M4: <input type="text" name="m4" size="1" maxlength="1" value="<?php if (isset($_POST['m4'])) echo $_POST['m4']; ?>" /></td>
      </tr>
        <td colspan="4"><br />Tenderness</td>
      <tr>
        <td valign="top">T1: <input type="text" name="t1" size="1" maxlength="1" value="<?php if (isset($_POST['t1'])) echo $_POST['t1']; ?>" /></td>
        <td valign="top">T2: <input type="text" name="t2" size="1" maxlength="1" value="<?php if (isset($_POST['t2'])) echo $_POST['t2']; ?>" /></td>
        <td valign="top">T3: <input type="text" name="t3" size="1" maxlength="1" value="<?php if (isset($_POST['t3'])) echo $_POST['t3']; ?>" /></td>
        <td valign="top">T4: <input type="text" name="t4" size="1" maxlength="1" value="<?php if (isset($_POST['t4'])) echo $_POST['t4']; ?>" /></td>
      </tr>
      <tr>
        <td colspan="4"><br />Feed Efficiency</td>
      </tr>
      <tr>
        <td valign="top">F1: <input type="text" name="f1" size="1" maxlength="1" value="<?php if (isset($_POST['f1'])) echo $_POST['f1']; ?>" /></td>
        <td valign="top">F2: <input type="text" name="f2" size="1" maxlength="1" value="<?php if (isset($_POST['f2'])) echo $_POST['f2']; ?>" /></td>
        <td valign="top">F3: <input type="text" name="f3" size="1" maxlength="1" value="<?php if (isset($_POST['f3'])) echo $_POST['f3']; ?>" /></td>
        <td valign="top">F4: <input type="text" name="f4" size="1" maxlength="1" value="<?php if (isset($_POST['f4'])) echo $_POST['f4']; ?>" /></td>
      </tr>
      <tr>
    	<td colspan="2"><br /><input type="submit" name="submit" value="Register" />
    	<input type="hidden" name="submitted" value="TRUE" /></td>
        <td colspan="2"><br /><input type="reset" name="clear" value="Clear" /></td>
     </tr>
    </table>
  </form>

<?php
	// Lower links
    include ('includes/gsfoot.html');
    include ('includes/footer.html');
?>