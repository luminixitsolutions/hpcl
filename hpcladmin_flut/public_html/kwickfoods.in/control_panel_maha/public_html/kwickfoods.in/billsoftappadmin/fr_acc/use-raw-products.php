<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Use-Raw-Products";
$Page = "Add-Use-Raw-Products";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Customers</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>
<script src="../ckeditor/ckeditor.js"></script>
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
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>
<?php 
if(isset($_POST['submit'])){
    $StockDate = addslashes(trim($_POST['StockDate']));
    $TotQty = addslashes(trim($_POST['TotQty']));
    $Narration = addslashes(trim($_POST['Narration']));
    $CreatedDate = date('Y-m-d');
    $sql = "INSERT INTO tbl_use_raw_stock SET StockDate='$StockDate',TotQty='$TotQty',Narration='$Narration',CreatedDate='$CreatedDate'";
    $conn->query($sql);
    $UseRawId = mysqli_insert_id($conn);

    $number = count($_POST["ProdId"]);
                    if ($number > 0) {
                        for ($i = 0; $i < $number; $i++) {
                            if (trim($_POST["ProdId"][$i] != '')) {
                                $ProdId = addslashes(trim($_POST['ProdId'][$i]));
                                $Qty = addslashes(trim($_POST['Qty'][$i]));
                                $sql22 = "INSERT INTO tbl_raw_stock SET ProdId='$ProdId',Qty='$Qty',CreatedDate='$CreatedDate',UseRawId='$UseRawId',CrDr='dr',Narration='$Narration'";
                                $conn->query($sql22);
                            }
                        }
                    }

                    echo "<script>alert('Record Saved Successfully!');window.location.href='view-uses-raw-products.php';</script>";
}


?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Use Raw Products</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">


 <div id="dynamic_field">
    <div class="form-row">
<div class="form-group col-md-5 ">
<label class="form-label">Product Name</label>
 <select class="form-control" name="ProdId[]" id="ProdId1"  onchange="getAvailStock(this.value,document.getElementById('srno1').value)">
<option selected="" value="">Select Product</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_raw_products WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']; ?></option>
<?php } ?>
</select>
</div>



<div class="form-group col-md-2">
<label class="form-label">Available Stock </label>
<input type="number" name="AvailStock[]" id="AvailStock1" class="form-control" placeholder="e.g.,1" value="" autocomplete="off" min="1">
</div>

<div class="form-group col-md-2">
<label class="form-label">Qty </label>
<input type="number" name="Qty[]" id="Qty1" class="form-control txt" placeholder="e.g.,1" value="" autocomplete="off" min="1" oninput="getSubTotal()">
</div>

<input type="hidden" class="form-control" name="srno[]" id="srno1" value="1">

<div class="form-group col-md-1" style="padding-top: 20px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" id="add_more">+</button>
</div>
</div>
</div>

<div class="form-row">



<div class="form-group col-md-6">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="StockDate" id="StockDate" class="form-control txt" placeholder="" value="<?php echo $row7["StockDate"]; ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

<div class="form-group col-md-6">
<label class="form-label">Total Qty </label>
<input type="text" name="TotQty" id="TotQty" class="form-control" placeholder="" value="<?php echo $row7["TotQty"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>

    
<div class="form-group col-md-12">
   <label class="form-label">Narration <span class="text-danger">*</span></label>
     <textarea name="Narration" id="Narration" class="form-control" required  
                                                >Stock Used</textarea>
    <div class="clearfix"></div>
 </div>
</div>

<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
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
 
 <script>
    function getSubTotal(){
     var sum = 0;
      $(".txt").each(function() {
      if(!isNaN(this.value) && this.value.length!=0) {
        sum += parseFloat(this.value);
      }
   });
     $('#TotQty').val(sum);
   
    }

    function getAvailStock(id,srno){
      var action = "getAvailStock";
            $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                    $('#AvailStock' + srno).val(data);
                    
                }
            });
    }

    $(document).ready(function(){
  var i=1; 
    $('#add_more').click(function(){  
           i++;  
       var action = "getMore";
    $.ajax({
    url:"ajax_files/ajax_raw_stock.php",
    method:"POST",
    data : {action:action,id:i},
    success:function(data)
    {
      $('#dynamic_field').append(data);
    }   
    });  
    }); 

    $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");  
           if(confirm("Are you sure you want to delete?"))  
           { 
           $('#row'+button_id+'').remove();  
            
           }
      }); 

  }); 
</script>
</body>
</html>