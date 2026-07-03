<?php
require_once '../config.php';
require_once 'gst_helper.php';

gstJsonHeaders();

try {
    ensureGstTables($conn);

    $prodId = (int) ($_POST['ProdId'] ?? $_REQUEST['ProdId'] ?? 0);
    $gstRateId = (int) ($_POST['GstRateId'] ?? $_REQUEST['GstRateId'] ?? 0);
    $hsnCode = gstEsc($conn, $_POST['HsnCode'] ?? $_REQUEST['HsnCode'] ?? '');
    $frId = gstEsc($conn, $_POST['user_id'] ?? $_REQUEST['user_id'] ?? $_REQUEST['FrId'] ?? '0');

    if ($prodId <= 0) {
        throw new Exception('ProdId is required');
    }
    if ($gstRateId <= 0) {
        throw new Exception('GstRateId is required');
    }

    $gst = getRecord("SELECT * FROM tbl_gst_master WHERE id = '$gstRateId' AND Status = 1 LIMIT 1");
    if (!$gst) {
        throw new Exception('Selected GST rate not found or inactive');
    }

    $product = getRecord("SELECT id, MinPrice, SubTotal FROM tbl_cust_products_2025 WHERE id = '$prodId' LIMIT 1");
    if (!$product) {
        throw new Exception('Product not found');
    }

    $cgstPer = gstFloat($gst['CgstPer']);
    $sgstPer = gstFloat($gst['SgstPer']);
    $igstPer = gstFloat($gst['IgstPer']);
    $gstPer = gstFloat($gst['GstPercentage']);
    $finalPrice = gstFloat($product['MinPrice']);
    $prodPrice = $gstPer > 0 ? gstFloat($finalPrice / (1 + ($gstPer / 100))) : $finalPrice;
    $gstAmt = gstFloat($finalPrice - $prodPrice);
    $cgstAmt = $igstPer > 0 ? 0 : gstFloat($gstAmt / 2);
    $sgstAmt = $igstPer > 0 ? 0 : gstFloat($gstAmt / 2);
    $igstAmt = $igstPer > 0 ? $gstAmt : 0;

    $hsnSql = $hsnCode !== '' ? ", HsnCode='$hsnCode'" : '';

    $sql = "UPDATE tbl_cust_products_2025 SET
                GstRateId='$gstRateId',
                CgstPer='$cgstPer',
                SgstPer='$sgstPer',
                IgstPer='$igstPer',
                ProdPrice='$prodPrice',
                GstAmt='$gstAmt',
                CgstAmt='$cgstAmt',
                SgstAmt='$sgstAmt',
                IgstAmt='$igstAmt'
                $hsnSql
            WHERE id='$prodId'";

    if ($frId !== '0' && $frId !== '') {
        $sql .= " AND CreatedBy='$frId'";
    }

    if (!$conn->query($sql)) {
        throw new Exception('Failed to assign GST rate to product: ' . $conn->error);
    }

    if ($conn->affected_rows === 0) {
        throw new Exception('Product not updated. Check ProdId and outlet access.');
    }

    gstRespond([
        'status' => 'success',
        'message' => 'GST rate assigned to product successfully',
        'product' => [
            'ProdId' => $prodId,
            'GstRateId' => $gstRateId,
            'GstName' => $gst['GstName'],
            'GstPercentage' => $gstPer,
            'CgstPer' => $cgstPer,
            'SgstPer' => $sgstPer,
            'IgstPer' => $igstPer,
            'ProdPrice' => $prodPrice,
            'GstAmt' => $gstAmt,
            'MinPrice' => $finalPrice,
            'HsnCode' => $hsnCode,
        ],
    ]);
} catch (Exception $e) {
    gstRespond([
        'status' => 'error',
        'message' => $e->getMessage(),
    ], 400);
}

?>
