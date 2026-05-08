<?php 
session_start();
include_once 'config.php';
require_once("dbcontroller.php");
$db_handle = new DBController();
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Manager-Vendor-Expense-Request";
$Page = "Manager-Vendor-Peding-Expense-Request";
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
    table, td, th {  
  border: 1px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 5px;
}
    </style>
     <style>
        .expense-block {
            position: relative;
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .btn-group-container {
            display: flex;
            gap: 10px;
            justify-content: flex-start;
            padding: 10px 20px;
        }

        .autocomplete-list3 {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background-color: white;
            z-index: 1000;
            width: 100%;
            margin-top: 35px;
        }

        .autocomplete-list2 {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background-color: white;
            z-index: 1000;
            width: 100%;
            margin-top: 35px;
        }

        .autocomplete-list {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            background-color: white;
            z-index: 1000;
            width: 100%;
        }

        .autocomplete-item {
            padding: 8px;
            cursor: pointer;
        }

        .autocomplete-item:hover,
        .autocomplete-item.active {
            background-color: #98e6ed;
        }
    </style>
     <div class="layout-wrapper layout-1 layout-without-sidenav">
        <div class="layout-inner">

             <?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>

<?php 
$id = $_GET['id'];
$sql7 = "SELECT tp.*,tu.ShopName FROM tbl_fr_req_stock_inv tp 
            LEFT JOIN tbl_users_bill tu ON tu.id=tp.FrId WHERE tp.id='$id'";
$row7 = getRecord($sql7);

if(isset($_POST['submit'])){
    $PurchaseApproveDate = addslashes(trim($_POST["PurchaseApproveDate"]));
     $ManagerComments = addslashes(trim($_POST["ManagerComments"]));
     $ManagerStatus = addslashes(trim($_POST["ManagerStatus"]));
  $CreatedDate = date('Y-m-d H:i:s');
   $ItemIds = $_POST['ItemId'];
   $PurchaseQtys = $_POST['PurchaseQty'];
    $PurchaseDates = $_POST['PurchaseDate'];
    $PurchaseStatuses = $_POST['PurchaseStatus'];
    $PurchaseComments = $_POST['PurchaseComment'];
  
  $sql = "UPDATE tbl_fr_req_stock_inv SET PurchaseBy='$user_id',PurchaseApproveDate='$PurchaseApproveDate',PurchaseComments='$ManagerComments',PurchaseStatus='$ManagerStatus' WHERE id='$id'";
  $conn->query($sql);
  $count = count($ItemIds);
  for ($i = 0; $i < $count; $i++) {
        $ItemId = addslashes(trim($ItemIds[$i]));
        $PurchaseQty = addslashes(trim($PurchaseQtys[$i]));
        $PurchaseDate = addslashes(trim($PurchaseDates[$i]));
        $PurchaseStatus = addslashes(trim($PurchaseStatuses[$i]));
        $PurchaseComment = addslashes(trim($PurchaseComments[$i]));

        $sql = "UPDATE tbl_fr_req_prod_stock 
                SET 
                    PurchaseQty = '$PurchaseQty',
                    PurchaseDate = '$PurchaseDate',
                    PurchaseStatus = '$PurchaseStatus',
                    PurchaseComment = '$PurchaseComment',
                    PurchaseBy = '$user_id'
                WHERE id = '$ItemId' AND InvId='$id'";
        
        $conn->query($sql);
    }

echo "<script>alert('Approved Successfully');window.location.href='view-other-product-stock-request.php';</script>";
}
?>
            <div class="layout-container">

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Approve Other Product Stock Request</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                     <div class="form-group col-md-2">
                                            <label class="form-label">Invoice ID</label>
                                            <input type="text" class="form-control"
                                                placeholder="" value="<?php echo $row7['id']; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-md-2">
                                            <label class="form-label">Invoice No</label>
                                            <input type="text" name="TradeName" class="form-control"
                                                placeholder="" value="<?php echo $row7['InvNo']; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Franchise Name</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['ShopName']; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                       
                                        
                                      
                                        
                                        <div class="form-group col-md-2">
                                            <label class="form-label">Invoice Date</label>
                                            <input type="date" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["StockDate"]; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <?php 
                                         $sql2 = "SELECT tve.*,tcp.ProductName FROM tbl_fr_req_prod_stock tve INNER JOIN tbl_cust_products_2025 tcp ON tcp.id=tve.ProdId WHERE tve.InvId='".$_GET['id']."'";
               $rncnt2 = getRow($sql2);
               ?>
                                        <div class="form-group col-md-2">
                                            <label class="form-label">Total Qty</label>
                                            <input type="text" name="TaskDate" class="form-control"
                                                placeholder="" value="<?php echo $rncnt2; ?>" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                          
                                       
                                        
                                         <div class="form-group col-md-12">
                                            <label class="form-label">Narration</label>
                                            <textarea name="TaskDate" class="form-control"
                                                placeholder=""><?php echo $row7['Narration']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>
                                         </div>
                                        
                                        <?php 
                                       
                                        if($rncnt2 > 0){?>
                                        <br>
                                            <div class="form-row">
    <h5 style="font-size: 15px;color: blue;padding-left: 10px;">MRP Products</h5>
<div style="overflow-x: auto; width: 100%;">
    <table class="table table-bordered">
  <tr>
    <th>Sr.No</th>
    <th>Product Name</th>
    <th>Req Qty</th>
   <!-- <th>Purchase Price</th>
    <th>Sell Price</th>-->
    <th>Approve Qty</th>
    <th>Date</th>
    <th>Status</th>
    <th>Comment</th>
    <!--<th>Total Price</th>-->
   
  </tr>
   <?php 
  $i=1;
  
  $row2 = getList($sql2);
  foreach($row2 as $result){
  $total = $result['Qty2'];
            $grandTotal += $result['Qty2'];
  ?>
  <tr>
    <td><?php echo $i;?></td>
   <td><?php echo $result['ProductName']; ?></td>
            <td><?php echo $result['Qty2']." ".$result['Unit2'] ; ?></td>
           <!-- <td><?php echo $result['PurchasePrice']; ?></td>
             <td><?php echo $result['SellPrice']; ?></td>-->
            <!--<td><?php echo round($total); ?></td>-->
            <input type="hidden" name="ItemId[]" class="form-control" value="<?php echo $result['id'];?>">
  <td><div class="input-group">
        <input type="text" name="PurchaseQty[]" class="form-control" value="<?php echo $result['PurchaseQty']; ?>">
        <div class="input-group-append">
            <span class="input-group-text" style="width: 50px;"><?php echo $result['Unit2']; ?></span>
        </div>
    </div>
  </td>
  <td><input type="date" name="PurchaseDate[]" class="form-control" value="<?php echo date('Y-m-d');?>"></td>
  <td><select class="form-control" id="PurchaseStatus<?php echo $i;?>" name="PurchaseStatus[]" required="" onchange="getAutoComment(this.value,<?php echo $i;?>)">
                                                <option selected="" disabled="" value="">Select Status</option>
                                                <option value="1" <?php if($result["PurchaseStatus"]=='1') {?> selected
                                                    <?php } ?>>Approved</option>
                                                <option value="0" <?php if($result["PurchaseStatus"]=='0') {?> selected
                                                    <?php } ?>>Pending</option>
                                                    <option value="2" <?php if($result["PurchaseStatus"]=='2') {?> selected
                                                    <?php } ?>>Reject</option>
                                            </select></td>
  <td><input type="text" class="form-control" value="<?php echo $result['PurchaseComment'];?>" id="PurchaseComment<?php echo $i;?>" name="PurchaseComment[]"></td>
                     
  </tr>
  <?php $i++;} ?>
 <!-- <tfoot>
        <tr>
            <td colspan="2" style="text-align: right;"><strong>Total Qty:</strong></td>
            <td><strong><?php echo round($grandTotal); ?></strong></td>
        </tr>
    </tfoot>-->
</table>
</div>
                                            </div>
<br>
<?php } ?>

                     
                     <div class="form-row">                   
 <div class="form-group col-md-2">
                                            <label class="form-label">Approve Date <span class="text-danger">*</span></label>
                                            <input type="date" name="PurchaseApproveDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>" required readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <!--<div class="form-group col-md-4">
                                            <label class="form-label">GRN No <span class="text-danger">*</span></label>
                                            <input type="text" name="GrnNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["GrnNo"]; ?>" required>
                                            <div class="clearfix"></div>
                                        </div>-->

<!-- <div class="form-group col-md-2">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ManagerStatus" name="ManagerStatus" required="">
                                                <option selected="" disabled="" value="">Select Status</option>
                                                <option value="1" <?php if($row7["PurchaseStatus"]=='1') {?> selected
                                                    <?php } ?>>Approved</option>
                                                <option value="0" <?php if($row7["PurchaseStatus"]=='0') {?> selected
                                                    <?php } ?>>Pending</option>
                                                    <option value="2" <?php if($row7["PurchaseStatus"]=='2') {?> selected
                                                    <?php } ?>>Reject</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>-->
 <div class="form-group col-md-10">
                                            <label class="form-label">Narration</label>
                                            <input type="text" name="ManagerComments" class="form-control"
                                                placeholder="" value="<?php echo $row7["PurchaseComments"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                           <!-- <div class="form-group col-md-6">
                                            <label class="form-label">Invoice</label><br>
                                         <?php if($row7['Photo'] == '') {} else{?>
                        <a href="../uploads/<?php echo $row7['Photo']; ?>" target="_blank"><img src="../uploads/<?php echo $row7['Photo']; ?>"  style="height:100px;"></a><?php } ?>
                                            <div class="clearfix"></div>
                                        </div>
                                      
                                        <div class="form-group col-md-6">
                                            <label class="form-label">Payment Receipt</label><br>
                                            <?php if($row7['Photo2'] == '') {} else{?>
                        <a href="../uploads/<?php echo $row7['Photo2']; ?>" target="_blank">View Pdf</a><?php } ?>
                                            <div class="clearfix"></div>
                                        </div>-->

                                        
</div>

  


                                   <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Approve</button>
                                    </div>

                
                                    </div>
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

 <script>
 function getAutoComment(val,srno){
      if(val == 1){
         $('#PurchaseComment'+srno).val('Approved'); 
      }
      else if(val == 2){
          $('#PurchaseComment'+srno).val('Rejected');
      }
      else{
         $('#PurchaseComment'+srno).val('Pending'); 
      }
 }
 function edit_prod(code,prdsrno) {
    // Assuming you have access to session data via AJAX (recommended)
    $.ajax({
        url: 'ajax_files/ajax_exp_mult_products.php',
        method: 'POST',
        data: { action: 'getProduct', code: code,prdsrno:prdsrno },
        success: function(response) {
            console.log(response);
            var data = JSON.parse(response);
            $('#ProductName').val(data.ProductName);
            $('#ProdId').val(data.id);
            $('#Qty').val(data.Qty);
            $('#PurchasePrice').val(data.PurchasePrice);
            $('#Unit').val(data.Unit);
            $('#SellPrice').val(data.SellPrice);
            $('#code').val(data.code); // Important to track the product

            $('#myModal').modal('show');
        }
    });
}
      function openModal(srno) {
            $('#myModal').modal('show');
            $('#prdsrno').val(srno);
            displayCart();
        }
        
        let currentFocus3 = -1;

        $(document).ready(function() {
            displayCart();
            $("#ProductName").on("input", function() {
                let ProductName = $(this).val();
                if (ProductName.length === 0) {
                    $("#autocomplete-list3").hide();
                    return;
                }
                var action = "getProductList";
                var FrId = $('#Locations').val();
                //alert(FrId);
                $.ajax({
                    url: "ajax_files/ajax_dropdown.php",
                    method: "POST",
                    data: {
                        action: action,
                        ProductName: ProductName,
                        FrId: FrId
                    },
                    success: function(data) {
                        console.log(data);
                        $("#autocomplete-list3").empty().show();
                        currentFocus3 = -1;

                        if (data.length === 0) {
                            $("#autocomplete-list3").hide();
                            return;
                        }

                        data.forEach(function(item) {
                            $("#autocomplete-list3").append(`<div class="autocomplete-item" onclick="getAvailProdStock(${item.id})">${item.ProductName}</div>`);
                        });

                        $(".autocomplete-item").on("click", function() {
                            $("#ProductName").val($(this).text());
                            $("#autocomplete-list3").hide();
                        });
                    }
                });
            });

            $("#ProductName").on("keydown", function(e) {
                let items = $(".autocomplete-item");

                if (e.key === "ArrowDown") {
                    currentFocus3++;
                    if (currentFocus3 >= items.length) currentFocus3 = 0;
                    setActive(items);
                    e.preventDefault();
                } else if (e.key === "ArrowUp") {
                    currentFocus3--;
                    if (currentFocus3 < 0) currentFocus3 = items.length - 1;
                    setActive(items);
                    e.preventDefault();
                } else if (e.key === "Enter") {
                    e.preventDefault();
                    if (currentFocus3 > -1 && items[currentFocus3]) {
                        items.eq(currentFocus3).click();
                    }
                }
            });

            function setActive(items) {
                items.removeClass("active");
                if (currentFocus3 >= 0 && currentFocus3 < items.length) {
                    items.eq(currentFocus3).addClass("active");
                    items.eq(currentFocus3)[0].scrollIntoView({
                        block: "nearest"
                    });
                }
            }

            $(document).click(function(e) {
                if (!$(e.target).closest("#ProductName, #autocomplete-list3").length) {
                    $("#autocomplete-list3").hide();
                }
            });
        });

        function addToCart() {
            var action = "addToCart";
            var code = $('#code').val();
            var ProdId = $('#ProdId').val();
            var quantity = $('#Qty').val();
            var Unit = $('#Unit').val();
            var PurchasePrice = $('#PurchasePrice').val();
            var SellPrice = $('#SellPrice').val();
            var prdsrno = $('#prdsrno').val();
            if (ProdId == '') {
                alert("Please Select Product");
            } else if (quantity == '') {
                alert("Please Enter Qty");
            } else {
                $.ajax({
                    url: "ajax_files/ajax_exp_mult_products.php",
                    method: "POST",
                    data: {
                        action: action,
                        code: code,
                        quantity: quantity,
                        id: ProdId,
                        PurchasePrice: PurchasePrice,
                        SellPrice: SellPrice,
                        prdsrno: prdsrno,
                        Unit:Unit
                    },
                    beforeSend: function() {
                        $('#add').attr('disabled', 'disabled');
                        $('#add').text('Please Wait...');
                    },
                    success: function(data) {
                        //alert(data);
                        console.log(data);
                        displayCart();
                        $('#add').attr('disabled', false);
                        $('#add').text('Add');
                        $('#code').val('');
                        $('#ProductName').val('');
                        $('#ProdId').val(0).attr("selected", true);
                        $('#Qty').val('');
                        $('#Unit').val('');
                        $('#PurchasePrice').val('');
                        $('#SellPrice').val('');
                        $('#AvailStock').val('');
                        
                    }
                });
            }
        }

        function displayCart() {
            var action = "displayCart";
            var prdsrno = $('#prdsrno').val();
            $.ajax({
                url: "ajax_files/ajax_exp_mult_products.php",
                type: "POST",
                data: {
                    action: action,
                    prdsrno: prdsrno
                },
                success: function(data) {
                    console.log(data);
                    $('#showcart').html(data);
                    $('#showcart2' + prdsrno).html(data);
                    calTotalQty();
                    calTotal_Amt();
                    calTotAmt();
                },

            });
        }

        function calTotalQty() {
             var prdsrno = $('#prdsrno').val();
            var action = "calTotalQty";
            $.ajax({
                url: "ajax_files/ajax_exp_mult_products.php",
                type: "POST",
                data: {
                    action: action,
                    prdsrno:prdsrno
                },
                success: function(data) {
                    console.log(data);
                    if(data <= 0){
                        $('#submit').prop('disabled', false);
                    }
                    else{
                        $('#submit').prop('disabled', false);
                    }
                    //$('#TotQty').val(data);
                },

            });
        }
        
        function calTotal_Amt() {
             var prdsrno = $('#prdsrno').val();
            var action = "calTotalAmt";
            $.ajax({
                url: "ajax_files/ajax_exp_mult_products.php",
                type: "POST",
                data: {
                    action: action,
                    prdsrno:prdsrno
                },
                success: function(data) {
                   //alert(data);
                   $('#TotalAmt'+ prdsrno).val(data);
                },

            });
        }
        
        

        function delete_prod(code) {
            var prdsrno = $('#prdsrno').val();
            if (confirm("Are you sure you want to delete Record?")) {
                var action = "delete_cart_prod";
                $.ajax({
                    url: "ajax_files/ajax_exp_mult_products.php",
                    type: "POST",
                    data: {
                        action: action,
                        code: code,
                        prdsrno: prdsrno
                    },
                    success: function(data) {
                        console.log(data);
                        displayCart();

                    },

                });
            }
        }

        function getProdDetails(id) {
            var action = "getProdDetails";
            $.ajax({
                url: "ajax_files/ajax_exp_mult_products.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                    //alert(data);
                    console.log(data);
                    var res = JSON.parse(data);
                    $('#PurchasePrice').val(res.PurchasePrice);
                    $('#SellPrice').val(res.MinPrice);
                    $('#code').val(res.code);
                    $('#Unit').val(res.Unit);

                }
            });
        }

        function getAvailProdStock(id) {
            $('#ProdId').val(id);
            getProdDetails(id);
            var action = "getAvailProdStock";
            $.ajax({
                url: "ajax_files/ajax_exp_mult_products.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                    //alert(data);
                    console.log(data);
                    $('#AvailStock').val(data);

                }
            });
        }
    </script>
</body>

</html>