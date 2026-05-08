<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "First-Financer";
$Page = "Financer-Pending-Request-Product-Stock";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Raw Stock
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>
    <script src="ckeditor/ckeditor.js"></script>
</head>

<body>
    <style type="text/css">
    .password-tog-info {
        display: inline-block;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        position: absolute;
        right: 50px;
        top: 30px;
        text-transform: uppercase;
        z-index: 2;
    }
    </style>
     <div class="layout-wrapper layout-1 layout-without-sidenav">
        <div class="layout-inner">

            <?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


            <div class="layout-container">

                

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT tp.*,tu.Fname AS GodownName,tu2.Fname FROM tbl_request_product_stock tp 
            LEFT JOIN tbl_users_bill tu ON tu.id=tp.FromFrId 
            LEFT JOIN tbl_users tu2 ON tu2.id=tp.VedId 
            WHERE tp.id='$id'";
$row7 = getRecord($sql7);

$sql2 = "SELECT * FROM tbl_request_product_stock_items WHERE TransferId='$id'";
$rncnt2 = getRow($sql2);

$EmpId = $row7['UserId'];




if(isset($_POST['submit'])){
    $FinancerApproveDate1 = addslashes(trim($_POST["FinancerApproveDate1"]));
     $FinancerComment1 = addslashes(trim($_POST["FinancerComment1"]));
  $AccAmount = addslashes(trim($_POST["AccAmount"]));
  $FinancerStatus1 = addslashes(trim($_POST["FinancerStatus1"]));
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');
 //$TicketNo= "#".rand(1000,9999);
    $query2 = "UPDATE tbl_request_product_stock SET FinancerApproveDate1='$FinancerApproveDate1',FinancerComment1='$FinancerComment1',FinancerStatus1='$FinancerStatus1',FinancerBy1='$user_id' WHERE id = '$id'";
  $conn->query($query2);

  echo "<script>alert('Approved Successfully!');window.location.href='first-financer-pending-request-product-stock.php';</script>";


    //header('Location:courses.php'); 

  }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Approve Product Stock Request</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                     

                                        <div class="form-group col-md-6">
                                            <label class="form-label">Franchise Name</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['GodownName']; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-md-6">
                                            <label class="form-label">Vendor Name</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['Fname']; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-md-3">
                                            <label class="form-label">Total Product</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $rncnt2; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-md-3">
                                            <label class="form-label">Total Qty</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["TotQty"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">GST Amount</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["GstAmount"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Total Amount</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["TotalAmount"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                       
                                        

 <div class="form-group col-md-6">
                                            <label class="form-label">Approve Date</label>
                                            <input type="date" name="FinancerApproveDate1" class="form-control"
                                                placeholder="" value="<?php echo $row7["FinancerApproveDate1"]; ?>" required>
                                            <div class="clearfix"></div>
                                        </div>

 <div class="form-group col-md-6">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="FinancerStatus1" name="FinancerStatus1" required="">
                                                <option selected="" disabled="" value="">Select Status</option>
                                                <option value="1" <?php if($row7["FinancerStatus1"]=='1') {?> selected
                                                    <?php } ?>>Approved</option>
                                                <option value="0" <?php if($row7["FinancerStatus1"]=='0') {?> selected
                                                    <?php } ?>>Pending</option>
                                                    <option value="2" <?php if($row7["FinancerStatus1"]=='2') {?> selected
                                                    <?php } ?>>Reject</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
 <div class="form-group col-md-12">
                                            <label class="form-label">Comment</label>
                                            <textarea name="FinancerComment1" class="form-control"
                                                placeholder=""></textarea>
                                            <div class="clearfix"></div>
                                        </div>



                                        
</div>

  


                                   <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Approve</button>
                                    </div>

                
                                    </div>
                               </div>


 <div class="col-lg-5" id="emidetails" style="display:none;">
    

 </div>

  
                                

 </div>
 </form>





                            </div>
                        </div>



</div>


                   


                    <?php include_once 'footer.php'; ?>
                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>

 
</body>

</html>