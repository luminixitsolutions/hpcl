<?php
$row_cnt = isset($_GET['row']) ? intval($_GET['row']) : 1;

// Fetch products from DB
include "config.php"; // your database connection
$sql = "SELECT id, ProductName,MinPrice, 'RAW' AS ProdCategory FROM tbl_cust_products2 WHERE Status=1 AND ProdType=1 AND CreatedDate>='2025-08-22' UNION ALL SELECT id, ProductName,MinPrice, 'Other' AS ProdCategory FROM tbl_cust_products2 WHERE Status=1 AND ProdType=0 AND ProdType2=3";
$result = mysqli_query($conn, $sql);
?>

<div class="form-row" data-row="<?= $row_cnt ?>" id="row<?php echo $row_cnt;?>">
    <div class="form-group col-md-4">
        <label class="form-label">Raw Product</label>
        <select class="form-control product-select" name="CustProdId[]" style="width: 100%" id="CustProdId<?php echo $row_cnt;?>" onchange="getRawProductDetails(this.value,<?php echo $row_cnt;?>)">
            <option value="">Select a product</option>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <option value="<?= $row['id'] ?>"><?= $row['ProductName']; ?> (<?= $row['ProdCategory']; ?>)</option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group col-md-2">
        <label class="form-label">Making Qty</label>
        <input type="number" name="MakingQty[]" class="form-control" id="MakingQty<?php echo $row_cnt; ?>">
    </div>
    
    <div class="form-group col-md-1"> 
            <label class="form-label">Unit</label>
            <input type="text" name="Unit[]" class="form-control" id="Unit<?php echo $row_cnt; ?>" readonly>
        </div>

    <div class="form-group col-md-2" style="padding-top: 30px;">
     
     <button type="button" class="btn btn-secondary add-more">+</button>
     <button class="btn btn-danger btn_remove" type="button" id="<?php echo $row_cnt;?>"><i class="fa fa-times"></i></button>
    </div>
</div>