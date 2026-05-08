<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Stock";
$Page = "Add-Cust-Stocks-By-Barcode";
$sessionid = session_id();
?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
  <title><?php echo $Proj_Title; ?> - Product</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="">
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

      <?php include_once 'top_header.php';
      include_once 'sidebar.php'; ?>


      <div class="layout-container">


        <?php
        $id = $_GET['id'];
        $sql7 = "SELECT * FROM tbl_cust_prod_stock_2025 WHERE id='$id'";
        $res7 = $conn->query($sql7);
        $row7 = $res7->fetch_assoc();
        $InvoiceDate = date('Y-m-d');
        ?>

        <?php
        if (isset($_POST['submit'])) {
        //   $ProdId = $_POST['ProdId'];
        //   $Qty = addslashes(trim($_POST['Qty']));
          $StockDate = addslashes(trim($_POST['StockDate']));
          $CreatedDate = date('Y-m-d');
          $Narration = addslashes(trim($_POST['Narration']));
        //   $PurchasePrice = addslashes(trim($_POST['PurchasePrice']));
        //   $SellPrice = addslashes(trim($_POST['SellPrice']));

          if ($_GET['id'] == '') {
              
              $number = count($_POST['BarcodeNo']);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["BarcodeNo"][$i] != ''))  
              {
                  $BarcodeNo = addslashes(trim($_POST['BarcodeNo'][$i]));
                  $ProductName = addslashes(trim($_POST['ProductName'][$i]));
                  $ProdId = addslashes(trim($_POST['ProdId'][$i]));
                  $AvailStock = addslashes(trim($_POST['AvailStock'][$i]));
                  $Qty = addslashes(trim($_POST['Qty'][$i]));
                  $PurchasePrice = addslashes(trim($_POST['PurchasePrice'][$i]));
                  $SellPrice = addslashes(trim($_POST['SellPrice'][$i]));
                  
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

            file_put_contents('stock_backup/' . $BillSoftFrId . '_backup.sql', $sqlDump, FILE_APPEND | LOCK_EX);


            $sql = "UPDATE tbl_cust_products_2025 SET PurchasePrice='$PurchasePrice' WHERE id='$ProdId'";
            $conn->query($sql);

            $url = $_SERVER['REQUEST_URI'];
            $createddate = date('Y-m-d H:i:s');
            $sql = "INSERT INTO tbl_user_logs SET userid='$user_id',frid='$BillSoftFrId',url='$url',action='new customer product stock added',invid='$InvId',createddate='$createddate',roll='customer-product-stock'";
            $conn->query($sql);
            
              }
              }
            }

                  
             echo "<script>alert('Product Stock Added Successfully!');window.location.href='view-cust-stocks-2025.php';</script>";
           
          } 



          //header('Location:courses.php'); 

        }
        ?>

        <div class="layout-content">

          <div class="container-fluid flex-grow-1 container-p-y">
            <h4 class="font-weight-bold py-3 mb-0">Product Stock</h4>


            <div class="card mb-4">
              <div class="card-body">
                <div id="alert_message"></div>
                <div class="row">
                  <div class="col-lg-12">
                    <form id="validation-form" method="post" enctype="multipart/form-data">
                      <div id="mobile-list">
                        <div class="form-row" id="row1">
                          <div class="form-group col-md-2">
                            <label class="form-label">Scan Barcode No </label>
                            <input type="text" name="BarcodeNo[]" id="BarcodeNo1" class="form-control imei-input" placeholder="" value="" autocomplete="off"  onchange="checkBarcodeNo(1, <?php echo $BillSoftFrId; ?>)">
                            <div class="clearfix"></div>
                          </div>

                          <div class="form-group col-md-3">
                            <label class="form-label">Product Name </label>
                            <input type="text" name="ProductName[]" id="ProductName1" class="form-control" placeholder="" value="" autocomplete="off" >
                            <div class="clearfix"></div>
                          </div>

                          <input type="hidden" name="ProdId[]" id="ProdId1" value="">

                          <div class="form-group col-md-2">
                            <label class="form-label">Available Stock </label>
                            <input type="text" name="AvailStock[]" id="AvailStock1" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                            <div class="clearfix"></div>
                          </div>

                          <div class="form-group col-md-1">
                            <label class="form-label">Stock In Qty <span class="text-danger">*</span></label>
                            <input type="text" name="Qty[]" id="Qty1" class="form-control" placeholder="" value="<?php echo $row7["Qty"]; ?>" autocomplete="off" >
                            <div class="clearfix"></div>
                          </div>

                          <div class="form-group col-md-2">
                            <label class="form-label">Purchase Price <span class="text-danger">*</span></label>
                            <input type="text" name="PurchasePrice[]" id="PurchasePrice1" class="form-control" placeholder="" value="" autocomplete="off" >
                            <div class="clearfix"></div>
                          </div>

                          <div class="form-group col-lg-2">
                            <label class="form-label">Sell Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                              <input type="text" name="SellPrice[]" class="form-control txt" id="SellPrice1" >
                              <span class="input-group-append">
                                <button class="btn btn-danger btn_remove" type="button" onclick="removeRow(1)"><i class="feather icon-x"></i></button>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-2">
                          <label class="form-label">Date <span class="text-danger">*</span></label>
                          <input type="date" name="StockDate" id="StockDate" class="form-control" placeholder="" value="<?php echo $row7["StockDate"]; ?>" autocomplete="off" required>
                          <div class="clearfix"></div>
                        </div>

                        <div class="form-group col-md-10">
                          <label class="form-label">Narration</label>
                          <input type="text" name="Narration" id="Narration" class="form-control" value="<?php echo $row7['Narration']; ?>">
                          <div class="clearfix"></div>
                        </div>

                      </div>






                      <button type="submit" name="submit" id="submit" class="btn btn-primary btn-finish">Submit</button>
                      <span id="error_msg" style="color:red;"></span>

                    </form>
                  </div>
                  <div class="col-lg-4" id="showcart">


                  </div>
                </div>
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
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("mobile-list").addEventListener("change", function (event) {
        if (event.target.classList.contains("imei-input")) {
            let rowId = event.target.id.replace("BarcodeNo", "");
            let imeiValue = event.target.value.trim();
            
            if ((imeiValue.length === 8 || imeiValue.length === 12 || imeiValue.length === 13) && !event.target.dataset.processed) {
                event.target.dataset.processed = "true"; 
                setTimeout(() => { 
                    addNewRow(rowId);
                    checkBarcodeNo(rowId, <?php echo $BillSoftFrId; ?>);
                }, 100); 
            }
        }
    });
});

