<?php require_once 'config.php';
$grpid = "";
$sql = "SELECT id
FROM tbl_customer_invoice_details
GROUP BY id
HAVING COUNT(id) > 1";
$row = getList($sql);
foreach($row as $result){
    $grpid.="'".$result['id']."',";
}
echo $grpid;

?>