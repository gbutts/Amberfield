<?php # Script 11.12 - login.php #4
    $pname = 'Log In';
    $version  = 'v4.1.0';
	$vdate = date("d F Y", getlastmod());
    $page_title = 'Amberfield :: '.$pname;

if (isset($_POST['submitted'])) {

	require_once ('includes/login_functions.inc.php');
	require_once ('../cgi-bin/mysqli_connect.php');
	list ($check, $data) = check_login($dbc, $_POST['uname'], $_POST['pass']);
	
	if ($check) { // OK!
			
		// Set the session data:.
		session_start();
		$_SESSION['user_id'] = $data['user_id'];
		$_SESSION['first_name'] = $data['fname'];
		
		// Store the HTTP_USER_AGENT:
		$_SESSION['agent'] = md5($_SERVER['HTTP_USER_AGENT']);
		
		// Redirect:
		$url = absolute_url ('loggedin.php');
		header("Location: $url");
		exit();
			
	} else { // Unsuccessful!
		$errors = $data;
	}
		
	mysqli_close($dbc);

} // End of the main submit conditional.

include ('includes/login_page.inc.php');
?>
