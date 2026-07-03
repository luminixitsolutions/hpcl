<?php
error_reporting(0);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hpcl";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("connection failed : " . mysqli_connect_error());
}

$Proj_Title = "HPCL";
$SiteUrl = "https://rjorg.in/pandavcollege/";
date_default_timezone_set("Asia/Kolkata");
$CloseDate = "2024-12-31";
$OpenDate = "2025-01-01";

function getList($sql){
  global $conn;
    $row3 = [];
    $res2 = $conn->query($sql);
    if (!$res2) {
        return $row3;
    }
    while($row2 = $res2->fetch_assoc()){
        $row3[] = $row2;
    }
    return $row3;
}

function getRecord($sql){
  global $conn;
    $res2 = $conn->query($sql);
    if (!$res2) {
        return null;
    }
	$row2 = $res2->fetch_assoc();
    return $row2;
}

function getRow($sql){
  global $conn;
    $res2 = $conn->query($sql);
    if (!$res2) {
        return 0;
    }
	$row2 = mysqli_num_rows($res2);
    return $row2;
}
?>