let rowCount = 1;

function addNewRow(previousRowId) {
    rowCount++;
    let originalRow = document.getElementById("row" + previousRowId);
    let newRow = originalRow.cloneNode(true);

    newRow.id = "row" + rowCount;
    
    newRow.querySelectorAll("input").forEach(input => {
        let oldId = input.id;
        let newId = oldId.replace(/\d+$/, rowCount);
        input.id = newId;
        input.value = "";
        input.removeAttribute("data-processed"); // Reset the processed flag

        // Update 'oninput' function dynamically for getMobileDetails()
        if (input.getAttribute("oninput") && input.getAttribute("oninput").includes("checkBarcodeNo")) {
            input.setAttribute("oninput", `checkBarcodeNo(${rowCount}, <?php echo $BillSoftFrId; ?>)`);
        }
    });

    // Update remove button dynamically
    let removeButton = newRow.querySelector(".btn_remove");
    if (removeButton) {
        removeButton.setAttribute("onclick", `removeRow(${rowCount})`);
        removeButton.id = rowCount; // Update button ID
    }

    // Append new row to the form
    document.getElementById("mobile-list").appendChild(newRow);

    // Move cursor to the next row's Barcode No field
    let nextImeiField = document.getElementById(`BarcodeNo${rowCount}`);
    if (nextImeiField) {
        nextImeiField.focus();
    }
}

function removeRow(rowId) {
    let rowElement = document.getElementById("row" + rowId);
    if (confirm("Are you sure you want to delete?")) {
        if (document.querySelectorAll(".form-row").length > 1) {
            rowElement.remove();
        }
        getSubTotal();
    }
}


</script>
  <script>
    
   function checkBarcodeNo(srno, frid) {
    var barcodeInput = $('#BarcodeNo' + srno);
    var barcode = barcodeInput.val().trim();

    if (!barcode) return; // Exit if barcode is empty

    var action = "checkBarcodeNo";

    barcodeInput.removeAttr("data-processed"); // Reset before AJAX call

    setTimeout(function () {  
        $.ajax({
            url: "ajax_files/ajax_shop_products_2025.php",
            method: "POST",
            data: {
                action: action,
                barcode: barcode,
                frid: frid
            },
            success: function (data) {
                console.log(data);
                try {
                    var res = JSON.parse(data);
                    $('#ProductName' + srno).val(res.ProductName);
                    $('#ProdId' + srno).val(res.ProdId);
                    getProdDetails(res.ProdId,srno);
                    getAvailProdStock(res.ProdId,srno);
                } catch (error) {
                    console.error("Invalid JSON response", error);
                }
            }
        });
    }, 100); 
}


    function getProdDetails(id,srno) {
      var action = "getProdDetails";
      $.ajax({
        url: "ajax_files/ajax_shop_products_2025.php",
        method: "POST",
        data: {
          action: action,
          id: id
        },
        success: function(data) {
          //alert(data);
          console.log(data);
          var res = JSON.parse(data);

          $('#PurchasePrice'+srno).val(res.PurchasePrice);
          $('#SellPrice'+srno).val(res.MinPrice);


        }
      });
    }

    function getAvailProdStock(id,srno) {
      var action = "getAvailProdStock";
      $.ajax({
        url: "ajax_files/ajax_raw_stock_2025.php",
        method: "POST",
        data: {
          action: action,
          id: id
        },
        success: function(data) {
          //alert(data);
          console.log(data);
          $('#AvailStock'+srno).val(data);

        }
      });
    }

    function getSubTotal() {
      var sum = 0;
      $(".txt").each(function() {
        if (!isNaN(this.value) && this.value.length != 0) {
          sum += parseFloat(this.value);
        }
      });
      $('#TotQty').val(sum);

    }

   
  </script>
</body>

</html>