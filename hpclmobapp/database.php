<?php
error_reporting(0);
$con=mysqli_connect('localhost','root','','kwickbill_happy_shop');
if (! $con) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
define( 'API_ACCESS_KEY', 'AAAAAy05i88:APA91bHUQFZhW78VkJCB38-ZOy1Fgg4BRkJq4chVX-kjPTLhhNs1ZuV_2V0u5BUOn2252z2rtaArkLHvHT1Kv8aOODgTqXbr3x8_-dwGhZipyqDx3CyxhB88Yo8ZwEJ7-Ohy8SwT6iNK');
$SiteUrl = "http://mahabuddy.com/exeapp";
?>
