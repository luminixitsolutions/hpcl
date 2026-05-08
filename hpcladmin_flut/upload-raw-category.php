<?php 
include_once 'config.php';
/*$sql = "SELECT * FROM tbl_users_bill WHERE Roll=5 AND id!=9";
$row = getList($sql);
foreach($row as $result){
    $id = $result['id'];
    $sql2 = "SELECT * FROM tbl_raw_prod_category WHERE CreatedBy=9";
    $row2 = getList($sql2);
    foreach($row2 as $result2){
        $Name = $result2['Name'];
         $sql3 = "INSERT INTO tbl_raw_prod_category SET Name='$Name',Status=1,srno='".$result2['srno']."',CreatedDate='".date('Y-m-d')."',CreatedBy='$id'";
        $conn->query($sql3);
    }
}*/
$frid = 771;
$sql = "SELECT * FROM tbl_raw_prod_category WHERE  Name IN ('ASSETS','CONSUMABLES','REPAIRING MANTANANCE','PACKED MRP PRODUCTS') AND CreatedBy='$frid'";
$row = getList($sql);
foreach($row as $result){
    $CatId = $result['id'];
    $sql2 = "SELECT * FROM tbl_raw_prod_sub_category WHERE FrId=717 AND id='$CatId'";
    $row2 = getList($sql2);
    foreach($row2 as $result2){
        $Name = $result2['Name'];
          $sql3 = "INSERT INTO tbl_raw_prod_sub_category SET Name='$Name',Status=1,FrId='$frid',CatId='$CatId',CreatedBy='$frid',CreatedDate='".date('Y-m-d')."'";
        $conn->query($sql3);  
    } 
}
?>