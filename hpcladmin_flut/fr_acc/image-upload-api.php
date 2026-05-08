<?php
session_start();
include_once 'config.php';
$name = $_REQUEST["name"];
$image = $_REQUEST["image"];
$prdid = $_REQUEST['prdid'];
$flag = $_REQUEST['flag'];
$action = $_REQUEST['action'];
$pageval = $_REQUEST['pageval'];
$decodedImage = base64_decode("$image");
$return = file_put_contents("images/" . $name . "", $decodedImage);
$response = array();
if ($return !== false) {
    $response['success'] = 1;
    if($pageval == 'pageval'){
        if($action == 'save'){
            $sql = "UPDATE tbl_cust_products SET Photo='$decodedImage' WHERE TempPrdId='$prdid'";
            $conn>query($sql);
        }
    }
    $response['message'] = "Your image has ploaded successfully with Retrofit";
} else {
    $response['success'] = 0;
    $response['message'] = "Image failed to pload";
}
echo json_encode($response);

?>