<?php
    $pname = 'Location';
    $version  = 'v4.0.0';
    $vdate = 'May 2011';
    $page_title = 'Amberfield :: '.$pname;
    include ('includes/mapheader.html');
?>

    <h1>Where is Amberfield?</h1>

    <center>
      <div class="gmap" id="map"></div>

      <p class="pinvis">To zoom, use the buttons at the top left. Drag the map to scroll around.</p>
    </center>

<?php
    include ('includes/navbar.html');
    include ('includes/footer.html');
?>
