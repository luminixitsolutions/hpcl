<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "Check-And-Receive-Stock";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?></title>
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




<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Receive Product Stock 
 
</h4>

<?php

if(isset($_POST['submit'])){
   $CreatedDate = date('Y-m-d');
      $number = count($_POST['CheckId']);
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                 $ProdId = addslashes(trim($_POST['ProdId'][$i]));
                 $ProdType = addslashes(trim($_POST['ProdType'][$i]));
                 $Qty = addslashes(trim($_POST['Qty'][$i]));
                 $Unit = addslashes(trim($_POST['Unit'][$i]));
                 $id = addslashes(trim($_POST['id'][$i]));
                 if($ProdType == 0){
                     $qx = "INSERT INTO tbl_cust_prod_stock SET ProdId='$ProdId',Qty='$Qty',CreatedBy='$user_id',StockDate='$CreatedDate',Narration='$Narration',Status='Cr',UserId='0',CreatedDate='$CreatedDate'";
                    $conn->query($qx);
                    
                 }
                 else{
                      $qx = "INSERT INTO tbl_fr_raw_stock SET FrId='0',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedBy='$user_id',StockDate='$CreatedDate',Narration='Stock Added',Status='Cr',CreatedDate='$CreatedDate'";
                    $conn->query($qx);
                 }
                
                $sql = "UPDATE tbl_transfer_godown_raw_stock_items SET Receive=1 WHERE id='$id'";
                $conn->query($sql);
                
                }
              }
            } 
        }


       
         echo "<script>alert('Stock Added Successfully!');window.location.href='view-check-transfer-stock.php';</script>";
}
?>

<div class="card" style="padding: 10px;">
  <form id="validation-form" method="post" enctype="multipart/form-data" action="">   
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <th>Product Name</th> 
               <th>Qty</th> 
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $id = $_GET['id'];
            $sql = "SELECT ttg.*,tcp.ProductName,tcp.ProdType FROM tbl_transfer_godown_raw_stock_items ttg 
                    LEFT JOIN tbl_cust_products tcp ON tcp.id=ttg.FrProdId WHERE TransferId='$id'";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                if($row['Receive']==0){
                    $bgcolor="background-color: #ffff;";
                }
                else{
                    $bgcolor="background-color: #e6fae6;";
                }
                
               
             ?>
            <tr style="<?php echo $bgcolor;?>"> 
                
               <td><?php if($row['Receive']==0){?>
                   <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $row['id']; ?>)">
                    <span class="custom-control-label">&nbsp;</span>
                 </label> <?php } ?> </td>
                  <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                   <input type="hidden" value="<?php echo $row['FrProdId']; ?>" name="ProdId[]">
                   <input type="hidden" value="<?php echo $row['ProdType']; ?>" name="ProdType[]">
                   <input type="hidden" value="<?php echo $row['Qty']; ?>" name="Qty[]">
                   <input type="hidden" value="<?php echo $row['Unit']; ?>" name="Unit[]">
                   <input type="hidden" value="<?php echo $row['id']; ?>" name="id[]">
               <td><?php echo $row['ProductName']; ?></td>
               <td><?php echo $row['Qty']." ".$row['Unit']; ?></td>
             
             
           
        
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
</div>
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
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
    });
});

  function featured(id,code,frombranch){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
            
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }
</script>
</body>
</html>
