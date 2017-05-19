<?php # Based on Script 8.6 - view_users.php #1
// This script retrieves Genestar records from sire and dam and predicts the progeny.
    session_start(); // Start the session.
    $pname = 'Genestar Prediction';
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
    include ('includes/starpredict.inc.php');
    include ('includes/header.html');

require_once ('../mysqli_connect.php'); // Connect to the db.

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

  $sn = mysqli_real_escape_string($dbc,trim($_POST['sire_id']));
  $dn = mysqli_real_escape_string($dbc,trim($_POST['dam_id']));

  // Construct the sire query:
    $qs = "SELECT
          aname, M1, M2, M3, M4, T1, T2, T3, T4, F1, F2, F3, F4
          FROM gstar
          JOIN animal ON gstar.animal_id = animal.animal_id
          WHERE animal.animal_id LIKE '$sn'
          ORDER BY M1 ASC";
    $rs = @mysqli_query ($dbc, $qs); // Run the query.
    // Count the number of returned rows:
    $num1 = mysqli_num_rows($rs);
  // end sire query

  // Construct the dam query:
    $qd = "SELECT
          aname, M1, M2, M3, M4, T1, T2, T3, T4, F1, F2, F3, F4
          FROM gstar
          JOIN animal ON gstar.animal_id = animal.animal_id
          WHERE animal.animal_id LIKE '$dn'
          ORDER BY M1 ASC";
    $rd = @mysqli_query ($dbc, $qd); // Run the query.
    // Count the number of returned rows:
    $num2 = mysqli_num_rows($rd);
  // end dam query

  if ($num1 + $num2 > 1) { // If it ran OK, display the records.

  	// Fetch and print the sire records:
    	while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
            $sm1 = $row['M1'];
            $sm2 = $row['M2'];
            $sm3 = $row['M3'];
            $sm4 = $row['M4'];
            $st1 = $row['T1'];
            $st2 = $row['T2'];
            $st3 = $row['T3'];
            $st4 = $row['T4'];
            $sf1 = $row['F1'];
            $sf2 = $row['F2'];
            $sf3 = $row['F3'];
            $sf4 = $row['F4'];
            $sname = $row['aname'];

      // Determine the sire Genestars by adding up
            $ms = $sm1+$sm2+$sm3+$sm4;
            $ts = $st1+$st2+$st3+$st4;
            $fs = $sf1+$sf2+$sf3+$sf4;
        }

  	// Fetch and print the dam records:
    	while ($row = mysqli_fetch_array($rd, MYSQLI_ASSOC)) {
            $dm1 = $row['M1'];
            $dm2 = $row['M2'];
            $dm3 = $row['M3'];
            $dm4 = $row['M4'];
            $dt1 = $row['T1'];
            $dt2 = $row['T2'];
            $dt3 = $row['T3'];
            $dt4 = $row['T4'];
            $df1 = $row['F1'];
            $df2 = $row['F2'];
            $df3 = $row['F3'];
            $df4 = $row['F4'];
            $dname = $row['aname'];
        }

      // Determine the dam Genestars by adding up
            $md = $dm1+$dm2+$dm3+$dm4;
            $td = $dt1+$dt2+$dt3+$dt4;
            $fd = $df1+$df2+$df3+$df4;

       // Load the variables
         // Most Likely Case
         $lm1 = lcase ($sm1, $dm1);
         $lm2 = lcase ($sm2, $dm2);
         $lm3 = lcase ($sm3, $dm3);
         $lm4 = lcase ($sm4, $dm4);
         $lt1 = lcase ($st1, $dt1);
         $lt2 = lcase ($st2, $dt2);
         $lt3 = lcase ($st3, $dt3);
         $lt4 = lcase ($st4, $dt4);
         $lf1 = lcase ($sf1, $df1);
         $lf2 = lcase ($sf2, $df2);
         $lf3 = lcase ($sf3, $df3);
         $lf4 = lcase ($sf4, $df4);

         // Best Case
         $bm1 = bcase ($sm1, $dm1);
         $bm2 = bcase ($sm2, $dm2);
         $bm3 = bcase ($sm3, $dm3);
         $bm4 = bcase ($sm4, $dm4);
         $bt1 = bcase ($st1, $dt1);
         $bt2 = bcase ($st2, $dt2);
         $bt3 = bcase ($st3, $dt3);
         $bt4 = bcase ($st4, $dt4);
         $bf1 = bcase ($sf1, $df1);
         $bf2 = bcase ($sf2, $df2);
         $bf3 = bcase ($sf3, $df3);
         $bf4 = bcase ($sf4, $df4);

         // Worst Case
         $wm1 = wcase ($sm1, $dm1);
         $wm2 = wcase ($sm2, $dm2);
         $wm3 = wcase ($sm3, $dm3);
         $wm4 = wcase ($sm4, $dm4);
         $wt1 = wcase ($st1, $dt1);
         $wt2 = wcase ($st2, $dt2);
         $wt3 = wcase ($st3, $dt3);
         $wt4 = wcase ($st4, $dt4);
         $wf1 = wcase ($sf1, $df1);
         $wf2 = wcase ($sf2, $df2);
         $wf3 = wcase ($sf3, $df3);
         $wf4 = wcase ($sf4, $df4);

         // Add up the stars
         $lms = $lm1+$lm2+$lm3+$lm4;
         $lts = $lt1+$lt2+$lt3+$lt4;
         $lfs = $lf1+$lf2+$lf3+$lf4;

         $bms = $bm1+$bm2+$bm3+$bm4;
         $bts = $bt1+$bt2+$bt3+$bt4;
         $bfs = $bf1+$bf2+$bf3+$bf4;

         $wms = $wm1+$wm2+$wm3+$wm4;
         $wts = $wt1+$wt2+$wt3+$wt4;
         $wfs = $wf1+$wf2+$wf3+$wf4;

  // Present the best and worst case results in a table
    echo '<h1>Genestar Prediction</h1>';
    echo '<form action="gstar_predict.php" method="post">
          <fieldset>
          <legend>Genestar Prediction Results for ' . strtoupper($sname) . ' and ' . strtoupper($dname) . '</legend><br />
          <table align="center" border="0">
            <tr><td width="100px"></td><td><b>Marker</b></td><td></td>
               <td align="center"><b>1</b></td><td align="center"><b>2</b></td><td align="center"><b>3</b></td><td align="center"><b>4</b></td>
            </tr>
  		    <tr><td colspan="7"><hr size="1" /></td></tr>';

       echo '<tr><td><b>MOST LIKELY</b></td><td>Marbling</td><td>&nbsp;<img src="images/Star' . $lms . '.png" width="30" height="30" alt="' . $lms . ' Stars" />&nbsp;</td><td><img src="images/' . $lm1 . 's.png" width="31" height="18" alt="' . $lm1 . '" /></td><td><img src="images/' . $lm2 . 's.png" width="31" height="18" alt="' . $lm2 . '" /></td><td><img src="images/' . $lm3 . 's.png" width="31" height="18" alt="' . $lm3 . '" /></td><td><img src="images/' . $lm4 . 's.png" width="31" height="18" alt="' . $lm4 . '" /></td></tr>
              <tr><td rowspan="2"></td><td>Tenderness</td><td>&nbsp;<img src="images/Star' . $lts . '.png" width="30" height="30" alt="' . $lts . ' Stars" />&nbsp;</td><td><img src="images/' . $lt1 . 's.png" width="31" height="18" alt="' . $lt1 . '" /></td><td><img src="images/' . $lt2 . 's.png" width="31" height="18" alt="' . $lt2 . '" /></td><td><img src="images/' . $lt3 . 's.png" width="31" height="18" alt="' . $lt3 . '" /></td><td><img src="images/' . $lt4 . 's.png" width="31" height="18" alt="' . $lt4 . '" /></td></tr>
              <tr><td>Feed Efficiency</td><td>&nbsp;<img src="images/Star' . $lfs . '.png" width="30" height="30" alt="' . $lfs . ' Stars" />&nbsp;</td><td><img src="images/' . $lf1 . 's.png" width="31" height="18" alt="' . $lf1 . '" /></td><td><img src="images/' . $lf2 . 's.png" width="31" height="18" alt="' . $lf2 . '" /></td><td><img src="images/' . $lf3 . 's.png" width="31" height="18" alt="' . $lf3 . '" /></td><td><img src="images/' . $lf4 . 's.png" width="31" height="18" alt="' . $lf4 . '" /></td></tr>
             ';

  	   echo '<tr><td colspan="7"><hr size="1" /></td></tr>';

       echo '<tr><td><b>BEST</b></td><td>Marbling</td><td>&nbsp;<img src="images/Star' . $bms . '.png" width="30" height="30" alt="' . $bms . ' Stars" />&nbsp;</td><td><img src="images/' . $bm1 . 's.png" width="31" height="18" alt="' . $bm1 . '" /></td><td><img src="images/' . $bm2 . 's.png" width="31" height="18" alt="' . $bm2 . '" /></td><td><img src="images/' . $bm3 . 's.png" width="31" height="18" alt="' . $bm3 . '" /></td><td><img src="images/' . $bm4 . 's.png" width="31" height="18" alt="' . $bm4 . '" /></td></tr>
              <tr><td rowspan="2"></td><td>Tenderness</td><td>&nbsp;<img src="images/Star' . $bts . '.png" width="30" height="30" alt="' . $bts . ' Stars" />&nbsp;</td><td><img src="images/' . $bt1 . 's.png" width="31" height="18" alt="' . $bt1 . '" /></td><td><img src="images/' . $bt2 . 's.png" width="31" height="18" alt="' . $bt2 . '" /></td><td><img src="images/' . $bt3 . 's.png" width="31" height="18" alt="' . $bt3 . '" /></td><td><img src="images/' . $bt4 . 's.png" width="31" height="18" alt="' . $bt4 . '" /></td></tr>
              <tr><td>Feed Efficiency</td><td>&nbsp;<img src="images/Star' . $bfs . '.png" width="30" height="30" alt="' . $bfs . ' Stars" />&nbsp;</td><td><img src="images/' . $bf1 . 's.png" width="31" height="18" alt="' . $bf1 . '" /></td><td><img src="images/' . $bf2 . 's.png" width="31" height="18" alt="' . $bf2 . '" /></td><td><img src="images/' . $bf3 . 's.png" width="31" height="18" alt="' . $bf3 . '" /></td><td><img src="images/' . $bf4 . 's.png" width="31" height="18" alt="' . $bf4 . '" /></td></tr>
             ';

  	   echo '<tr><td colspan="7"><hr size="1" /></td></tr>';

  	   echo '<tr><td><b>WORST</b></td><td>Marbling</td><td>&nbsp;<img src="images/Star' . $wms . '.png" width="30" height="30" alt="' . $wms . ' Stars" />&nbsp;</td><td><img src="images/' . $wm1 . 's.png" width="31" height="18" alt="' . $wm1 . '" /></td><td><img src="images/' . $wm2 . 's.png" width="31" height="18" alt="' . $wm2 . '" /></td><td><img src="images/' . $wm3 . 's.png" width="31" height="18" alt="' . $wm3 . '" /></td><td><img src="images/' . $wm4 . 's.png" width="31" height="18" alt="' . $wm4 . '" /></td></tr>
             <tr><td rowspan="2"></td><td>Tenderness</td><td>&nbsp;<img src="images/Star' . $wts . '.png" width="30" height="30" alt="' . $wts . ' Stars" />&nbsp;</td><td><img src="images/' . $wt1 . 's.png" width="31" height="18" alt="' . $wt1 . '" /></td><td><img src="images/' . $wt2 . 's.png" width="31" height="18" alt="' . $wt2 . '" /></td><td><img src="images/' . $wt3 . 's.png" width="31" height="18" alt="' . $wt3 . '" /></td><td><img src="images/' . $wt4 . 's.png" width="31" height="18" alt="' . $wt4 . '" /></td></tr>
             <tr><td>Feed Efficiency</td><td>&nbsp;<img src="images/Star' . $wfs . '.png" width="30" height="30" alt="' . $wfs . ' Stars" />&nbsp;</td><td><img src="images/' . $wf1 . 's.png" width="31" height="18" alt="' . $wf1 . '" /></td><td><img src="images/' . $wf2 . 's.png" width="31" height="18" alt="' . $wf2 . '" /></td><td><img src="images/' . $wf3 . 's.png" width="31" height="18" alt="' . $wf3 . '" /></td><td><img src="images/' . $wf4 . 's.png" width="31" height="18" alt="' . $wf4 . '" /></td></tr>
             ';
  	echo '</table>';    // Close the table.
    echo '</fieldset>'; // Close the field

 // Parents Genestars.
    echo '<br /><br />
           <fieldset>
            <legend>Parental Genestar Results</legend><br />
            <table align="center" border="0">
             <tr><td width="100px"></td><td><b>Marker</b></td><td></td>
                 <td align="center"><b>1</b></td><td align="center"><b>2</b></td><td align="center"><b>3</b></td><td align="center"><b>4</b></td>
             </tr>
             <tr><td colspan="7"><hr size="1" /></td></tr>';

  		echo '<tr><td><b>' . strtoupper($sname) . '</b></td><td>Marbling</td><td>&nbsp;<img src="images/Star' . $ms . '.png" width="30" height="30" alt="' . $ms . ' Stars" />&nbsp;</td><td><img src="images/' . $sm1 . 's.png" width="31" height="18" alt="' . $sm1 . '" /></td><td><img src="images/' . $sm2 . 's.png" width="31" height="18" alt="' . $sm2 . '" /></td><td><img src="images/' . $sm3 . 's.png" width="31" height="18" alt="' . $sm3 . '" /></td><td><img src="images/' . $sm4 . 's.png" width="31" height="18" alt="' . $sm4 . '" /></td></tr>
              <tr><td rowspan="2"></td><td>Tenderness</td><td>&nbsp;<img src="images/Star' . $ts . '.png" width="30" height="30" alt="' . $ts . ' Stars" />&nbsp;</td><td><img src="images/' . $st1 . 's.png" width="31" height="18" alt="' . $st1 . '" /></td><td><img src="images/' . $st2 . 's.png" width="31" height="18" alt="' . $st2 . '" /></td><td><img src="images/' . $st3 . 's.png" width="31" height="18" alt="' . $st3 . '" /></td><td><img src="images/' . $st4 . 's.png" width="31" height="18" alt="' . $st4 . '" /></td></tr>
              <tr><td>Feed Efficiency</td><td>&nbsp;<img src="images/Star' . $fs . '.png" width="30" height="30" alt="' . $fs . ' Stars" />&nbsp;</td><td><img src="images/' . $sf1 . 's.png" width="31" height="18" alt="' . $sf1 . '" /></td><td><img src="images/' . $sf2 . 's.png" width="31" height="18" alt="' . $sf2 . '" /></td><td><img src="images/' . $sf3 . 's.png" width="31" height="18" alt="' . $sf3 . '" /></td><td><img src="images/' . $sf4 . 's.png" width="31" height="18" alt="' . $sf4 . '" /></td></tr>
             ';

  		echo '<tr><td colspan="7"><hr size="1" /></td></tr>';

  		echo '<tr><td><b>' . strtoupper($dname) . '</b></td><td>Marbling</td><td>&nbsp;<img src="images/Star' . $md . '.png" width="30" height="30" alt="' . $md . ' Stars" />&nbsp;</td><td><img src="images/' . $dm1 . 's.png" width="31" height="18" alt="' . $dm1 . '" /></td><td><img src="images/' . $dm2 . 's.png" width="31" height="18" alt="' . $dm2 . '" /></td><td><img src="images/' . $dm3 . 's.png" width="31" height="18" alt="' . $dm3 . '" /></td><td><img src="images/' . $dm4 . 's.png" width="31" height="18" alt="' . $dm4 . '" /></td></tr>
              <tr><td rowspan="2"></td><td>Tenderness</td><td>&nbsp;<img src="images/Star' . $td . '.png" width="30" height="30" alt="' . $td . ' Stars" />&nbsp;</td><td><img src="images/' . $dt1 . 's.png" width="31" height="18" alt="' . $dt1 . '" /></td><td><img src="images/' . $dt2 . 's.png" width="31" height="18" alt="' . $dt2 . '" /></td><td><img src="images/' . $dt3 . 's.png" width="31" height="18" alt="' . $dt3 . '" /></td><td><img src="images/' . $dt4 . 's.png" width="31" height="18" alt="' . $dt4 . '" /></td></tr>
              <tr><td>Feed Efficiency</td><td>&nbsp;<img src="images/Star' . $fd . '.png" width="30" height="30" alt="' . $fd . ' Stars" />&nbsp;</td><td><img src="images/' . $df1 . 's.png" width="31" height="18" alt="' . $df1 . '" /></td><td><img src="images/' . $df2 . 's.png" width="31" height="18" alt="' . $df2 . '" /></td><td><img src="images/' . $df3 . 's.png" width="31" height="18" alt="' . $df3 . '" /></td><td><img src="images/' . $df4 . 's.png" width="31" height="18" alt="' . $df4 . '" /></td></tr>
             ';
  	echo '</table>';    // Close the table.
    echo '</fieldset>'; // Close the field

	include ('includes/gsfoot.html');
    include ('includes/footer.html');
	exit();

  	mysqli_free_result ($rs); // Free up the resources.
  	mysqli_free_result ($rd); // Free up the resources.

  } else { // If no records were returned.

  	echo '<p class="error">One of the selected animals has no stars registered on the database. Try again.</p>';
  }
  //mysqli_close($dbc); // Close the database connection.
} // End of the main Submit conditional.
?>

<h1>Genestar Prediction</h1>
  <form action="gstar_predict.php" method="post">
  <fieldset>
   <legend>Pick a Sire and Dam</legend><br />
    <table summary="form" align="center" width ="60%">
      <tr>
        <td align="right">Sire Name:</td>
        <td valign="top"><select name="sire_id" STYLE="width: 120px"><option selected>Select...</option>
        <?php dropdown2('animal_id', 'aname', 'animal', 'M'); ?>
        </select></td>
      </tr>
      <tr>
        <td align="right">Dam Name:</td>
        <td valign="top"><select name="dam_id" STYLE="width: 120px"><option selected>Select...</option>
        <?php dropdown2('animal_id', 'aname', 'animal', 'F'); ?>
        </select></td>
      </tr>
      <tr>
        <td></td>
    	<td><br /><input type="submit" name="submit" value="Submit" />
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
