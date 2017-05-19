<?php
    $pname = 'For Sale';
    $version  = 'v4.5.0'; //Mack for slae
	$last_modified = filemtime("for_sale.php");
	$vdate = date("d F Y", $last_modified);
    $page_title = 'Amberfield :: '.$pname;
    include ('includes/header.html');
?>

<h1>For Sale</h1>

    <p>We rarely advertise specific animals for sale on this page. All our animals are for sale 
    if the price is right. We invite you to contact us at geoff(at)amberfield(dot)com(dot)au to discuss your
    needs.</p> 

<h3>Show Bulls and Heifers</h3>

    <p>For a short time, we have Amberfield Mack, a purebred bull for sale. He is currently unregistered but we are willing 
	and able to put him through the process if registration is required. Contact us at the above address if you are interested.</p>
    
    <p>We still produce show quality animals with strong genetics, however recently due to our change of focus, we
    have not been registering them. However, if you are interested in starting up a show herd or are 
    wanting to increase the genetic quality of your herd please contact us and we can discuss
    a solution for you.</p> 
    
    <p>Our <a href="results.php">show results</a> speak for themselves.</p> 
    
<h3>Bull Lease</h3>

    <p>Some of our bulls are available for lease for up to three months. Click <a href="rollcall.php">here</a> and search for <strong>'Male'</strong> and <strong>'Still owned by Amberfield'</strong>.</p>

    <p>Please contact us if you are interested.</p><br />

<?php
    include ('includes/navbar.html');
    include ('includes/footer.html');
?>
