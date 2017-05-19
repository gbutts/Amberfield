<?php # Based on Script 8.6 - view_users.php #1
// This script retrieves all the records from the Genestar table.
session_start(); // Start the session.
$pname = 'Genestar Results';
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

require_once ('../mysqli_connect.php'); // Connect to the db.

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

$an = mysqli_real_escape_string($dbc,trim($_POST['animal_id']));

// Construct the query:
$q0 = "SELECT
	aname, M1, M2, M3, M4, T1, T2, T3, T4, F1, F2, F3, F4
	FROM gstar
	JOIN animal ON gstar.animal_id = animal.animal_id
	WHERE animal.animal_id LIKE '$an'
	ORDER BY M1 ASC";
$r0 = @mysqli_query ($dbc, $q0); // Run the query.

// Count the number of returned rows:
$num = mysqli_num_rows($r0);

if ($num > 0) { // If it ran OK, display the records.

	// Table header.
	echo '<h1>Genestar Results</h1>';
	echo '<form action="gstar_results.php" method="post">
	<fieldset>
	<legend>Genestar</legend><br />
	<table align="center" border="0">';

	// Fetch and print all the records:
	while ($row = mysqli_fetch_array($r0, MYSQLI_ASSOC)) {

// Determine the Genestars by adding up
$ms = $row['M1']+$row['M2']+$row['M3']+$row['M4'];
$ts = $row['T1']+$row['T2']+$row['T3']+$row['T4'];
$fs = $row['F1']+$row['F2']+$row['F3']+$row['F4'];

			echo '<tr><td width="100px"><b>' . strtoupper($row['aname']) . '</b></td><td><b>Marker</b></td><td></td>
			<td align="center"><b>1</b></td><td align="center"><b>2</b></td><td align="center"><b>3</b></td><td align="center"><b>4</b></td></tr>
			<tr><td></td><td>Marbling</td><td>&nbsp;<img src="images/Star' . $ms . '.png" width="30" height="30" alt="' . $ms . ' Stars" />&nbsp;</td><td><img src="images/' . $row['M1'] . 's.png" width="31" height="18" alt="' . $row['M1'] . '" /></td><td><img src="images/' . $row['M2'] . 's.png" width="31" height="18" alt="' . $row['M2'] . '" /></td><td><img src="images/' . $row['M3'] . 's.png" width="31" height="18" alt="' . $row['M3'] . '" /></td><td><img src="images/' . $row['M4'] . 's.png" width="31" height="18" alt="' . $row['M4'] . '" /></td></tr>
			<tr><td rowspan="2"></td><td>Tenderness</td><td>&nbsp;<img src="images/Star' . $ts . '.png" width="30" height="30" alt="' . $ts . ' Stars" />&nbsp;</td><td><img src="images/' . $row['T1'] . 's.png" width="31" height="18" alt="' . $row['T1'] . '" /></td><td><img src="images/' . $row['T2'] . 's.png" width="31" height="18" alt="' . $row['T2'] . '" /></td><td><img src="images/' . $row['T3'] . 's.png" width="31" height="18" alt="' . $row['T3'] . '" /></td><td><img src="images/' . $row['T4'] . 's.png" width="31" height="18" alt="' . $row['T4'] . '" /></td></tr>
			<tr><td>Feed Efficiency</td><td>&nbsp;<img src="images/Star' . $fs . '.png" width="30" height="30" alt="' . $fs . ' Stars" />&nbsp;</td><td><img src="images/' . $row['F1'] . 's.png" width="31" height="18" alt="' . $row['F1'] . '" /></td><td><img src="images/' . $row['F2'] . 's.png" width="31" height="18" alt="' . $row['F2'] . '" /></td><td><img src="images/' . $row['F3'] . 's.png" width="31" height="18" alt="' . $row['F3'] . '" /></td><td><img src="images/' . $row['F4'] . 's.png" width="31" height="18" alt="' . $row['F4'] . '" /></td></tr>
			';
	}
	echo '</table>';    // Close the table.
echo '</fieldset>'; // Close the field

include ('includes/gsfoot.html');
include ('includes/footer.html');
	exit();

	mysqli_free_result ($r0); // Free up the resources.

} else { // If no records were returned.

$q1 = "SELECT aname FROM animal WHERE animal_id LIKE '$an'";
$r1 = mysqli_query($dbc, $q1);  // Run the query.
$row1 = mysqli_fetch_array($r1, MYSQLI_ASSOC);
$aa = $row1['aname'];
	echo '<p class="error">' . "$aa" . ' is not registered on the database. Try again.</p>';

}
//mysqli_close($dbc); // Close the database connection.
} // End of the main Submit conditional.
?>

<h1>Genestar Results</h1>
	<form action="gstar_results.php" method="post">
	<fieldset>
	<legend>Pick an Animal</legend><br />
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
			<input type="hidden" name="submitted" value="TRUE" />
		<input type="reset" name="clear" value="Clear" /></td>
		</tr>
		</table>
	</fieldset>
	</form>

<?php
    include ('includes/gsfoot.html');
	include ('includes/footer.html');
?>
