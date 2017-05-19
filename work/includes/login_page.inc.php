<?php # Script 11.1 - login_page.inc.php

// This page prints any errors associated with logging in
// and it creates the entire login page, including the form.

// Include the header:
// $page_title = 'Login';
include ('includes/adminheader.html');

// Print any error messages, if they exist:
if (!empty($errors)) {
	echo '<h1>Error!</h1>
	<p class="error">The following error(s) occurred:<br />';
	foreach ($errors as $msg) {
		echo " - $msg<br />\n";
	}
	echo '</p><p>Please try again.</p>';
}

// Display the form:
?>
<h1>Login</h1>
<form action="login.php" method="post">
    <table summary="form" align="center" width ="300px">
     <tr>
      <td align="right">User Name:</td>
      <td><input type="text" name="uname" size="10" maxlength="10" /></td>
	 </tr>
     <tr>
      <td align="right">Password:</td>
      <td><input type="password" name="pass" size="10" maxlength="10" /></td>
	 </tr>
     <tr>
        <td></td>
    	<td><br /><br /><input type="submit" name="submit" value="Login" />
	    <input type="hidden" name="submitted" value="TRUE" /></td>
	 </tr>
    </table>
</form>

<?php // Include the footer:
include ('includes/footer.html');
?>
