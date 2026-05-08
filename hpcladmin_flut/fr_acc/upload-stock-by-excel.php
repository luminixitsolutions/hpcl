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
    $BillSoftFrId = $_POST['FrId'];
    $StockDate = $_POST['StockDate'];
    $CreatedDate = date('Y-m-d');
    $Narration = addslashes(trim($_POST['Narration']));
    
    $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
  
  if(in_array($_FILES["file"]["type"],$allowedFileType)){

         $targetPath = 'excelfiles/selling/'.$_FILES['file']['name'];
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
                if(isset($Row[5])) {
                    $PurchasePrice = mysqli_real_escape_string($conn,$Row[5]);
                }

                $SellPrice = "";
                if(isset($Row[6])) {
                    $SellPrice = mysqli_real_escape_string($conn,$Row[6]);
                }

                $Qty = "";
                if(isset($Row[10])) {
                    $Qty = mysqli_real_escape_string($conn,$Row[10]);
                }
               
               
                 if (!empty($ProductId) || !empty($Qty)) {

                   
                     $qx = "INSERT INTO tbl_cust_prod_stock_2025 SET ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice'";
       $conn->query($qx);
       $InvId = mysqli_insert_id($conn);
       
     $qx = "INSERT INTO tbl_cust_prod_stock_2025_backup SET ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$StockDate',Narration='$Narration',Status='Cr',UserId='$BillSoftFrId',CreatedDate='$CreatedDate',FrId='$BillSoftFrId',PurchasePrice='$PurchasePrice',SellPrice='$SellPrice',orgstockid='$InvId'";
  $conn->query($qx);
  
  // Fetch the inserted records
$result = $conn->query("SELECT * FROM tbl_cust_prod_stock_2025 WHERE id = '$InvId'");
$row = $result->fetch_assoc();

// Create SQL Dump
$sqlDump = "INSERT INTO tbl_cust_prod_stock_2025 (ProdId, Qty, CreatedBy, StockDate, Narration, Status, UserId, CreatedDate, FrId, PurchasePrice, SellPrice) 
            VALUES ('" . implode("','", array_values($row)) . "');\n";

file_put_contents('stock_backup/'.$BillSoftFrId.'_backup.sql', $sqlDump, FILE_APPEND | LOCK_EX);

  
  $sql = "UPDATE tbl_cust_products_2025 SET PurchasePrice='$PurchasePrice' WHERE id='$ProdId'";
  $conn->query($sql);
  $url = $_SERVER['REQUEST_URI'];
  $createddate = date('Y-m-d H:i:s');
  $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='new customer product stock added',invid='$InvId',createddate='$createddate',roll='customer-product-stock'";
  $conn->query($sql);
                
}
             }
        
         }
         
//   $sql = "DELETE FROM tbl_cust_prod_stock_2025 WHERE PurchasePrice='Sell Price'";
//  $conn->query($sql);
//  $sql = "DELETE FROM tbl_cust_prod_stock_2025_backup WHERE PurchasePrice='Sell Price'";
//  $conn->query($sql);
?>
<script>
alert("Excel Data Imported into the Database");
    window.location.href='upload-stock-by-excel.php';
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
<h4 class="font-weight-bold py-3 mb-0">Download & Upload Selling Product Stock
</h4>

<div class="card" style="padding: 10px;">
      
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               
                <th>Main Product Id</th>
                <th>Product Id</th>
                <th>Product Name</th>
                <th>Barcode  No</th>
                
                <th>Purchase Price</th>
                <th>Sell Price</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Balance</th>
                <th>Min Qty</th>
                <th>Add Stock Qty</th>
             
            
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT p.ProdId AS MainId,p.BarcodeNo,p.CreatedBy AS FrId, p.id AS ProdId, p.ProductName, tcc.Name AS CatName, COALESCE(p.MinQty, 0) AS MinQty ,p.PurchasePrice,p.MinPrice 
                                FROM tbl_cust_products_2025 p 
                                
                                INNER JOIN tbl_cust_category_2025 tcc ON p.CatId = tcc.id 
                                WHERE p.CreatedBy = '$BillSoftFrId' AND p.ProdType = 0 AND p.ProdType2 IN (1) AND p.delete_flag=0 AND p.checkstatus=1 
                                GROUP BY p.id ORDER BY p.ProductName ASC";
            
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT sum(creditqty) AS creditqty,sum(debitqty) AS debitqty,sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE FrId='$BillSoftFrId' AND ProdId='".$row['ProdId']."' AND ProdType=0 ";
                $sql2.= " GROUP by Status) as a";
                $row2 = getRecord($sql2);
                
                $MinQty = $row['MinQty'];
                $BalQty = $row2['balqty'];
                //if($BalQty <  $MinQty){
             ?>
            <tr>
               <td><?php echo $row['MainId']; ?></td>
               <td><?php echo $row['ProdId']; ?></td>
               <td><?php echo $row['ProductName']; ?></td>
                <td><?php echo $row['BarcodeNo']; ?></td>
                
                 <td><?php echo $row['PurchasePrice']; ?></td>
                  <td><?php echo $row['MinPrice']; ?></td>
                 <td><?php echo $row2['creditqty']; ?></td>
                <td><?php echo $row2['debitqty']; ?></td>
                <td><?php echo $row2['balqty']; ?></td>
                <td><?php echo $row['MinQty']; ?></td>
                <td></td>
            </tr>
           <?php $i++;} ?>

          
        </tbody>
    </table>
</div>

<form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
<div class="form-row">

       

<input type="hidden" name="FrId" id="FrId" class="form-control" value="<?php echo $BillSoftFrId;?>" autocomplete="off">
<div class="form-group col-md-2">
<label class="form-label">Stock Date </label>
<input type="date" name="StockDate" id="StockDate" class="form-control" value="" autocomplete="off" required>
</div>
<div class="form-group col-md-3">
   <label class="form-label">Upload Excel File </label>
     <input type="file" name="file" id="" class="form-control"
                                                placeholder=""
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div>
 <div class="form-group col-md-6">
   <label class="form-label">Narration</label>
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
