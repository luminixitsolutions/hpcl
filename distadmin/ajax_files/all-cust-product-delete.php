<?php
include('../config.php');
if(isset($_POST["checkbox_value"]))
{
 for($count = 0; $count < count($_POST["checkbox_value"]); $count++)
 {
  $sql = "DELETE FROM tbl_cust_products WHERE id = '".$_POST['checkbox_value'][$count]."'";
 $conn->query($sql);
 }
}


?>