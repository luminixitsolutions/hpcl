<?php
session_start();
$sessionid = session_id();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'fetch_expense_details'){
$id = $_POST['id'];
$sql7 = "SELECT te.*,tu.Fname,tu.Lname,tu.Photo AS Uphoto FROM tbl_vendor_expenses te LEFT JOIN tbl_users tu ON tu.id=te.UserId WHERE te.id='$id'";
$row = getRecord($sql7);

$sql88 = "SELECT SUM(creditAmt) As Credit,SUM(debitAmt) As Debit FROM (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as creditAmt,(case when Status='Dr' then sum(Amount) else 0 end) as debitAmt FROM wallet WHERE UserId='".$row['UserId']."' GROUP BY Status) as a";
    $row88 = getRecord($sql88);
    $Wallet = $row88['Credit'] - $row88['Debit'];
    
echo json_encode(array('rowdata'=>$row,'walletamt'=>$Wallet));
    
}



if($_POST['action'] == 'MgrSave'){
    $id = $_POST['id'];
    $EmpId = $_POST['EmpId'];
     $ApproveDate = addslashes(trim($_POST["ApproveDate"]));
     $MannagerComment = addslashes(trim($_POST["BranchComment"]));
  $MgrAmount = addslashes(trim($_POST["BranchAmount"]));
  $ManagerStatus = addslashes(trim($_POST["BranchStatus"]));
   $CreatedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');

 //$TicketNo= "#".rand(1000,9999);
    $query2 = "UPDATE tbl_vendor_expenses SET BranchApproveDate='$ApproveDate',BranchComment='$MannagerComment',BranchAmount='$MgrAmount',BranchStatus='$ManagerStatus',BranchBy='$user_id' WHERE id = '$id'";
  $conn->query($query2);
   
    $sql33 = "SELECT Fname As AccName FROM tbl_users WHERE id='$user_id'";
  $row33 = getRecord($sql33);
  $AccName = $row33['AccName'];
   if($ManagerStatus=='1'){ 
  $status = "<span style='color:green;'>Approved<br>By $AccName </span>";
  } 
  else if($ManagerStatus=='2'){ 
  $status = "<span style='color:red;'>Rejected <br> By $AccName </span>";}
  else {
 $status = '<a href="javascript:void(0)" data-toggle="modal" data-target="#modals-default" id="add_button" onclick="getExpenseVal('.$id.')">Approve By Popup</a>';
  } 
  
 echo json_encode(array('response'=>1,'status'=>$status,'id'=>$id));
  
}
?>