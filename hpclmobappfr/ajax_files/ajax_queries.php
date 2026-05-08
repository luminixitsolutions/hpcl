<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['User']['id'];
  
if($_POST['action']=='SaveQuery'){
    $id = $_POST['id'];
    $Questions = addslashes(trim($_POST['Questions']));
    $CreatedDate = date('Y-m-d');
    $sql = "INSERT INTO tbl_query_questions SET StudId='$user_id',Questions='$Questions',Status=1,CreatedDate='$CreatedDate'";
    $conn->query($sql);
    echo 1;
}   

if($_POST['action']=='SaveComments'){
    $QueryId = $_POST['QueryId'];
    $Comments = addslashes(trim($_POST['Comments']));
    $CustName = addslashes(trim($_POST["CustName"]));
$Phone = addslashes(trim($_POST["Phone"]));
$Address = addslashes(trim($_POST['Address']));
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');
    $sql = "INSERT INTO tbl_customer_query_feedback SET SellId='$QueryId',CustId='$user_id',Details='$Comments',CreatedDate='$CreatedDate',CreatedBy='$user_id',Status=1,CreatedTime='$CreatedTime',Diaposition=1,CustName='$CustName',Phone = '$Phone',Address='$Address'";
    $conn->query($sql);
    echo 1;
}  
if($_POST['action']=='searchDoughts'){
    $SearchText = addslashes(trim($_POST['SearchText']));
                    $i=1;
                   
                  
                    $sql2 = "SELECT tq.*,tu.Fname,tu.Lname,tu.Photo FROM tbl_customer_queries tq LEFT JOIN tbl_users tu ON tq.CustId=tu.id WHERE tq.Details LIKE '%$SearchText%' AND tq.CustId='$user_id'"; 
                    //echo $sql2;
                    $rncnt2 = getRow($sql2);
                    if($rncnt2 > 0){
                    $res2 = getList($sql2);
                    foreach($res2 as $row){ 
                         if($i%2==0){
                            $bgcolor = "background-color: #e6f2ff;";
                        }
                        else{
                           $bgcolor = "background-color: #b3d9ff;"; 
                        }
                    $url =  $SiteUrl.'uploads/'.$row["Files"];
                    
                    /*$sql = "SELECT * FROM tbl_query_comments WHERE QueryId='".$row["id"]."'";
                    $rncnt = getRow($sql);
                    
                    $sql3 = "SELECT * FROM tbl_query_views WHERE QueryId='".$row["id"]."'";
                    $rncnt3 = getRow($sql3);*/
                    
                    ?>
                    
                    <input type="hidden" id="user_id" value="<?php echo $UserId; ?>">
 
   <input type="hidden" id="pid<?php echo $row["id"];?>" value="<?php echo $row["id"];?>">
 <input type="hidden" id="code<?php echo $row["id"];?>" value="<?php echo $row['code'];?>">
     <input type="hidden" id="prd_price<?php echo $row["id"];?>" value="<?php echo $row['Amount'];?>"> 
      <input type="hidden" id="qntno<?php echo $row["id"];?>" value="1">         
      
      
            <div class="col-12 col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <a href="query-details.php?id=<?php echo $row['id'];?>">
                                <div class="row align-items-center">
                                    <div class="col-auto pr-0">
                                        <div class="avatar avatar-40 rounded">
                                            <div class="background" style="background-image: url('<?php echo $UploadUrl;?>/uploads/<?php echo $row['Photo'];?>');">
                                                <img src="<?php echo $UploadUrl;?>/uploads/<?php echo $row['Photo'];?>" alt="" style="display: none;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pr-0">
                                        <h6 class=" mb-1" style="font-weight:500;font-size:16px;"><?php echo $row["Details"];?></h6>
                                        <span style="font-size:12px;color:#0066ff;"><?php echo $row['Fname']." ".$row['Lname'];?></span>
                                        <p class="small text-secondary" style="font-size:14px;">
                                            <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?>
                                        </p>
                                    </div>
                                  
                                </div></a>
                                
                              
                            </div>
                        </div>
                    </div>
                    
                  
                     <?php $i++;} } else{?>
                        <h3 style="color:grey;padding-left: 10px;font-size: 15px;">No Queries Found</h3>
                    <?php } 
}


