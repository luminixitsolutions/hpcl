<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['User']['id'];
$FranchiseId = $_SESSION['FranchiseId'];
if($_POST['action'] == 'Add'){
    $ProductName = addslashes(trim($_POST['ProductName']));
    $CatId = $_POST['CatId'];
    $MinPrice = $_POST['MinPrice'];
    $CgstPer = addslashes(trim($_POST['CgstPer']));
    $SgstPer = addslashes(trim($_POST['SgstPer']));
    $IgstPer = addslashes(trim($_POST['IgstPer']));
    $GstAmt = addslashes(trim($_POST['GstAmt']));
    $ProdPrice = addslashes(trim($_POST['ProdPrice']));

    $CreatedDate = date('Y-m-d');
    $Status = $_POST['Status'];
    $SrNo = $_POST['SrNo'];
    $randno = rand(1,100);
    $src = $_FILES['Photo']['tmp_name'];
    $fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
    $fnm = str_replace(" ","_",$fnm);
    $ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
    $dest = '../../uploads/'. $randno . "_".$fnm . $ext;
    $imagepath =  $randno . "_".$fnm . $ext;
    if(move_uploaded_file($src, $dest))
    {
    $Photo = $imagepath ;
    } 
    else{
      $Photo = $_POST['OldPhoto'];
    }
    
    
    function RandomStringGenerator($n)
    {
        $generated_string = "";   
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $len = strlen($domain);
        for ($i = 0; $i < $n; $i++)
        {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        return $generated_string;
    } 
    $n = 10;
    $Code = RandomStringGenerator($n); 
    
    $sql = "INSERT INTO tbl_cust_products SET ProductName='$ProductName',CatId='$CatId',MinPrice='$MinPrice',Status='$Status',SrNo='$SrNo',Photo='$Photo',CreatedDate='$CreatedDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',ProdPrice='$ProdPrice',CreatedBy='$FranchiseId'";
    $conn->query($sql);
    $ProdId = mysqli_insert_id($conn); 
$Code2 = $Code."".$ProdId; 
$sql2 = "UPDATE tbl_cust_products SET code='$Code2' WHERE id='$ProdId'";
$conn->query($sql2);
if (isset($_FILES['Files'])) {
    $errors = array();
    foreach ($_FILES['Files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $key . $_FILES['Files']['name'][$key];
        $file_size = $_FILES['Files']['size'][$key];
        $file_tmp = $_FILES['Files']['tmp_name'][$key];
        $file_type = $_FILES['Files']['type'][$key];
        $FileName = $_FILES['Files']['name'][$key];
        
        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB';
        }
        if ($file_name == '0' || $file_size == '0') {} else {
             $query = "INSERT into tbl_cust_product_images SET ProductId='$ProdId',Files='$file_name',FileName='$FileName'";
            $desired_dir = "../../uploads/";
            if (empty($errors) == true) {
                if (is_dir($desired_dir) == false) {
                    mkdir("$desired_dir", 0700); // Create directory if it does not exist
                }
                if (is_dir("$desired_dir/" . $file_name) == false) {
                    move_uploaded_file($file_tmp, "../../uploads/" . $file_name);
                } else {
                    // rename the file if another one exist
                    $new_dir = "../../uploads/" . $file_name . time();
                    rename($file_tmp, $new_dir);
                }
                $conn->query($query);
            } else {
                print_r($errors);
            }
        }
        if (empty($error)) {
           
           
        }
    }
}

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Added",
  text: "New Product Added Successfully!",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="../product-lists.php";
  }
}); });</script>

<?php
}


