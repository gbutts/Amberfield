<?php
    $pname = 'Search';
    $version  = 'v4.2.0'; //Validate, new header for js
	$vdate = date("d F Y", getlastmod());
    $page_title = 'Amberfield :: '.$pname;
    include ('includes/searchheader.html');
?>

<h1>Search Page</h1>

    <p>Use this page to search the Amberfield Lowline Cattle Stud web site. A new window will open.</p>

<?php
    include ('includes/searchbox.html');
    include ('includes/navbar.html');
    include ('includes/footer.html');
?>