if($_POST['action']=='completeTask'){
    $TaskId = $_POST['id'];
    $Details = addslashes(trim($_POST['Details']));
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');

    $sql3 = "SELECT * FROM tbl_cashback_amount WHERE id=6";
$row3 = getRecord($sql3);
$CashbackAmt = $row3['Price'];

    $sql = "INSERT INTO tbl_complete_task SET TaskId='$TaskId',UserId='$user_id',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',Details='$Details'";
    $conn->query($sql);
    
    $sql = "UPDATE tbl_tasks SET Status=1 WHERE id='$TaskId'";
    $conn->query($sql);

    $sql_12 = "INSERT INTO wallet SET UserId='$user_id',Amount='$CashbackAmt',Status='Cr',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',Narration='Complete Task Cashback'";
$conn->query($sql_12);
    echo 1;
} 


if($_POST['action']=='searchTask'){
    $TaskDate = $_POST['SearchText'];
    $Status = $_POST['status'];
    $i=1;
                   
                    if($Status==1){
                    $sql2 = "SELECT tq.* FROM tbl_tasks tq WHERE tq.ExeId='$user_id'";
                    if($TaskDate!=''){
                        $sql2.=" AND tq.TaskDate='$TaskDate'"; 
                    }
                    $sql2.=" ORDER BY tq.id DESC";
                    //echo $sql2;
                    $rncnt2 = getRow($sql2);
                    if($rncnt2 > 0){
                    $res2 = getList($sql2);
                    foreach($res2 as $row){ 
                         if($i%2==0){
                            $bgcolor = "background-color: #e6f2ff;";
                        }
                        else{
                           $bgcolor = "background-color: #b3d9ff;"; 
                        }
                    $url =  $SiteUrl.'uploads/'.$row["Files"];
                    
                    $sql = "SELECT * FROM tbl_complete_task WHERE TaskId='".$row["id"]."' AND UserId='$user_id'";
                    $rncnt = getRow($sql);
                    
                    $sql3 = "SELECT * FROM tbl_complete_task WHERE TaskId='".$row["id"]."'";
                    $rncnt3 = getRow($sql3);
                    $row3 = getRecord($sql3);
                    if($Status==2){
                        $sql2.=" "; 
                    }
                    ?>
                    
                    <input type="hidden" id="user_id" value="<?php echo $UserId; ?>">
 
   <input type="hidden" id="pid<?php echo $row["id"];?>" value="<?php echo $row["id"];?>">
 <input type="hidden" id="code<?php echo $row["id"];?>" value="<?php echo $row['code'];?>">
     <input type="hidden" id="prd_price<?php echo $row["id"];?>" value="<?php echo $row['Amount'];?>"> 
      <input type="hidden" id="qntno<?php echo $row["id"];?>" value="1">         
      
      
            <div class="col-12 col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                
                                <div class="row align-items-center">
                                   <!--<a href="task-details.php?id=<?php echo $row['id'];?>">-->
                                    <div class="col align-self-center pr-0">
                                         <span style="font-size:12px;color:#0066ff;"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['TaskDate']))); ?></span>
                                        <h6 class=" mb-1" style="font-weight:500;font-size:16px;"><?php echo $row["TaskName"];?></h6>
                                       
                                        <!-- <p class="small text-secondary" style="font-size:14px;"><i style="font-size:16px; color:rgba(232,37,37,1);" class="fa fa-user"></i>
                                        Completed this task</p> -->
                                        <textarea class="form-control" name="Details" id="Details<?php echo $row['id'];?>" style="width: 98%;"><?php echo $row3['Details'];?></textarea>
                                    </div>
                                   <!-- </a>-->
                                    
                                  
                                </div>
<br>
                                <div class="col-auto pl-0">
                                        <?php if($rncnt > 0){?>
                                        <button class="btn btn-sm btn-default px-4 rounded" disabled style="background-color: #009300;">
                                            Completed
                                        </button>
                                        
                                        <?php } else { ?>
                                        <button class="btn btn-sm btn-default px-4 rounded" onclick="completeTask(<?php echo $row['id'];?>,document.getElementById('Details<?php echo $row['id'];?>').value)" id="CompleteBtn<?php echo $row['id'];?>">
                                            Complete Task
                                        </button>
                                        <?php } ?>
                                    </div>
                                
                              
                            </div>
                        </div>
                    </div>
                    
                  
                     <?php $i++;} } } 
                     
                     else if($Status==2){
                         
                          $sql2 = "SELECT tq.* FROM tbl_tasks tq WHERE  tq.ExeId='$user_id'";
                    if($TaskDate!=''){
                        $sql2.=" AND tq.TaskDate='$TaskDate'"; 
                    }
                    $sql2.=" ORDER BY tq.id DESC";
                    //echo $sql2;
                    $rncnt2 = getRow($sql2);
                    if($rncnt2 > 0){
                    $res2 = getList($sql2);
                    foreach($res2 as $row){ 
                         if($i%2==0){
                            $bgcolor = "background-color: #e6f2ff;";
                        }
                        else{
                           $bgcolor = "background-color: #b3d9ff;"; 
                        }
                    $url =  $SiteUrl.'uploads/'.$row["Files"];
                    
                    $sql = "SELECT * FROM tbl_complete_task WHERE TaskId='".$row["id"]."' AND UserId='$user_id'";
                    $rncnt = getRow($sql);
                    
                   $sql3 = "SELECT * FROM tbl_complete_task WHERE TaskId='".$row["id"]."'";
                    $rncnt3 = getRow($sql3);
                    $row3 = getRecord($sql3);
                    
                    if($rncnt > 0){
                    ?>
                    
                    <input type="hidden" id="user_id" value="<?php echo $UserId; ?>">
 
   <input type="hidden" id="pid<?php echo $row["id"];?>" value="<?php echo $row["id"];?>">
 <input type="hidden" id="code<?php echo $row["id"];?>" value="<?php echo $row['code'];?>">
     <input type="hidden" id="prd_price<?php echo $row["id"];?>" value="<?php echo $row['Amount'];?>"> 
      <input type="hidden" id="qntno<?php echo $row["id"];?>" value="1">         
      
      
            <div class="col-12 col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                
                                <div class="row align-items-center">
                                   <!--<a href="task-details.php?id=<?php echo $row['id'];?>">-->
                                    <div class="col align-self-center pr-0">
                                        <h6 class=" mb-1" style="font-weight:500;font-size:16px;"><?php echo $row["TaskName"];?></h6>
                                        <span style="font-size:12px;color:#0066ff;"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['TaskDate']))); ?></span>
                                        <!-- <p class="small text-secondary" style="font-size:14px;"><i style="font-size:16px; color:rgba(232,37,37,1);" class="fa fa-user"></i>
                                        Completed this task</p> -->
                                        <textarea class="form-control" name="Details" id="Details<?php echo $row['id'];?>" style="width: 98%;"><?php echo $row3['Details'];?></textarea>
                                    </div>
                                   <!-- </a>-->
                                    <div class="col-auto pl-0">
                                        <?php if($rncnt > 0){?>
                                        <button class="btn btn-sm btn-default px-4 rounded" disabled style="background-color: #009300;">
                                            Completed
                                        </button>
                                        
                                        <?php } else { ?>
                                        <button class="btn btn-sm btn-default px-4 rounded" onclick="completeTask(<?php echo $row['id'];?>,document.getElementById('Details<?php echo $row['id'];?>').value)" id="CompleteBtn<?php echo $row['id'];?>">
                                            Complete Task
                                        </button>
                                        <?php } ?>
                                    </div>
                                  
                                </div>
                                
                              
                            </div>
                        </div>
                    </div>
                    
                  
                     <?php $i++;} } } }
                     
                     else if($Status==3){
                         $CurrDate = date('Y-m-d');
                         $sql2 = "SELECT tq.* FROM tbl_tasks tq WHERE tq.TaskDate<'$CurrDate' AND tq.ExeId='$user_id'";
                    if($TaskDate!=''){
                        $sql2.=" AND tq.TaskDate='$TaskDate'"; 
                    }
                    $sql2.=" ORDER BY tq.id DESC";
                    $rncnt2 = getRow($sql2);
                    if($rncnt2 > 0){
                    $res2 = getList($sql2);
                    foreach($res2 as $row){ 
                         if($i%2==0){
                            $bgcolor = "background-color: #e6f2ff;";
                        }
                        else{
                           $bgcolor = "background-color: #b3d9ff;"; 
                        }
                    $url =  $SiteUrl.'uploads/'.$row["Files"];
                    
                    $sql = "SELECT * FROM tbl_complete_task WHERE TaskId='".$row["id"]."' AND UserId='$user_id'";
                    $rncnt = getRow($sql);
                    
                    $sql3 = "SELECT * FROM tbl_complete_task WHERE TaskId='".$row["id"]."'";
                    $rncnt3 = getRow($sql3);
                    $row3 = getRecord($sql3);
                    if($Status==2){
                        $sql2.=" "; 
                    }
                    ?>
                    
                    <input type="hidden" id="user_id" value="<?php echo $UserId; ?>">
 
   <input type="hidden" id="pid<?php echo $row["id"];?>" value="<?php echo $row["id"];?>">
 <input type="hidden" id="code<?php echo $row["id"];?>" value="<?php echo $row['code'];?>">
     <input type="hidden" id="prd_price<?php echo $row["id"];?>" value="<?php echo $row['Amount'];?>"> 
      <input type="hidden" id="qntno<?php echo $row["id"];?>" value="1">         
      
      
            <div class="col-12 col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                
                                <div class="row align-items-center">
                                   <!--<a href="task-details.php?id=<?php echo $row['id'];?>">-->
                                    <div class="col align-self-center pr-0">
                                        <h6 class=" mb-1" style="font-weight:500;font-size:16px;"><?php echo $row["TaskName"];?></h6>
                                        <span style="font-size:12px;color:#0066ff;"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['TaskDate']))); ?></span>
                                       <!--  <p class="small text-secondary" style="font-size:14px;"><i style="font-size:16px; color:rgba(232,37,37,1);" class="fa fa-user"></i>
                                        Completed this task</p> -->
                                        <textarea class="form-control" name="Details" id="Details<?php echo $row['id'];?>" style="width: 98%;"><?php echo $row3['Details'];?></textarea>
                                    </div>
                                   <!-- </a>-->
                                    <div class="col-auto pl-0">
                                        <?php if($rncnt > 0){?>
                                        <button class="btn btn-sm btn-default px-4 rounded" disabled style="background-color: #009300;">
                                            Completed
                                        </button>
                                        
                                        <?php } else { ?>
                                        <button class="btn btn-sm btn-default px-4 rounded" onclick="completeTask(<?php echo $row['id'];?>,document.getElementById('Details<?php echo $row['id'];?>').value)" id="CompleteBtn<?php echo $row['id'];?>">
                                            Complete Task
                                        </button>
                                        <?php } ?>
                                    </div>
                                  
                                </div>
                                
                              
                            </div>
                        </div>
                    </div>
                    
                  
                     <?php $i++;} }
                     
                     }
                     
                     else if($Status==4){
                         $CurrDate = date('Y-m-d');
                         $sql2 = "SELECT tq.* FROM tbl_tasks tq WHERE tq.TaskDate>='$CurrDate' AND tq.ExeId='$user_id'";
                    if($TaskDate!=''){
                        $sql2.=" AND tq.TaskDate='$TaskDate'"; 
                    }
                    $sql2.=" ORDER BY tq.id DESC";
                    $rncnt2 = getRow($sql2);
                    if($rncnt2 > 0){
                    $res2 = getList($sql2);
                    foreach($res2 as $row){ 
                         if($i%2==0){
                            $bgcolor = "background-color: #e6f2ff;";
                        }
                        else{
                           $bgcolor = "background-color: #b3d9ff;"; 
                        }
                    $url =  $SiteUrl.'uploads/'.$row["Files"];
                    
                    $sql = "SELECT * FROM tbl_complete_task WHERE TaskId='".$row["id"]."' AND UserId='$user_id'";
                    $rncnt = getRow($sql);
                    
                    $sql3 = "SELECT * FROM tbl_complete_task WHERE TaskId='".$row["id"]."'";
                    $rncnt3 = getRow($sql3);
                    $row3 = getRecord($sql3);
                    if($Status==2){
                        $sql2.=" "; 
                    }
                    ?>
                    
                    <input type="hidden" id="user_id" value="<?php echo $UserId; ?>">
 
   <input type="hidden" id="pid<?php echo $row["id"];?>" value="<?php echo $row["id"];?>">
 <input type="hidden" id="code<?php echo $row["id"];?>" value="<?php echo $row['code'];?>">
     <input type="hidden" id="prd_price<?php echo $row["id"];?>" value="<?php echo $row['Amount'];?>"> 
      <input type="hidden" id="qntno<?php echo $row["id"];?>" value="1">         
      
      
            <div class="col-12 col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                
                                <div class="row align-items-center">
                                   <!--<a href="task-details.php?id=<?php echo $row['id'];?>">-->
                                    <div class="col align-self-center pr-0">
                                        <h6 class=" mb-1" style="font-weight:500;font-size:16px;"><?php echo $row["TaskName"];?></h6>
                                        <span style="font-size:12px;color:#0066ff;"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['TaskDate']))); ?></span>
                                        <!-- <p class="small text-secondary" style="font-size:14px;"><i style="font-size:16px; color:rgba(232,37,37,1);" class="fa fa-user"></i>
                                        Completed this task</p> -->
                                        <textarea class="form-control" name="Details" id="Details<?php echo $row['id'];?>" style="width: 98%;"><?php echo $row3['Details'];?></textarea>
                                    </div>
                                   <!-- </a>-->
                                    <div class="col-auto pl-0">
                                        <?php if($rncnt > 0){?>
                                        <button class="btn btn-sm btn-default px-4 rounded" disabled style="background-color: #009300;">
                                            Completed
                                        </button>
                                        
                                        <?php } else { ?>
                                        <button class="btn btn-sm btn-default px-4 rounded" onclick="completeTask(<?php echo $row['id'];?>,document.getElementById('Details<?php echo $row['id'];?>').value)" id="CompleteBtn<?php echo $row['id'];?>">
                                            Complete Task
                                        </button>
                                        <?php } ?>
                                    </div>
                                  
                                </div>
                                
                              
                            </div>
                        </div>
                    </div>
                    
                  
                     <?php $i++;} }
                     
                     }
                     
                     else{?>
                        <h3 style="color:grey;padding-left: 10px;font-size: 15px;">No Task Found</h3>
                    <?php } 
    
}


if($_POST['action']=='SaveTask'){
$TaskDate = $_POST['TaskDate'];
$TaskName = addslashes(trim($_POST['TaskName']));
$Status = 0;
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');

if($_GET['id']==''){
     $qx = "INSERT INTO tbl_tasks SET ExeId='$user_id',TaskDate='$TaskDate',TaskName = '$TaskName',Status='$Status',CreatedDate='$CreatedDate',CreatedBy='$user_id'";
  $conn->query($qx);
  echo 1;
}
else{
 //$TicketNo= "#".rand(1000,9999);
    $query2 = "UPDATE tbl_tasks SET ExeId='$user_id',TaskDate='$TaskDate',TaskName = '$TaskName',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id' WHERE id = '$id'";
  $conn->query($query2);
  echo 1;

}

    }
?>