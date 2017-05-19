<?php # no_sb.php

session_start(); // Access the existing session.
    $pname = 'No Stud Book Entry';
    $version  = 'v4.1.2'; // Studbook
	$vdate = date("d F Y", getlastmod());
    $page_title = 'Amberfield :: '.$pname;


include ('includes/header.html');

// Print a customized message:
echo '<h1>No Stud Book</h1>
<p align="center">There is no stud book reference in the database.<br />

<a href="javascript:window.close();">Close this window and return</a>
</p>';

include ('includes/footer.html');
?>
