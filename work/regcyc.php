<?php # Script 8.5 - based on 'register5.php'
// This script inputs a breeder's cycling data.
// This is regcyc3.php - calendar script issues fixed. Config of cal is page specific and done in the html and js files.

    session_start(); // Start the session.
    $pname = 'Register a Cycle';
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
	include ('includes/calscript3.html');
	
// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	require_once ('../cgi-bin/mysqli_connect.php');

	$errors = array(); // Initialize an error array.

	// Check for an animal name.
	if (($_POST['animal_name']) == "Select...") {
		$errors[] = 'You forgot to enter a breeder name.';
	} else {
		$aa = mysqli_real_escape_string($dbc, trim($_POST['animal_name']));
    }

	// Check for a cycle date.
	if (empty($_POST['cdate'])) {
		$errors[] = 'You forgot to enter a cycle date.';
	} else {
		$cd = mysqli_real_escape_string($dbc, trim($_POST['cdate']));
	}

	// Check for an sire name.
	if (($_POST['siname']) == "Select...") {
		$errors[] = 'You forgot to enter an sire name.';
	} else {
		$sn = mysqli_real_escape_string($dbc, trim($_POST['siname']));
    }

	// Check for a quality.
	if (($_POST['cqual']) == "") {
		$errors[] = 'You forgot to enter a quality assessment.';
	} else {
		$cq = mysqli_real_escape_string($dbc, trim($_POST['cqual']));
	}

		$no =  mysqli_real_escape_string($dbc, trim($_POST['note']));

	if (empty($errors)) { // If everything's OK.

		// Register the recipient in the database...
        // Make the query:
    		$q0 = "INSERT INTO cycle (animal_id, cycdate, sire, quality, notes) VALUES ('$aa', '$cd', '$sn', '$cq', '$no')";
    		$r0 = @mysqli_query ($dbc, $q0); // Run the query.
    		if ($r0) { // If it ran OK.

			// Get the receiver's name
			$q1 = "SELECT aname FROM animal WHERE animal_id LIKE '$aa'"; 
	   		$r1 = @mysqli_query ($dbc, $q1); // Run the query.
			$row = mysqli_fetch_array($r1, MYSQLI_ASSOC);
			$ab = $row['aname'];

    			// Print a message:
    			echo '<h1>Success!</h1>
    				  <p>' . "$ab" . ' is now registered on the database.</p><p><br /></p>';

    		} else { // If it did not run OK.

    			// Public message:
    			echo '<h1>System Error</h1>
    			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';

    			// Debugging message:
    			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q0 . '</p>';


    	 } // End of if ($r0) IF.

		mysqli_close($dbc); // Close the database connection.

		// Include the footer and quit the script:
		include ('includes/cycfoot.html');
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

<h1>Register a Cycle</h1>
  <form name = "regcycle" action="regcyc.php" method="post">
  <fieldset>
   <legend>Cycle Details</legend><br />
    <table summary="form" align="center" border="0" width ="90%">
      <tr>
        <td align="right">Animal Name:</td>
        <td valign="top" value="<?php if (isset($_POST['animal_name'])) echo $_POST['animal_name']; ?>"><select name="animal_name" STYLE="width: 120px"><option selected>Select...</option>
        <?php dropdown3('animal_id', 'aname', 'roles.role_id', 'aname', 'F'); ?>
      </tr>
      <tr>
  	    <td align="right">Cycle Date:</td>
        <td  valign="middle"><input type="date" name="cdate" size="15" maxlength="10" value="<?php if (isset($_POST['cdate'])) echo $_POST['cdate']; ?>" />&nbsp;&nbsp;<a href="javascript:showCal('Calendar1')" style="text-decoration:none"><img src="images/calendar.jpg" width="16" height="15" border="0"  /></a>
		</td>
      </tr>
      <tr>
        <td align="right">Sire Name:</td>
        <td valign="top" value="<?php if (isset($_POST['siname'])) echo $_POST['siname']; ?>"><select name="siname" STYLE="width: 120px"><option selected>Select...</option>
        <?php dropdown3('animal_id', 'aname', 'roles.role_id', 'aname', 'M'); ?>
      </tr>
      <tr>
  	    <td align="right" >Quality:</td>
        <td valign="top"><input type="text" name="cqual" size="3" maxlength="1" value="<?php if (isset($_POST['cqual'])) echo $_POST['cqual']; ?>" /></td>
      </tr>
      <tr>
  	    <td align="right">Notes:</td>
        <td valign="top"><textarea name="note" align="left" cols="40" rows="3" value="<?php if (isset($_POST['note'])) echo $_POST['note']; ?>"></textarea><br></td>
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
	include ('includes/cycfoot.html');
    include ('includes/footer.html');
?>