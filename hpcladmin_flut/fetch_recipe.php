<?php
include_once 'config.php';
$id = $_POST['id'];

$sql12 = "SELECT * FROM tbl_raw_prod_make_qty_2025 WHERE CustProdId='$id'";
$row12 = getList($sql12);

$i=1;
foreach($row12 as $result12){
?>
    <div class="form-row" id="row<?php echo $i;?>">

        <!-- Raw Product -->
        <div class="form-group col-md-4">
            <label class="form-label">Raw Product</label>
            <select class="form-control select2-demo" style="width:100%" 
                name="CustProdId[]" id="CustProdId2<?php echo $i;?>" 
                onchange="getRawProductDetails(this.value,<?php echo $i;?>)">
                <option value="">...</option>
                <?php
                $sql4 = "SELECT * FROM tbl_cust_products2 WHERE Status=1 AND ProdType=1 AND CreatedDate>='2025-08-22' ORDER BY ProductName";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
                ?>
                    <option <?php if ($result12["RawProdId"] == $result['id']) { ?> selected <?php } ?> 
                        value="<?php echo $result['id']; ?>">
                        <?php echo $result['ProductName']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Making Qty -->
        <div class="form-group col-lg-2">
            <label class="form-label">Making Qty</label>
            <input type="number" name="MakingQty[]" class="form-control" 
                id="MakingQty<?php echo $i; ?>" value="<?php echo $result12["MakingQty"]; ?>">
        </div> 

        <!-- Unit -->
        <div class="form-group col-lg-1">
            <label class="form-label">Unit</label>
            <input type="text" class="form-control unit" name="Unit[]" 
                id="Unit<?php echo $i; ?>" value="<?php echo $result12['RawUnit'];?>" readonly>
        </div> 

        <input type="hidden" name="MakingQty2[]" value="">
        <input type="hidden" name="MakingQtyUnit2[]" value="">
        <input type="hidden" name="srno[]" value="<?php echo $i; ?>">

        <!-- Remove button -->
        <div class="form-group col-md-1" style="padding-top:20px;">
            <button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
<?php 
$i++;
} 
?>
