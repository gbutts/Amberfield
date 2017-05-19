<?php
    $pname = 'File Not Found';
    $version  = 'v4.0.0';
    $vdate = 'May 2011';
    $page_title = $pname;
    include ('includes/header.html');
?>
<head>
  <script type="text/javascript" src="/js/search.js">
  </script>
</head>

<h1>File Not Found - Error 404</h1>

    <p>The page you are looking for doesn't appear to exist. If you have typed in a page
    reference, please check the spelling. Alternatively, click <a href="index.php" title="Home">here</a> for our main page or
    follow one of the links on the left.</p>

    <p>If you followed a link and ended up here, apologies. Please <a href=
    "feedback.php">contact us</a> and report the dead link.</br></br></p>

  <h2>Search the Site</h2>
<?php
    include ('includes/searchbox.html');
    include ('includes/navbar.html');
    include ('includes/footer.html');
?>
