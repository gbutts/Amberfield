<?php # Script 11.11 - logout.php #2
// This page lets the user logout.
session_start(); // Access the existing session.
    $pname = 'Logged Out';
    $version  = 'v4.1.0';
	$vdate = date("d F Y", getlastmod());
    $page_title = 'Amberfield :: '.$pname;


// If no session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])) {

	require_once ('includes/login_functions.inc.php');
	$url = absolute_url();
	header("Location: $url");
	exit();

} else { // Cancel the session.

	$_SESSION = array(); // Clear the variables.
	session_destroy(); // Destroy the session itself.
	setcookie ('PHPSESSID', '', time()-3600, '/', '', 0, 0); // Destroy the cookie.

}

// Set the page title and include the HTML header:
// $page_title = 'Logged Out!';
include ('includes/adminheader.html');

// Print a customized message:
echo "<h1>Logged Out!</h1>
<p>You are now logged out!</p>";

include ('includes/footer.html');
?>
