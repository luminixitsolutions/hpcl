<?php
error_reporting(0);
$con=mysqli_connect('localhost','root','','kwickbill_happy_shop');
if (! $con) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
define( 'API_ACCESS_KEY', 'AAAAK6kBL6M:APA91bEe7lkzF7TBR0DlkoaB948aczYljJtFyO0vdRQoZ-wIY_dsX9g3poPC2quiknasG5S7fMUqjG_9wl6PiHWQEJ3MvK8oHbUuERElp75DtwCK8Xwsr2dSpPIj0vk6ydOLzzqR_qah');
$SiteUrl = "http://mahabuddy.com/mobapp";
?>
