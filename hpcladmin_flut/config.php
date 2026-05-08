<?php
/**
 * Safe request values (avoids PHP 8+ "Undefined array key" on $_POST / $_GET).
 */
function req($key, $default = null) {
	if (isset($_POST[$key])) {
		return $_POST[$key];
	}
	if (isset($_GET[$key])) {
		return $_GET[$key];
	}
	return $default;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kwickbill_happy_shop";

/// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
// check connection 
if($conn->connect_error) {
    die("connection failed : " . $conn->connect_error);
} else {
    // echo "Successfully Connected";
}
$Proj_Title = "HPCL";
$SiteUrl = "https://rjorg.in/pandavcollege/";
date_default_timezone_set("Asia/Kolkata");

function getList($sql){
  global $conn;  
    $res2 = $conn->query($sql);
    while($row2 = $res2->fetch_assoc()){
        $row3[] = $row2;
    }
    return $row3;
}

function getRecord($sql){
  global $conn;  
    $res2 = $conn->query($sql);
	$row2 = $res2->fetch_assoc();
    return $row2;
}

function getRow($sql){
  global $conn;  
    $res2 = $conn->query($sql);
	$row2 = mysqli_num_rows($res2);
    return $row2;
}
?>