if($_POST['action'] == 'Edit'){
    $id = $_POST['id'];
    $ProductName = addslashes(trim($_POST['ProductName']));
    $CatId = $_POST['CatId'];
    $MinPrice = $_POST['MinPrice'];
    $CgstPer = addslashes(trim($_POST['CgstPer']));
    $SgstPer = addslashes(trim($_POST['SgstPer']));
    $IgstPer = addslashes(trim($_POST['IgstPer']));
    $GstAmt = addslashes(trim($_POST['GstAmt']));
    $ProdPrice = addslashes(trim($_POST['ProdPrice']));
    $CreatedDate = date('Y-m-d');
    $Status = $_POST['Status'];
    $SrNo = $_POST['SrNo'];
    $randno = rand(1,100);
    $src = $_FILES['Photo']['tmp_name'];
    $fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
    $fnm = str_replace(" ","_",$fnm);
    $ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
    $dest = '../../uploads/'. $randno . "_".$fnm . $ext;
    $imagepath =  $randno . "_".$fnm . $ext;
    if(move_uploaded_file($src, $dest))
    {
    $Photo = $imagepath ;
    } 
    else{
      $Photo = $_POST['OldPhoto'];
    }
    
    $sql = "UPDATE tbl_cust_products SET ProductName='$ProductName',CatId='$CatId',MinPrice='$MinPrice',Status='$Status',SrNo='$SrNo',Photo='$Photo',ModifiedDate='$CreatedDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',ProdPrice='$ProdPrice' WHERE id='$id'";
    $conn->query($sql);
    $ProdId = $_POST['id'];
    
if (isset($_FILES['Files'])) {
    $errors = array();
    foreach ($_FILES['Files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $key . $_FILES['Files']['name'][$key];
        $file_size = $_FILES['Files']['size'][$key];
        $file_tmp = $_FILES['Files']['tmp_name'][$key];
        $file_type = $_FILES['Files']['type'][$key];
        $FileName = $_FILES['Files']['name'][$key];
        
        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB';
        }
        if ($file_name == '0' || $file_size == '0') {} else {
             $query = "INSERT into tbl_cust_product_images SET ProductId='$ProdId',Files='$file_name',FileName='$FileName'";
            $desired_dir = "../../uploads/";
            if (empty($errors) == true) {
                if (is_dir($desired_dir) == false) {
                    mkdir("$desired_dir", 0700); // Create directory if it does not exist
                }
                if (is_dir("$desired_dir/" . $file_name) == false) {
                    move_uploaded_file($file_tmp, "../../uploads/" . $file_name);
                } else {
                    // rename the file if another one exist
                    $new_dir = "../../uploads/" . $file_name . time();
                    rename($file_tmp, $new_dir);
                }
                $conn->query($query);
            } else {
                print_r($errors);
            }
        }
        if (empty($error)) {
           
           
        }
    }
}

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Updated",
  text: "Product Updated Successfully!",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="../product-lists.php";
  }
}); });</script>
<?php
}

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_cust_products SET Photo='' WHERE id=$id";
        $conn->query($q);
        // $src = "../../uploads/$Photo";
        // unlink($src);

    echo "Product Photo Delete Successfully";
} 
if($_POST['action'] == 'deletePhoto2'){
    $id = $_POST['id'];
    $pid = $_POST['pid'];
    $Photo = $_POST['Photo'];
        $q = "DELETE FROM tbl_cust_product_images WHERE id=$id AND ProductId='$pid'";
        $conn->query($q);
        // $src = "../../uploads/$Photo";
        // unlink($src);

    echo "Product Photo Delete Successfully";
}

if($_POST['action'] == 'showProdImages'){ 
    $id = $_POST['id'];
  $sql2 = "SELECT * FROM tbl_cust_product_images WHERE ProductId='$id'";
  $res2 = $conn->query($sql2);
  $rncnt = mysqli_num_rows($res2);
  if($rncnt > 0){
    while($row2 = $res2->fetch_assoc()){?>
    <input type="hidden" name="OldMulImage" id="OldMulImage<?php echo $row2["id"]; ?>" value="<?php echo $row2["Files"]; ?>">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" onclick="delete_photo2(<?php echo $row2["id"]; ?>,<?php echo $_POST["id"]; ?>)"></a><img src="../uploads/<?php echo $row2['Files'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
<?php }} } 

if($_POST['action'] == 'fetchFranchiseInv'){
    $SearchData = addslashes(trim($_POST['SearchData']));
    $ownfr = addslashes(trim($_POST['ownfr']));
    $CocoFranchiseAccess = addslashes(trim($_POST['CocoFranchiseAccess']));
    $Roll = addslashes(trim($_POST['Roll']));
    $calendar = addslashes(trim($_POST['calendar']));
    $FromDate = addslashes(trim($_POST['FromDate']));
    $ToDate = addslashes(trim($_POST['ToDate']));
    
    function countval($val,$frid,$Calendar){
        global $conn; 
   
    if($val == 'cash_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType='Cash'";
    }
    if($val == 'upi_payment'){
        $sql = "SELECT SUM(NetAmount) As result FROM tbl_customer_invoice WHERE Roll=2 AND FrId='$frid' AND PayType IN ('Phone Pay','Paytm','UPI','Other UPI')";
    }
    
   if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql.=" AND InvoiceDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
        }
        else{
           $sql.=" AND InvoiceDate='".date('Y-m-d')."'"; 
        }
        //echo $sql;
     $res2 = $conn->query($sql);
    $row2 = $res2->fetch_assoc();
    return $row2['result'];
    }
    
$Calendar = $_REQUEST['calendar'];
 $sql = "SELECT SUM(NetAmount) AS NetAmount,COUNT(*) AS TotInv,tc.FrId,tu.ShopName,tu.Address FROM tbl_customer_invoice tc INNER JOIN tbl_users_bill tu ON tu.id=tc.FrId WHERE tu.Roll='5' AND tu.OwnFranchise='".$ownfr."'";
if($Roll != 1){
    if($CocoFranchiseAccess != '' || $CocoFranchiseAccess != 0){
    $sql.=" AND tu.id IN ($CocoFranchiseAccess)";
    }
}
if($SearchData!=''){
    $sql.="  AND tu.ShopName LIKE '%$SearchData%'";
}

if($Calendar == 'yesterday'){
            $Yesterday = date('Y-m-d',strtotime("-1 days"));
            $sql.=" AND tc.InvoiceDate='$Yesterday'";
        }

        else if($Calendar == 'week'){
            $Week = date('Y-m-d',strtotime("-7 days"));
            $sql.=" AND tc.InvoiceDate>='$Week' AND tc.InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'month'){
            //$Week = date('Y-m-d',strtotime("-30 days"));
            $Week = date('Y-m')."-01";
            $sql.=" AND tc.InvoiceDate>='$Week' AND tc.InvoiceDate<='".date('Y-m-d')."'";
        }
        else if($Calendar == 'Custom'){
        if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND tc.InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND tc.InvoiceDate<='$ToDate'";
            }
        }
        else{
           $sql.=" AND tc.InvoiceDate='".date('Y-m-d')."'"; 
        }
        
