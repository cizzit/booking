<?php
// INCLUDES FILE - everything we need at each page
// do a: require_once('incl.php');
// to call it.

session_start();
// start a session

require_once('db.class.php');
$db = new db_class;
// create database class instance

if (!$db->connect('SQLSERVER', 'SQLUSER', 'SQLPASS', 'booking', true)) 
   $db->print_last_error(false);
// connect to database


//set Business Name
$businessname = "Company Name goes HERE";

// software version
$version = "1.0.4";
$lastdate = "27th November, 2009";

// require functions lists
require_once('fnctn.php');

?>
