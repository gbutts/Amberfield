<?php # Script 8.2 - mysqli_connect.php

// This file contains the database access information. 
// This file also establishes a connection to MySQL 
// and selects the database.

// updated for iMac and awards03

// Set the database access information as constants:
DEFINE ('DB_USER', 'amberfie_shiraz');
DEFINE ('DB_PASSWORD', 'linkinZ4');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'amberfie_amf1');

// Make the connection:
$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );

?>