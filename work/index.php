<?php
// This is the 'front' page for the work directory
    session_start(); // Start the session.
    $pname = 'Admin Front Page';
    $version  = 'v4.1.0';
	$vdate = date("d F Y", getlastmod());
    $page_title = 'Amberfield :: '.$pname;

    // Which header to load:
    if ( (isset($_SESSION['user_id'])) && (!strpos($_SERVER['PHP_SELF'], 'logout.php')) ) {
	     include ('includes/header.html');
       } else {
	     include ('includes/adminheader.html');
}?>

<h1>Admin Front Page</h1>

<p>This is the front page for the administrative directories.</p>

<p>This area is used to administrate the databases.
There are no visitor options here, if you are looking for the champions page, please <a href="/results.php">click</a> here.</p>

<p>Administrators, <a href="login.php">log in</a> here. </p>

<?php
include ('includes/footer.html');
?>
