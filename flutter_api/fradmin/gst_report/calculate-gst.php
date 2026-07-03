<?php
require_once '../config.php';
require_once 'gst_helper.php';

gstJsonHeaders();

try {
    ensureGstTables($conn);

    $rate = gstFloat($_REQUEST['rate'] ?? $_REQUEST['Rate'] ?? 0);
    $qty = gstFloat($_REQUEST['qty'] ?? $_REQUEST['Qty'] ?? 1);
    $gstRateId = (int) ($_REQUEST['GstRateId'] ?? 0);
    $sameState = (int) ($_REQUEST['same_state'] ?? $_REQUEST['SameState'] ?? 1);
    $frId = gstEsc($conn, $_REQUEST['user_id'] ?? $_REQUEST['FrId'] ?? '0');
    $prodId = (int) ($_REQUEST['ProdId'] ?? 0);

    $cgstPer = 0;
    $sgstPer = 0;
    $igstPer = 0;
    $gstPercentage = 0;
    $hsnCode = '';

    if ($prodId > 0) {
        $product = getRecord("SELECT p.*, h.HsnCode AS MasterHsnCode
                              FROM tbl_cust_products_2025 p
                              LEFT JOIN tbl_hsn_master h ON h.HsnCode = p.HsnCode
                              WHERE p.id = '$prodId' LIMIT 1");
        if ($product) {
            $hsnCode = $product['HsnCode'] ?: $product['MasterHsnCode'];
            if ($gstRateId <= 0 && (int) $product['GstRateId'] > 0) {
                $gstRateId = (int) $product['GstRateId'];
            }
            if ($gstRateId <= 0) {
                $cgstPer = gstFloat($product['CgstPer']);
                $sgstPer = gstFloat($product['SgstPer']);
                $igstPer = gstFloat($product['IgstPer']);
                $gstPercentage = $cgstPer + $sgstPer + $igstPer;
            }
        }
    }

    if ($gstRateId > 0) {
        $gst = getRecord("SELECT * FROM tbl_gst_master WHERE id = '$gstRateId' AND Status = 1 LIMIT 1");
        if (!$gst) {
            throw new Exception('GST rate not found');
        }
        $gstPercentage = gstFloat($gst['GstPercentage']);
        if ($sameState) {
            $cgstPer = gstFloat($gst['CgstPer']);
            $sgstPer = gstFloat($gst['SgstPer']);
            $igstPer = 0;
        } else {
            $cgstPer = 0;
            $sgstPer = 0;
            $igstPer = gstFloat($gst['IgstPer'] > 0 ? $gst['IgstPer'] : $gstPercentage);
        }
    }

    if ($gstPercentage <= 0 && $rate <= 0) {
        throw new Exception('Rate or GstRateId/ProdId is required');
    }

    if ($rate <= 0) {
        $rate = 0;
    }

    $lineTotal = gstFloat($rate * $qty);
    $taxableAmount = $gstPercentage > 0
        ? gstFloat($lineTotal / (1 + ($gstPercentage / 100)))
        : $lineTotal;
    $totalGst = gstFloat($lineTotal - $taxableAmount);

    if ($sameState) {
        $cgstAmount = gstFloat($totalGst / 2);
        $sgstAmount = gstFloat($totalGst / 2);
        $igstAmount = 0;
    } else {
        $cgstAmount = 0;
        $sgstAmount = 0;
        $igstAmount = $totalGst;
    }

    gstRespond([
        'status' => 'success',
        'calculation' => [
            'ProdId' => $prodId,
            'GstRateId' => $gstRateId,
            'HsnCode' => $hsnCode,
            'Qty' => $qty,
            'Rate' => gstFloat($rate),
            'TaxableAmount' => $taxableAmount,
            'GstPercentage' => $gstPercentage,
            'CgstPer' => $cgstPer,
            'SgstPer' => $sgstPer,
            'IgstPer' => $igstPer,
            'CgstAmount' => $cgstAmount,
            'SgstAmount' => $sgstAmount,
            'IgstAmount' => $igstAmount,
            'TotalGst' => $totalGst,
            'TotalAmount' => $lineTotal,
            'InvoiceType' => $sameState ? 'INTRA_STATE' : 'INTER_STATE',
        ],
    ]);
} catch (Exception $e) {
    gstRespond([
        'status' => 'error',
        'message' => $e->getMessage(),
    ], 400);
}

?>
