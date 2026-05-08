<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "Transfer-Raw-Stock-Godown-To-Franchise";

?>
<!DOCTYPE html>
<html lang="en" class="default-style">
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

/* Enhanced Select2 Search Styling */
.select2-results__option mark {
  background-color: #ffeb3b;
  color: #333;
  font-weight: bold;
  padding: 1px 2px;
  border-radius: 2px;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 8px 12px;
  font-size: 14px;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
  background-color: #007bff;
  color: white !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] mark {
  background-color: #ffc107;
  color: #212529;
}

.select2-container--default .select2-results__option {
  color: #333 !important;
}

.select2-container--default .select2-results__option:hover {
  background-color: #f8f9fa;
  color: #333 !important;
}

.select2-dropdown {
  border: 1px solid #ddd;
  border-radius: 4px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

</style>
 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">


<?php 
if(isset($_POST['submit'])){
    $StockDate = addslashes(trim($_POST['StockDate']));
    $TotQty = addslashes(trim($_POST['TotQty']));
    $Narration = addslashes(trim($_POST['Narration']));
    $FromFrId = addslashes(trim($_POST['FromFrId']));
    $ToFrId = addslashes(trim($_POST['ToFrId']));
    $CreatedDate = date('Y-m-d');
    $sql = "INSERT INTO tbl_transfer_franchise_prod_stock_2025 SET FromFrId='$FromFrId',ToFrId='$ToFrId',StockDate='$StockDate',TotQty='$TotQty',Narration='$Narration',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
    $conn->query($sql);
    $UseRawId = mysqli_insert_id($conn);
    
    $url = $_SERVER['REQUEST_URI'];
   $createddate = date('Y-m-d H:i:s');
   $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='transfer franchise to franchise stock added',invid='$UseRawId',createddate='$createddate',roll='transfer-franchise-to-franchise-stock'";
  $conn->query($sql);

    $number = count($_POST["ProdId"]);
                    if ($number > 0) {
                        for ($i = 0; $i < $number; $i++) {
                            if (trim($_POST["ProdId"][$i] != '')) {
                                $ProdId = addslashes(trim($_POST['GodownProdId'][$i]));
                                $Qty = addslashes(trim($_POST['Qty'][$i]));
                                $Unit = addslashes(trim($_POST['QtyUnit'][$i]));
                                $FrProdId = addslashes(trim($_POST['ProdId'][$i]));
                                $Price = addslashes(trim($_POST['Price'][$i]));
                                $TotalPrice = addslashes(trim($_POST['TotalPrice'][$i]));
                                $sql33 = "INSERT INTO tbl_transfer_franchise_prod_stock_items_2025 SET Price='$Price',TotalPrice='$TotalPrice',FromFrId='$FromFrId',ToFrId='$ToFrId',ProdId='$ProdId',FrProdId='$FrProdId',Qty='$Qty',Unit='$Unit',CreatedDate='$CreatedDate',TransferId='$UseRawId',CreatedBy='$user_id',StockDate='$StockDate'";
                                $conn->query($sql33);
                                
                                $sql22 = "INSERT INTO tbl_cust_prod_stock_2025 SET SellPrice='$Price',UserId='$FromFrId',ProdId='$ProdId',Qty='$Qty',Unit='$Unit',CreatedDate='$CreatedDate',TransferId='$UseRawId',Status='Dr',Narration='$Narration',StockDate='$StockDate',CreatedBy='$user_id',FrId='$FromFrId'";
                                $conn->query($sql22);
                            }
                        }
                    }

                    echo "<script>alert('Record Saved Successfully!');window.location.href='view-transfer-franchise-to-franchise-stock.php';</script>";
}


?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Transfer Stock Franchise To Franchise</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">

<div class="form-row">
<div class="form-group col-md-6">
<label class="form-label"> From Franchise <span class="text-danger">*</span></label>
 <select class="form-control" name="FromFrId" id="FromFrId" required>

<!--<option selected="" value="">Select Franchise</option>-->
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE id='$BillSoftFrId'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["FromFrId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ShopName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label"> To Franchise <span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="ToFrId" id="ToFrId" required onchange="getFrRawProduct(this.value)">

<option selected="" value="">Select Franchise</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users_bill WHERE Roll=5 AND Status=1 AND id!='$BillSoftFrId' AND ShowFrStatus=1 ORDER BY ShopName";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ToFrId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['ShopName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

</div>

 <div id="dynamic_field">
    <div class="form-row">
        
        <div class="form-group col-md-2">
<label class="form-label">From Franchise Product</label>
 <select class="select2-demo form-control GodownProdId" name="GodownProdId[]" id="GodownProdId1" data-allow-clear="true" data-placeholder="Search and select godown product..." onchange="getAvailProdStock(this.value,document.getElementById('srno1').value)">
<option selected="" value="">Select Product</option>
 <?php 
  /*$sql12 = "SELECT id,ProductName FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$BillSoftFrId' AND Transfer=1 AND ProdType=0 AND ProdType2 IN (1,3) AND checkstatus=1 UNION ALL SELECT id,ProductName FROM tbl_cust_products2 WHERE id IN($AllocateRawProd)  ORDER BY ProductName";*/
  $sql12 = "SELECT id,ProductName,ProdType2 FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$BillSoftFrId' AND Transfer=1 AND ProdType=0 AND ProdType2 IN (1,3) AND checkstatus=1  ORDER BY ProductName";
  $row12 = getList($sql12);
  foreach($row12 as $result){
      if($result['ProdType2'] == 1){
          $ProdType2 = "MRP";
      }
      else if($result['ProdType2'] == 3){
          $ProdType2 = "Other";
      }
     ?> 
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['ProductName']." (".$ProdType2.")"; ?></option>
<?php } ?>
</select>
</div>

<div class="form-group col-md-2">
<label class="form-label">To Franchise Product</label>
 <select class="select2-demo form-control ProdId" name="ProdId[]" id="ProdId1" data-allow-clear="true" data-placeholder="Search and select product...">
<option selected="" value="">Select Product</option>
 
</select>
</div>

<div class="form-group col-md-2">
<label class="form-label">Available Stock </label>
<div class="input-group">
    <input type="text" name="AvailStock[]" id="AvailStock1" class="form-control" placeholder="" value="" autocomplete="off" readonly>
        <div class="input-group-append">
            <input type="text" name="AvailStockUnit[]" id="AvailStockUnit1" class="form-control" placeholder="" value="" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Qty </label>
<div class="input-group">
    <input type="text" name="Qty[]" id="Qty1" class="form-control" placeholder="" value="" autocomplete="off" required oninput="calculateTotal(1)">
        <div class="input-group-append">
            <input type="text" name="QtyUnit[]" id="QtyUnit1" class="form-control" placeholder="" value="<?php echo $row7["Unit"]; ?>" autocomplete="off" readonly style="width: 50px;">
        </div>
    </div>
</div>

<div class="form-group col-md-1">
<label class="form-label">Price </label>
<input type="text" name="Price[]" id="Price1" class="form-control" placeholder="" value="" autocomplete="off" oninput="calculateTotal(1)">
</div>

<div class="form-group col-md-2">
<label class="form-label">Total Price </label>
<input type="text" name="TotalPrice[]" id="TotalPrice1" class="form-control" placeholder="" value="" autocomplete="off" readonly>
</div>

<input type="hidden" class="form-control" name="srno[]" id="srno1" value="1">

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" id="add_more">+</button>
</div>
</div>
</div>

<div class="form-row">



<div class="form-group col-md-3">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="StockDate" id="StockDate" class="form-control txt" placeholder="" value="<?php echo date('Y-m-d'); ?>" autocomplete="off" required>
<div class="clearfix"></div>
    </div>

<!--<div class="form-group col-md-6">
<label class="form-label">Total Qty </label>
<input type="text" name="TotQty" id="TotQty" class="form-control" placeholder="" value="<?php echo $row7["TotQty"]; ?>" autocomplete="off" readonly>
<div class="clearfix"></div>
    </div>-->

    
<div class="form-group col-md-9">
   <label class="form-label">Narration <span class="text-danger">*</span></label>
     <input type="text" name="Narration" id="Narration" class="form-control" required value="Stock Used">
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
    
    function getToFrSlectProd(id,srno){
        var action = "getToFrSlectProd";
        var ToFrId = $('#ToFrId').val();
        $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
                method: "POST",
                data: {
                    action: action,
                    id: id,
                    ToFrId:ToFrId
                },
                success: function(data) {
                  //alert(data);
                  console.log(data);
                  $('#ProdId'+srno).html(data);
                  // Reinitialize Select2 for the updated select element
                  $('#ProdId'+srno).select2({
                    placeholder: 'Search and select product...',
                    allowClear: true,
                    minimumInputLength: 0,
                    escapeMarkup: function(markup) {
                      return markup;
                    },
                    templateResult: function(data) {
                      if (data.loading) {
                        return 'Searching...';
                      }
                      var term = $('.select2-search__field').val();
                      if (term && data.text) {
                        var highlighted = data.text.replace(new RegExp(term, 'gi'), '<mark>$&</mark>');
                        return $('<span>' + highlighted + '</span>');
                      }
                      return data.text;
                    },
                    templateSelection: function(data) {
                      return data.text;
                    },
                    language: {
                      noResults: function() {
                        return 'No products found';
                      },
                      searching: function() {
                        return 'Searching products...';
                      }
                    }
                  });
                }
            });
    }
    function getAvailProdStock(id,srno){
        getToFrSlectProd(id,srno);
     var action = "getAvailProdStock2";
     var FromFrId = $('#FromFrId').val();
            $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
                method: "POST",
                data: {
                    action: action,
                    id: id,
                    FromFrId:FromFrId
                },
                success: function(data) {
                  //alert(data);
                  console.log(data);
                   var res = JSON.parse(data);
                   $('#AvailStockUnit'+srno).val(res.Unit);
                    $('#QtyUnit'+srno).val(res.Unit);
                    $('#AvailStock'+srno).val(res.balqty);
                    $('#Price'+srno).val(res.Price);
                    calculateTotal(srno);
                }
            });
  }
  
     
    
    
    function getFrRawProduct(val){
        var action = "getFrRawProduct";
        $.ajax({
                url: "ajax_files/ajax_raw_stock.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    console.log(data);
                   $('.ProdId').html(data);
                   // Reinitialize Select2 after updating options
                   initializeSelect2();
                }
            });
    }

    $(document).ready(function(){
  var i=1; 
    $('#add_more').click(function(){  
           i++;  
       var action = "getMoreFr";
       var FranchiseId = $('#ToFrId').val();
    $.ajax({
    url:"ajax_files/ajax_raw_stock.php",
    method:"POST",
    data : {action:action,id:i,FranchiseId:FranchiseId},
    success:function(data)
    {
      $('#dynamic_field').append(data);
      // Reinitialize Select2 for newly added elements
      initializeSelect2();
      // Specifically initialize Select2 for the new GodownProdId element
      $('#GodownProdId'+i).select2({
        placeholder: 'Search and select godown product...',
        allowClear: true,
        minimumInputLength: 0,
        escapeMarkup: function(markup) {
          return markup;
        },
        templateResult: function(data) {
          if (data.loading) {
            return 'Searching...';
          }
          var term = $('.select2-search__field').val();
          if (term && data.text) {
            var highlighted = data.text.replace(new RegExp(term, 'gi'), '<mark>$&</mark>');
            return $('<span>' + highlighted + '</span>');
          }
          return data.text;
        },
        templateSelection: function(data) {
          return data.text;
        },
        language: {
          noResults: function() {
            return 'No products found';
          },
          searching: function() {
            return 'Searching products...';
          }
        }
      });
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

  // Enhanced Select2 configuration for product search
  function initializeSelect2() {
    $('.ProdId, .GodownProdId').select2({
      placeholder: 'Search and select product...',
      allowClear: true,
      minimumInputLength: 0,
      escapeMarkup: function(markup) {
        return markup;
      },
      templateResult: function(data) {
        if (data.loading) {
          return 'Searching...';
        }
        // Highlight search term in results
        var term = $('.select2-search__field').val();
        if (term && data.text) {
          var highlighted = data.text.replace(new RegExp(term, 'gi'), '<mark>$&</mark>');
          return $('<span>' + highlighted + '</span>');
        }
        return data.text;
      },
      templateSelection: function(data) {
        return data.text;
      },
      language: {
        noResults: function() {
          return 'No products found';
        },
        searching: function() {
          return 'Searching products...';
        }
      }
    });
  }

  // Function to calculate total price
  function calculateTotal(srno) {
    var price = parseFloat($('#Price' + srno).val()) || 0;
    var qty = parseFloat($('#Qty' + srno).val()) || 0;
    var total = price * qty;
    $('#TotalPrice' + srno).val(total.toFixed(2));
  }

  $(document).ready(function() {
    // Initialize Select2 for existing elements
    initializeSelect2();
  });
</script>
</body>
</html>