<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$sessionid = session_id();
$frid = $_POST['frid'];
$prodid = "";

 $number = count($_POST['ProdId']);
                    if ($number > 0) {
                        for ($i = 0; $i < $number; $i++) {
                            if (trim($_POST["CheckId"][$i] != '')) {
                                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                                if($CheckId == 1){
                                    $prodid .= $_POST['ProdId'][$i] . ",";
                                }
                            }
                        }
                    }
$AllocateProd = rtrim($prodid, ","); 
$sql = "UPDATE tbl_users SET AllocateRawProd='$AllocateProd' WHERE id='$frid'";
$conn->query($sql);
echo "<script>alert('Product Assign Successfully');window.location.href='view-allocate-raw-products-vendor.php';</script>";
?>