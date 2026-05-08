<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Sell-category-Report";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> </title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>


<?php 
$sql55 = "SELECT * FROM tbl_users WHERE id='$BillSoftFrId'";
            $row55 = getRecord($sql55);
            $title = str_replace(" ","#",$row55['ShopName']);
            $Address = str_replace(" ","#",$row55['Address']);
            $Phone = str_replace(" ","#",$row55['Phone']);
            $GstNo = str_replace(" ","#",$row55['GstNo']);
            $terms_condition = str_replace(" ","#",$row55['terms_condition']);
            $bottom_title = str_replace(" ","#",$row55['bottom_title']);

            $CustName = str_replace(" ","#",$row['CustName']);
            $emparray = array();
            
            $sql = "SELECT * FROM tbl_cust_category WHERE CreatedBy IN (0,$BillSoftFrId)";
            if($_REQUEST['CatId']){
                $CatId = $_REQUEST['CatId'];
                if($CatId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND id='$CatId'";
                }
            }
            $sql.=" ORDER BY srno asc";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
                $sql2 = "SELECT SUM(tcid.Total) AS Total,COUNT(tcid.id) AS TotProd FROM tbl_customer_invoice_details tcid INNER JOIN tbl_customer_invoice tci ON tci.id=tcid.InvId WHERE tcid.CatId='".$row['id']."' AND tci.Roll=2 AND tci.FrId='$BillSoftFrId'";
                
                if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql2.= " AND tci.InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql2.= " AND tci.InvoiceDate<='$ToDate'";
            }
            $row2 = getRecord($sql2);
             if($row2['TotProd'] > 0){
                 $TotalQty+=$row2['TotProd'];
                 $SubTotal+=$row2['Total'];
                $Product_name2 = str_replace(" ","#",$row['Name']);
                $Product_name = substr($Product_name2,0,16);
                $emparray[] = array('item'=>$Product_name,'rate'=>0,'quantity'=>$row2['TotProd'],'amount'=>$row2['Total']);
             }
            }
           // print_r($emparray);
            $dados2 =  json_encode(array('productList' => $emparray));
$date = date("d/m/Y", strtotime(str_replace('-', '/',$_REQUEST['FromDate'])));

$dados1 = json_encode(array('title'=>$title,'address'=>$Address,'mobile'=>$Phone,'gstin'=>$GstNo,'customer_name'=>'','customer_phone_no'=>'','receipt_title'=>'Retail#Receipt',
'invoice_no'=>'','date'=>$date,'sub_total'=>$SubTotal,'discount'=>0,'service_charge'=>0,'gst'=>0,'total_bill'=>$SubTotal,'total_payable'=>$SubTotal,
'terms_condition'=>$terms_condition,'bottom_title'=>$bottom_title,'totalqty'=>$TotalQty)); 

           
     $invoice_data =  json_encode(array_merge(json_decode($dados1, true),json_decode($dados2, true)));
         //print_r($invoice_data);  
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Category Wise Sell Report</h4>
<br>

<div class="card">
<div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">



<div class="form-group col-md-3">
<label class="form-label"> Category<span class="text-danger">*</span></label>
 <select class="form-control" name="CatId" id="CatId" required>
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_cust_category WHERE CreatedBy IN (0,$BillSoftFrId)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CatId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label"> Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<!--<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<button type="button" id="print" class="btn btn-success btn-finish" onClick=printReport('<?php echo $invoice_data;?>')>Print</button>
</div>-->
<?php if(isset($_REQUEST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <th>Category</th> 
               
                <th>Total Sell</th>
                <th>Amount</th>
              
   
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT * FROM tbl_cust_category WHERE CreatedBy IN (0,$BillSoftFrId) ";

            if($_REQUEST['CatId']){
                $CatId = $_REQUEST['CatId'];
                if($CatId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND id='$CatId'";
                }
            }
            
            $sql.=" ORDER BY srno asc";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                
                $sql2 = "SELECT SUM(tcid.Total) AS Total,COUNT(tcid.id) AS TotProd FROM tbl_customer_invoice_details tcid INNER JOIN tbl_customer_invoice tci ON tci.id=tcid.InvId WHERE tcid.CatId='".$row['id']."' AND tci.Roll=2 AND tci.FrId='$BillSoftFrId'";
                
                if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql2.= " AND tci.InvoiceDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql2.= " AND tci.InvoiceDate<='$ToDate'";
            }
            $row2 = getRecord($sql2);
             if($row2['TotProd'] > 0){
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
            
              
               
                  <td><?php echo $row['Name']; ?></td>
              
                    <td><?php echo $row2['TotProd']; ?></td>
                <td>&#8377;<?php echo number_format($row2['Total'],2); ?></td>
          
        
              
            </tr>
           <?php $i++;}} ?>

       
        </tbody>
    </table>
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
<script type="text/javascript">
  function printReport(invdata){
     console.log(invdata);
      Android.printReport(''+invdata+'');
 }
	$(document).ready(function() {
    $('#example').DataTable({
        "pageLength":100,
      "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });

    $(document).on("change", "#CustId", function(event) {
                var val = this.value;
                var action = "getInvoiceNos";
                $.ajax({
                    url: "ajax_files/ajax_dropdown.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: val
                    },
                    success: function(data) {
                        $('#InvNo').html(data);
                       
                    }
                });

            });
});
</script>
</body>
</html>
