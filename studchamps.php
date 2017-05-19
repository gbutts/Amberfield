<?php # Script 8.5 - studchamps2.php
// Fix English in $records statement.

    $pname = 'Show Champions';
    $version  = 'v4.2.0';
	$vdate = date("d F Y", getlastmod());
    $page_title = 'Amberfield :: '.$pname;

    include ('includes/dropdown.inc.php');
	include ('work/includes/conv_num.inc.php');
    include ('includes/header.html');

	require_once ('cgi-bin/mysqli_connect.php'); // Connect to the db.  *Move outside the if (isset)?

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize an error array. *This appears to work. *Don't touch.
    // However, is it required given the drop downs?

	// Load the main variables.
		$yr = mysqli_real_escape_string($dbc, trim($_POST['year']));
		$an = mysqli_real_escape_string($dbc,trim($_POST['animal_id']));
		$sh = mysqli_real_escape_string($dbc, trim($_POST['show_id']));
		$aw = mysqli_real_escape_string($dbc, trim($_POST['ribbon']));

	// Load the others.
		$sx = mysqli_real_escape_string($dbc, trim($_POST['sex']));
		$ro = mysqli_real_escape_string($dbc, trim($_POST['royal']));
		$ow = mysqli_real_escape_string($dbc, trim($_POST['owned']));
		$so = mysqli_real_escape_string($dbc, trim($_POST['here']));

    // Sort the checkboxes
        if (empty($ro))    // box unchecked
            $ro = '%';      // make it 'any' (ie royals + regionals)

        if (empty($ow))    // box unchecked
            $ow = '%';      // make it 'any'  (ie including not owned when won)

        if (empty($so))    // box unchecked
            $so = '%';      // make it 'any'  (ie including those now sold)

	if (empty($errors)) { // If everything's OK.

        // Construct the query:
        $q0 = "SELECT
              year, place, aname, sex, champ
              FROM (event JOIN animal ON event.animal_id = animal.animal_id)
              JOIN ribbon ON ribbon.award_id = event.award_id
              JOIN shows ON shows.show_id = event.show_id
              JOIN years ON years.year_id = event.year_id
              WHERE years.year_id LIKE '$yr' && 
              		animal.animal_id LIKE '$an' && 
              		shows.show_id LIKE '$sh' && 
              		ribbon.award_id LIKE '$aw' && 
              		animal.sex LIKE '$sx' && 
              		shows.royal LIKE '$ro' && 
              		event.owned LIKE '$ow' && 
              		animal.here LIKE '$so'
              ORDER BY year ASC, shows.show_id DESC, ribbon.award_id ASC ";

    		$r0 = @mysqli_query ($dbc, $q0); // Run the query.
    		if ($r0) { // If it ran OK.

            // Count the number of returned rows:
            $records = mysqli_num_rows($r0);

            	mysqli_close($dbc);

                echo '<h1>Amberfield Show Champions</h1>';

            	// Table header.
                echo '<div class="champs1">';
                echo '<br /><table align="center" border="1" cellspacing="0" cellpadding="5" summary="Champion Results" width="60%">
            	<tr align="center" bgcolor="#000000"><td><strong>Year</strong></td><td><strong>Animal</strong></td><td><strong>Show</strong></td><td><strong>Ribbon</strong></td></tr>';

            	// Fetch and print all the records:
            	while ($row = mysqli_fetch_array($r0, MYSQLI_ASSOC)) {
                      // Get correct gender
              		  if ($row['sex'] == 'M') { $sx1 = 'Bull';
                      } else {
  	                    $sx1 = 'Female';
                      }
                      // Get ribbon cell colour
                      if ($row['champ'] == 'Grand') { $col = 'navy';
                      } elseif ($row['champ'] == 'Supreme') { $col = 'red';
                      } elseif ($row['champ'] == 'Interbreed') { $col = 'navy';
                      } elseif ($row['champ'] == 'Junior') { $col = 'purple';
                      } elseif ($row['champ'] == 'Senior') { $col = 'purple';
                      } else { $col = 'green';
                      }
                    // Construct the row
            		echo '<tr>
            				<td>' . $row['year'] . '</td>
            				<td>' . $row['aname'] . '</td>
            				<td>' . $row['place'] . '</td>
            				<td bgcolor="' . $col . '">' . $row['champ'] . ' Champion ' . $sx1 . '</td></tr>';
                }
	            echo '</table>'; // Close the table.
                echo '</div>';

				// Print how many animals there are.
					// Get the English correct.
					if ($records == 1) {
						$prep = "is";
						$noun = "record";
						$succ = "success";
					} else {
						$prep = "are";
						$noun = "records";
						$succ = "successes";
					}
					// Convert numbers to lower case words
					$wrecords = strtolower(conv_num($records));
				
				// Print the statement
				echo "<br /><p class='copyright'>There $prep $wrecords $noun of our $succ in this category.</p>\n";


                // Link to reload page
                echo '<p class="smallinkL"><a href="studchamps.php" title="Conduct another search">Search Again</a><br /></p>';

            } else { // If it did not run OK.

    			// Public message:
    			echo '<h1>System Error</h1>
    			<p class="error">You could not be registered due to a system error. We apologise for any inconvenience.</p>';

    			// Debugging message:
    			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q0 . '</p>';

    	    } // End of if ($r0) IF.

		    // Include the footer and quit the script:
            include ('includes/navbar.html');
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

	mysqli_free_result ($r0); // Free up the resources.
	mysqli_close($dbc);

} // End of the main Submit conditional.
?>

