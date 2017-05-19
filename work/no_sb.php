<?php # no_sb.php

session_start(); // Access the existing session.
    $pname = 'No Stud Book Entry';
    $version  = 'v3.0.5';
    $vdate = 'September 2008';
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

// Print a customized message:
echo "<h1>No Stud Book</h1>
<p>There is no stud book reference in the database.</p>";

echo '<div id="smallink">';
echo '<a href="javascript:window.close();">Close this window and return</a>';
echo '</div>';

include ('includes/footer.html');
?>
