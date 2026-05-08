<?php
session_start();
include_once '../config.php';
require_once ("../database.php");
$user_id = $_SESSION['User']['id'];
$sql2 = "SELECT Fname FROM tbl_users WHERE id='$user_id'";
$row2 = getRecord($sql2);
$FromName = $row2['Fname'];
if($_POST['action']=='SendMoney'){
    $UserId2 = $_POST['UserId2'];
    $Amount = $_POST['Amount'];
    $Phone = $_POST['Phone'];
    $Fname = addslashes(trim($_POST['Fname']));
    $Narration2 = addslashes(trim($_POST['Narration']));
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');
    if($Narration2 == ''){
        $Narration = "Transfer Amount to $Fname";
        $Narration3 = "Amount Received From $FromName";
    }
    else{
        $Narration = $Narration2;
        $Narration3 = "Amount Received From $FromName";
    }
    $sql = "INSERT INTO wallet SET UserId='$UserId2',Amount='$Amount',Narration='$Narration3',Status='Cr',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
    $conn->query($sql);
    
    $sql2 = "INSERT INTO wallet SET UserId='$user_id',Amount='$Amount',Narration='$Narration',Status='Dr',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
    $conn->query($sql2);
    
     //send notification to receiver user
    $Title = "$FromName paid you Rs. $Amount";
    $Message = $Narration;
    $sql73 = "SELECT Tokens,id FROM tbl_users WHERE id='$UserId2' AND Tokens!=''";
    $data=mysqli_query($con,$sql73);
        
        while($row=mysqli_fetch_array($data))
        {
            
            $ReceiverId = $row['id'];
            $sql = "INSERT INTO tbl_notifications SET SenderId='$user_id',ReceiverId='$UserId2',Title='$Title',Message='$Narration3',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
            $conn->query($sql);

            $title = $Title;
            $body =  $Message;
            $reg_id = $row[0];
            $registrationIds = array($reg_id);
            //$imgurl = "https://rjorg.in/teasoftware/uploads/44_2504_tanduri-chai.jpg";
            //$url = "$SiteUrl/profile.php?id=$UserId";
            include '../../incnotification.php';
         
        }
        
        
         //send notification to sender user
    $Title = "Rs. $Amount Transfer Amount to $Fname";
    $Message = $Narration;
    $sql73 = "SELECT Tokens,id FROM tbl_users WHERE id='$user_id' AND Tokens!=''";
    $data=mysqli_query($con,$sql73);
        
        while($row=mysqli_fetch_array($data))
        {
            
            $ReceiverId = $row['id'];
            $sql = "INSERT INTO tbl_notifications SET SenderId='$user_id',ReceiverId='$user_id',Title='$Title',Message='$Narration',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
            $conn->query($sql);

            $title = $Title;
            $body =  $Message;
            $reg_id = $row[0];
            $registrationIds = array($reg_id);
            //$imgurl = "https://rjorg.in/teasoftware/uploads/44_2504_tanduri-chai.jpg";
            //$url = "$SiteUrl/profile.php?id=$UserId";
            include '../../incnotification.php';
         
        }
    
    echo json_encode(array('amount'=>$Amount,'paidto'=>$Fname));
}

if($_POST['action']=='checkPhone'){
    $Phone = $_POST['Phone'];
    $sql = "SELECT * FROM tbl_users WHERE Phone='$Phone'";
    $rncnt = getRow($sql);
    $row = getRecord($sql);
    if($rncnt > 0){
        echo json_encode(array('Status'=>1,'Name'=>$row['Fname'],'id'=>$row['id']));
        
    }
    else{
        echo json_encode(array('Status'=>0));
    }
    
}

if($_POST['action']=='WithdrawReq'){
    $Amount = $_POST['Amount'];
    $Narration = addslashes(trim($_POST['Narration']));
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');
    
    $sql2 = "SELECT * FROM tbl_withdraw_request WHERE UserId='$user_id' AND Status=0";
    $rncnt2 = getRow($sql2);
    if($rncnt2 > 0){
        echo 0;
    }
    else{
    $sql = "INSERT INTO tbl_withdraw_request SET UserId='$user_id',Amount='$Amount',ReqDate='$CreatedDate',ReqTime='$CreatedTime',Status=0,Narration='$Narration'";
    $conn->query($sql);
    echo 1;
    }
    
}
if($_POST['action']=='userLists'){
    $Phone = $_POST['Phone'];
    $sql = "SELECT * FROM tbl_users WHERE Phone LIKE '%".$Phone."%'";
    $row = getList($sql);
    foreach($row as $result){?>

    <li class="list-group-item">
            <a href="javascript:void(0)" onclick="getUserDetails('<?php echo $result['Phone'];?>')">
                                <div class="row align-items-center">
                                    <div class="col-auto pr-0">
                                        <div class="avatar avatar-40 rounded">
                                            <div class="background">
                                                <?php if($result['Photo'] == ''){?>
                                                <img src="no_profile.jpg" alt="" style="width: 40px;">
                                                <?php } else { ?>
                                                <img src="../uploads/<?php echo $result['Photo'];?>" alt="" style="width: 40px;">
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-normal mb-1"><?php echo $result['Fname'];?></h6>
                                        <p class="small text-secondary"><?php echo $result['Phone'];?></p>
                                    </div>
                                   
                                </div></a>
                            </li>
        
   <?php }
}

if($_POST['action']=='chechBal'){
    $sql = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as credit,(case when Status='Dr' then sum(Amount) else 0 end) as debit FROM wallet WHERE UserId='$user_id' group by Status) as a";
    $row = getRecord($sql);
    $mybalance = $row['credit'] - $row['debit'];
    echo $mybalance;
}
?>