$sql.=" GROUP BY tc.FrId  ORDER BY NetAmount DESC";
//echo $sql;
$row = getList($sql);
 foreach($row as $result){
     
    $NetAmount2 = $result['NetAmount'];
   if($NetAmount2 > 0){
       $NetAmount = $result['NetAmount'];
   }
   else{
       $NetAmount = 0;
   }
   
   $rncnt224+=$result['TotInv'];
    $TotNetAmount+=$NetAmount;
   $TotCashAmount+=countval('cash_payment',$result['FrId'],$Calendar);
   $TotUpiAmount+=countval('upi_payment',$result['FrId'],$Calendar);
    if($NetAmount > 0){
        
        $sql = "SELECT Max(modified_time) AS LastTime FROM `tbl_customer_invoice` WHERE FrId='".$result['FrId']."'";
$row = getRecord($sql);
$LastTime = $row['LastTime'];
$date = $LastTime;
$currtime = gmdate("Y-m-d H:i:s");
// Create two DateTime objects
$dateTime1 = new DateTime($currtime); // Current date and time
$dateTime2 = new DateTime($date); // 1 hour 45 minutes ago

// Calculate the difference between the two dates
$interval = $dateTime1->diff($dateTime2);

// Format the difference
$hours = $interval->h;
$minutes = $interval->i;

if ($hours > 0 && $minutes > 0) {
    $timeDifference = "$hours hour" . ($hours > 1 ? 's' : '') . " $minutes minute" . ($minutes > 1 ? 's' : '') . " ago";
} elseif ($hours > 0) {
    $timeDifference = "$hours hour" . ($hours > 1 ? 's' : '') . " ago";
} elseif ($minutes > 0) {
    $timeDifference = "$minutes minute" . ($minutes > 1 ? 's' : '') . " ago";
} else {
    $timeDifference = "just now";
}


$sync_time = $timeDifference;
?>
<div class="card mb-2">
                   <!-- <a href="product-lists.php?catid=<?php echo $result['id'];?>">-->
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                             <a href="home2.php?frid=<?php echo $result['FrId'];?>">
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;"><?php echo $result['ShopName'];?></h6>
                                          <span class="small text-secondary" style=""><?php echo $result['Address'];?></span>
                                       <p class="small " style="text-transform:capitalize;color:#009eff;">Last Sync : <?php echo $sync_time;?></p>
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($NetAmount,2);?></strong>
                                         <br><span style="font-size:12px;">Cash : ₹<?php echo number_format(countval('cash_payment',$result['FrId'],$Calendar),2);?></span>
                                         <br><span style="font-size:12px;">UPI : ₹<?php echo number_format(countval('upi_payment',$result['FrId'],$Calendar),2);?></span>
                                         <br><span style="font-size:12px;">Avg : <?php echo number_format($NetAmount/$result['TotInv'],2);?></span></p>
                                    </div>
                                </div>
                            </li>
                            </a>
                        </ul>
                    </div>
               <!-- </a>-->
                </div>
            <?php } } ?>
            <div class="card mb-2">
                
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total Cash</h6>
                                         
                                      
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotCashAmount,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total UPI</h6>
                                         
                                      
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotUpiAmount,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;"> Total Income</h6>
                                         
                                      
                                    </div>
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong>&#8377;<?php echo number_format($TotNetAmount+$NetAmount3,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                    
                    <div class=" px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item" style="border-radius: 10px;">
                                
                                <div class="row align-items-center">
                                    <div class="col align-self-center pr-0" width="80%">
                                        <h6 class=" mb-1" style="color:black;font-size:14px;font-weight:500;">Total Avg</h6>
                                         
                                      
                                    </div> 
                                    <div class="col-auto" align="right" >
                                         <p class="small text-secondary" style="font-size:15px;"><strong><?php echo number_format(($TotNetAmount+$NetAmount3)/$rncnt224,2);?></strong></p>
                                    </div> 
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                    
                </div>
                <?php 
}
?>