<?php
error_reporting(0);
$con=mysqli_connect('localhost','root','','kwickbill_happy_shop'); 
if (! $con) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
define( 'API_ACCESS_KEY', 'AAAAmKb7giQ:APA91bE0HOCOEBnPt7dnFJXx4hgmWIvr2Xnaev3nKfJvJ4E5e3i_9THUYH5wdcjDV_E7guMjlHqTVnQqauBm_Wzz9YutLVfDxBSqYLjWGZ9r0afUlOTmFu_de70A16inM2wB5LIJ8KnQ');
$SiteUrl = "http://mahabuddy.com/franchisebillsoftapp";
?>
