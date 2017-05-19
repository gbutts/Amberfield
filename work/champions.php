<?php # Script 8.5 - champions.php
    session_start(); // Start the session.
    $pname = 'Register a Champion';
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


// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

require_once ('../cgi-bin/mysqli_connect.php'); // Connect to the db.

	$errors = array(); // Initialize an error array. *This appears to work. *Don't touch.

	// Check for a year.
	if (empty($_POST['year'])) {
		$errors[] = 'You forgot to enter a year.';
	} else {
		$yr = mysqli_real_escape_string($dbc, trim($_POST['year']));
    }

	// Check for an animal name.
	if (empty($_POST['animal_id'])) {
		$errors[] = 'You forgot to enter an animal name.';
	} else {
		$an = mysqli_real_escape_string($dbc,trim($_POST['animal_id']));
    }

	// Check for a show id.
	if (empty($_POST['show_id'])) {
		$errors[] = 'You forgot to enter a show location.';
	} else {
		$sh = mysqli_real_escape_string($dbc, trim($_POST['show_id']));
	}

	// Check for a ribbon.
	if (empty($_POST['ribbon'])) {
		$errors[] = 'You forgot to enter a ribbon.';
	} else {
		$aw = mysqli_real_escape_string($dbc, trim($_POST['ribbon']));
	}

	// Load owned.
		$ow = mysqli_real_escape_string($dbc, trim($_POST['owned']));

	if (empty($errors)) { // If everything's OK.

		// Register the award in the database...

// eg from http://www.wellho.net/solutions/php-mysql-and-php-enquiry-tool-for-ad-hoc-requirements.html
// $basequery = "select title,isbn,fullname,b_pivot.biid,b_pivot.aiid,b_pivot.pvid from (b_btab join b_pivot on b_btab.biid = b_pivot.biid) join b_atab on b_atab.aiid = b_pivot.aiid";

        // Turn '2002' into '2'
           $yr1 = substr($yr,3,1);

        // Make the query:  *Query works for manually entered data. *Don't touch.
    		$q2 = "INSERT INTO event (year_id, animal_id, show_id, award_id, owned) VALUES ('$yr1', '$an', '$sh', '$aw', '$ow')";
    		$r2 = @mysqli_query ($dbc, $q2); // Run the query.
    		if ($r2) { // If it ran OK.

    	    // Thank you page.
                // Grab the animals name
                $q3 = "SELECT aname FROM animal WHERE animal_id = $an";
                $r3 = mysqli_query($dbc, $q3);  // Run the query.
                mysqli_data_seek($r3, 0);       // seek to row no. 1
                $sa = mysqli_fetch_row($r3);    // fetch the row
                $ssa = $sa[0];                  // draw out the first value

                // Grab the ribbon
                $q4 = "SELECT champ FROM ribbon WHERE award_id = $aw";
                $r4 = mysqli_query($dbc, $q4);  // Run the query.
                mysqli_data_seek($r4, 0);       // seek to row no. 1
                $h = mysqli_fetch_row($r4);     // fetch the row
                $ch = $h[0];                    // draw out the first value

            	mysqli_close($dbc); // However, this one causes dropdowns to fail on second load.

		        echo '<h1>Thank you!</h1>';

    	        echo "<p> $ssa is now registered as a $ch Champion on the database.</p><p><br /></p>";

                echo '<div id="smallink">';
                    echo '<a href="champions.php" title="Add Another">Add Another</a>';
                echo '</div>';

    		} else { // If it did not run OK.

    			// Public message:
    			echo '<h1>System Error</h1>
    			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';

    			// Debugging message:
    			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q2 . '</p>';


    	 } // End of if ($r2) IF.

	// mysqli_free_result ($r2); // Free up the resources.
	// mysqli_close($dbc); // Close the database connection.

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

//	mysqli_close($dbc); // However, this one causes dropdowns to fail on second load.

} // End of the main Submit conditional.

// GRB added to drive dropdown
	require_once ('../../cgi-bin/mysqli_connect.php'); // Connect to the db.
?>

<h1>Register a Champion</h1>
  <form action="champions.php" method="post">
  <fieldset>
   <legend>Fill in the Champion's details</legend><br />
    <table summary="form" align="center" width ="300px">
      <tr>
        <td align="right">Year:</td>
        <td valign="top"><?php grbyear(); ?></td>
      </tr>
      <tr>
        <td align="right">Animal Name:</td>
        <td valign="top"><select name="animal_id" STYLE="width: 120px"><option selected>Select...</option>
        <?php dropdown('animal_id', 'aname', 'animal', 'aname'); ?>
        </select></td>
      </tr>
      <tr>
        <td align="right">Show Location:</td>
        <td valign="top"><select name="show_id" STYLE="width: 120px"><option selected>Select...</option>
        <?php dropdown('show_id', 'place', 'shows', 'show_id'); ?>
        </select></td>
      </tr>
      <tr>
  	    <td align="right">Ribbon:</td>
        <td valign="top"><select name="ribbon" STYLE="width: 120px"><option selected>Select...</option>
        <?php dropdown('award_id', 'champ', 'ribbon', 'award_id'); ?>
        </select></td>
      </tr>
  	  <tr>
        <td align="right" >Owned when won?:</td>
        <td>
            <input type="radio" name="owned" value="Y" checked="checked">Yes&nbsp;
            <input type="radio" name="owned" value="N">No
        </td>
      </tr>
      <tr>
        <td></td>
    	<td><br /><br /><input type="submit" name="submit" value="Register" />
    	<input type="hidden" name="submitted" value="TRUE" />
        <input type="reset" name="clear" value="Clear" /></td>
      </tr>
    </table>
   </fieldset>
  </form>

<?php
    include ('includes/footer.html');
?>