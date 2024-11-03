<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
$sname= "localhost";
$uname= "root";
$password = "";
$db_name = "facility_booking";

$conn = mysqli_connect($sname, $uname, $password, $db_name);

if (!$conn) {
	echo "Connection failed!";
}


// function to echo/check output
function p($x, $b = false) {
    echo '<pre>';
    print_r($x);
    echo '</pre>';
    if (!$b) {
        die();
    }
}

// display error of sql/ php
error_reporting(E_ALL);
ini_set('display_errors', 'On');


?>