<h1>Amberfield Show Champions</h1>
<div class="champs">
  <form action="studchamps.php" method="post">
   <fieldset>
   <legend>Enter the details</legend><br />
    <table summary="champs" align="center" width="500" border="0" cellpadding="1">
      <tr>
        <td align="right">Year:</td>
        <td valign="middle"><select name="year" style="width: 60px"><option value = '%' selected="selected">All</option>
        <?php dropdown('year_id', 'year', 'years', 'year'); ?>
        </select></td>
        <td align="right">Show Name:</td>
        <td valign="middle"><select name="show_id" style="width: 110px"><option value = '%' selected="selected">All</option>
        <?php dropdown(show_id, place, shows, place); ?>
        </select></td>
      </tr>
      <tr>
        <td align="right">Animal Name:</td>
        <td valign="middle"><select name="animal_id" style="width: 110px"><option value = '%' selected="selected">All</option>
        <?php dropdown('animal_id', 'aname', 'animal', 'aname'); ?>
        </select></td>
  	    <td align="right">Champion:</td>
        <td valign="middle"><select name="ribbon" style="width: 110px"><option value = '%' selected="selected">All</option>
        <?php dropdown('award_id', 'champ', 'ribbon', 'award_id'); ?>
        </select></td>
      </tr>
      <tr>
        <td></td>
        <td class="champs" colspan="3">Sex:&nbsp;&nbsp;
            <input type="radio" name="sex" value="%" checked="checked" />Both&nbsp;
            <input type="radio" name="sex" value="M" />Male&nbsp;
            <input type="radio" name="sex" value="F" />Female
        </td>
      </tr>
      <tr>
        <td></td>
        <td class="champs" colspan="3"><input type="checkbox" name="royal" value="Y" />&nbsp;&nbsp;Just Royal Shows?</td>
      </tr>
      <tr>
        <td></td>
        <td colspan="3"><input type="checkbox" name="owned" value="Y" />&nbsp;&nbsp;Owned by Amberfield when won?</td>
      </tr>
      <tr>
        <td></td>
        <td colspan="3"><input type="checkbox" name="here" value="Y" />&nbsp;&nbsp;Still Owned by Amberfield?</td>
      </tr>
      <tr><td></td></tr>
      <tr>
        <td></td>
    	<td class="champs"><input type="submit" class="button1" name="submit" value="Show" />
    	<input type="hidden" name="submitted" value="TRUE" /></td>
        <td></td>
      </tr>
    </table><br />
   </fieldset>
  </form>
</div>    <!--Close 'champs'-->
<?php
    include ('includes/navbar.html');
    include ('includes/footer.html');
?>
