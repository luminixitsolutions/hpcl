<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once('excel_vendor/php-excel-reader/excel_reader2.php');
require_once('excel_vendor/SpreadsheetReader.php');
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customer-Products-2025";
$Page = "Download-Customer-Products-Excel-2025";

?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title>Product Stock Report</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">



<?php 
if(isset($_POST['submit5'])){
    $GodownId = $_POST['GodownId'];
    $StockDate = $_POST['StockDate'];
    $CreatedDate = date('Y-m-d');
    $Narration = addslashes(trim($_POST['Narration']));
    
    $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  
  if(in_array($_FILES["file"]["type"],$allowedFileType)){

         $targetPath = 'excelfiles/godown/'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        
        $Reader = new SpreadsheetReader($targetPath);
        $sheetCount = count($Reader->sheets());
        
        for($i=0;$i<$sheetCount;$i++)
        {
            
            $Reader->ChangeSheet($i);
            
            foreach ($Reader as $Row)
            {
          
                
                $ProdId = "";
                if(isset($Row[1])) {
                    $ProdId = mysqli_real_escape_string($conn,$Row[1]);
                }

                 $PurchasePrice = "";
                if(isset($Row[4])) {
                    $PurchasePrice = mysqli_real_escape_string($conn,$Row[4]);
                }

              
                $Qty = "";
                if(isset($Row[8])) {
                    $Qty = mysqli_real_escape_string($conn,$Row[8]);
                }
               
               $TotalPrice = $Qty*$PurchasePrice;
               
                 if (!empty($ProdId) || !empty($Qty)) {

  $sql22 = "INSERT INTO tbl_godown_raw_prod_stock_2025 SET InvId='$InvId',GodownId='$GodownId',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedBy='$user_id',
 StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='0',CreatedDate='$CreatedDate',Price='$PurchasePrice',TotalPrice='$TotalPrice',CgstPer='0',SgstPer='0',IgstPer='0',GstAmt='0',CgstAmt='0',SgstAmt='0',IgstAmt='0'";
                $conn->query($sql22);
                
                  
                
}
             }
        
         }
         
  $sql = "DELETE FROM tbl_godown_raw_prod_stock_2025 WHERE PurchasePrice='Sell Price'";
 $conn->query($sql);

?>
<script>
alert("Excel Data Imported into the Database");
    window.location.href='upload-godown-stock-by-excel.php';
</script>
<?php
  }
  else
  { 
        $type = "error";
        $message = "Invalid File Type. Upload Excel File.";
  }
  
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Download & Upload Godown Product Stock
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Product Id</th>
                <th>Product Name</th>
                <th>Barcode  No</th>
                
                <th>Purchase Price</th>
               <!-- <th>Sell Price</th>-->
                <th>Credit</th>
                <th>Debit</th>
                <th>Balance</th>
                <!--<th>Min Qty</th>-->
                <th>Add Stock Qty</th>
             
            
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT p.BarcodeNo,p.CreatedBy AS FrId, p.id AS ProdId, p.ProductName, tcc.Name AS CatName, COALESCE(p.MinQty, 0) AS MinQty ,p.PurchasePrice,p.MinPrice 
                                FROM tbl_cust_products2 p 
                                
                                INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id 
                                WHERE p.ProdType = 0 AND p.ProdType2 IN (3) 
                                GROUP BY p.id ORDER BY p.ProductName ASC";
            
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT sum(creditqty) AS creditqty,sum(debitqty) AS debitqty,sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_godown_raw_prod_stock_2025` WHERE ProdId='".$row['ProdId']."' ";
                $sql2.= " GROUP by Status) as a";
                
                $row2 = getRecord($sql2);
                
                $MinQty = $row['MinQty'];
                $BalQty = $row2['balqty'];
                //if($BalQty <  $MinQty){
             ?>
            <tr>
               <td><?php echo $i; ?></td>
               <td><?php echo $row['ProdId']; ?></td>
               <td><?php echo $row['ProductName']; ?></td>
                <td><?php echo $row['BarcodeNo']; ?></td>
                
                 <td><?php echo $row['PurchasePrice']; ?></td>
                <!--  <td><?php echo $row['MinPrice']; ?></td>-->
                 <td><?php echo $row2['creditqty']; ?></td>
                <td><?php echo $row2['debitqty']; ?></td>
                <td><?php echo $row2['balqty']; ?></td>
               <!-- <td><?php echo $row['MinQty']; ?></td>-->
                <td></td>
            </tr>
           <?php $i++;} ?>

          
        </tbody>
    </table>
</div>

<form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
<div class="form-row">

       

<input type="hidden" name="FrId" id="FrId" class="form-control" value="<?php echo $BillSoftFrId;?>" autocomplete="off">

<div class="form-group col-md-4">
<label class="form-label"> Godown <span class="text-danger">*</span></label>
 <select class="form-control" name="GodownId" id="GodownId" required>

<option selected="" value="">Select Godown</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE Roll=93";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Stock Date <span class="text-danger">*</span></label>
<input type="date" name="StockDate" id="StockDate" class="form-control" value="" autocomplete="off" required>
</div>
<div class="form-group col-md-6">
   <label class="form-label">Upload Excel File <span class="text-danger">*</span></label>
     <input type="file" name="file" id="" class="form-control"
                                                placeholder=""
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div>
 <div class="form-group col-md-12">
   <label class="form-label">Narration <span class="text-danger">*</span></label>
     <input type="text" name="Narration" id="Narration" class="form-control" required>
    <div class="clearfix"></div>
 </div>

<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit5" class="btn btn-primary btn-finish" id="submit">Submit</button>
</div>

</div>

</form>

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
 
    	$(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>
</html>
