<?php

/**
 * MRP product Excel import — mirrors ajax_customer_products.php (Add action).
 */

function mrp_import_sanitize($conn, $data)
{
    if ($data === null || is_array($data)) {
        $data = '';
    }
    return mysqli_real_escape_string($conn, trim((string) $data));
}

function mrp_import_random_code($n)
{
    $domain = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $len = strlen($domain);
    $generated = '';
    for ($i = 0; $i < $n; $i++) {
        $generated .= $domain[rand(0, $len - 1)];
    }
    return $generated;
}

function mrp_import_normalize_header($header)
{
    $h = strtolower(trim((string) $header));
    $h = preg_replace('/[^a-z0-9]+/', '', $h);
    return $h;
}

function mrp_import_header_map()
{
    return [
        'srno' => 'row_sr',
        'productname' => 'ProductName',
        'barcodeno' => 'BarcodeNo',
        'brandid' => 'BrandId',
        'brandvendorid' => 'BrandId',
        'vendorid' => 'BrandId',
        'categoryid' => 'CatId',
        'catid' => 'CatId',
        'subcategoryid' => 'SubCatId',
        'subcatid' => 'SubCatId',
        'purchaseprice' => 'PurchasePrice',
        'mrpprice' => 'SubTotal',
        'subtotal' => 'SubTotal',
        'mrp' => 'SubTotal',
        'discount' => 'DiscPer',
        'discountper' => 'DiscPer',
        'discper' => 'DiscPer',
        'discountpercentage' => 'DiscPer',
        'cgstper' => 'CgstPer',
        'cgst' => 'CgstPer',
        'cgstpercentage' => 'CgstPer',
        'sgstper' => 'SgstPer',
        'sgst' => 'SgstPer',
        'sgstpercentage' => 'SgstPer',
        'igstper' => 'IgstPer',
        'igst' => 'IgstPer',
        'igstpercentage' => 'IgstPer',
        'pricewogst' => 'ProdPrice',
        'withoutgstprice' => 'ProdPrice',
        'prodprice' => 'ProdPrice',
        'gstamt' => 'GstAmt',
        'finalprice' => 'MinPrice',
        'minprice' => 'MinPrice',
        'price' => 'MinPrice',
        'cgstamt' => 'CgstAmt',
        'sgstamt' => 'SgstAmt',
        'igstamt' => 'IgstAmt',
        'unit' => 'Unit',
        'minstockqty' => 'MinQty',
        'minqty' => 'MinQty',
        'status' => 'Status',
        'transfer' => 'Transfer',
        'producttype' => 'ProdType2',
        'prodtype2' => 'ProdType2',
        'sortsrno' => 'SrNo',
        'sortorder' => 'SrNo',
    ];
}

function mrp_import_row_from_headers(array $headers, array $row)
{
    $alias = mrp_import_header_map();
    $out = [];
    foreach ($headers as $idx => $header) {
        $key = mrp_import_normalize_header($header);
        if ($key === '' || !isset($alias[$key])) {
            continue;
        }
        $field = $alias[$key];
        $out[$field] = trim((string) ($row[$idx] ?? ''));
    }
    return $out;
}

function mrp_import_is_header_row(array $row)
{
    foreach ($row as $cell) {
        $n = mrp_import_normalize_header($cell);
        if (in_array($n, ['productname', 'srno', 'barcodeno', 'categoryid', 'catid'], true)) {
            return true;
        }
    }
    return false;
}

/**
 * Calculate price fields (same logic as add-customer-product.php getProdPrice).
 */
function mrp_import_calc_prices(array $data)
{
    $cgstPer = (float) ($data['CgstPer'] ?? 0);
    $sgstPer = (float) ($data['SgstPer'] ?? 0);
    $igstPer = (float) ($data['IgstPer'] ?? 0);
    $discPer = (float) ($data['DiscPer'] ?? 0);
    $subTotal = (float) ($data['SubTotal'] ?? 0);

    $discAmt = $subTotal * ($discPer / 100);
    $finalPrice = $subTotal - $discAmt;
    $totalGstPer = $igstPer > 0 ? $igstPer : ($cgstPer + $sgstPer);
    $prodPrice = $totalGstPer > 0 ? $finalPrice / (1 + ($totalGstPer / 100)) : $finalPrice;
    $gstAmt = $finalPrice - $prodPrice;

    if ($igstPer > 0) {
        $cgstAmt = 0;
        $sgstAmt = 0;
        $igstAmt = $gstAmt;
    } else {
        $cgstAmt = $gstAmt / 2;
        $sgstAmt = $gstAmt / 2;
        $igstAmt = 0;
    }

    $data['Discount'] = number_format($discAmt, 2, '.', '');
    $data['MinPrice'] = number_format($finalPrice, 2, '.', '');
    $data['ProdPrice'] = number_format($prodPrice, 2, '.', '');
    $data['GstAmt'] = number_format($gstAmt, 2, '.', '');
    $data['CgstAmt'] = number_format($cgstAmt, 2, '.', '');
    $data['SgstAmt'] = number_format($sgstAmt, 2, '.', '');
    $data['IgstAmt'] = number_format($igstAmt, 2, '.', '');

    return $data;
}

function mrp_import_prepare_row(array $raw, $fallbackSrNo = 0, $defaultBrandId = '')
{
    $defaults = [
        'ProductName' => '',
        'BarcodeNo' => '',
        'BrandId' => '',
        'CatId' => '',
        'SubCatId' => '',
        'PurchasePrice' => '0',
        'SubTotal' => '0',
        'DiscPer' => '0',
        'Discount' => '0',
        'CgstPer' => '2.5',
        'SgstPer' => '2.5',
        'IgstPer' => '0',
        'ProdPrice' => '',
        'GstAmt' => '',
        'MinPrice' => '',
        'CgstAmt' => '',
        'SgstAmt' => '',
        'IgstAmt' => '',
        'Unit' => '',
        'MinQty' => '0',
        'Status' => '1',
        'ProdType2' => '1',
        'Transfer' => '1',
        'SrNo' => (string) $fallbackSrNo,
        'StockQty' => '0',
        'TempPrdId' => '0',
    ];

    $data = array_merge($defaults, $raw);

    if (($data['BrandId'] === '' || (int) $data['BrandId'] <= 0) && $defaultBrandId !== '') {
        $data['BrandId'] = (string) $defaultBrandId;
    }

    if ($data['ProductName'] === '' || strtolower($data['ProductName']) === 'productname') {
        return null;
    }

    if ($data['SubTotal'] === '' || (float) $data['SubTotal'] <= 0) {
        if ($data['MinPrice'] !== '' && (float) $data['MinPrice'] > 0) {
            $data['SubTotal'] = $data['MinPrice'];
        }
    }

    $needsCalc = ($data['ProdPrice'] === '' || $data['GstAmt'] === '' || $data['MinPrice'] === '');
    if ($needsCalc && (float) $data['SubTotal'] > 0) {
        $data = mrp_import_calc_prices($data);
    } else {
        foreach (['Discount', 'MinPrice', 'ProdPrice', 'GstAmt', 'CgstAmt', 'SgstAmt', 'IgstAmt'] as $f) {
            if ($data[$f] === '') {
                $data[$f] = '0';
            }
        }
    }

    return $data;
}

/**
 * Legacy fixed-column layout (old sample excel without Brand Id).
 */
function mrp_import_row_legacy(array $row, $fallbackSrNo = 0, $defaultBrandId = '')
{
    return mrp_import_prepare_row([
        'ProductName' => trim((string) ($row[1] ?? '')),
        'BarcodeNo' => trim((string) ($row[2] ?? '')),
        'CatId' => trim((string) ($row[3] ?? '')),
        'SubCatId' => trim((string) ($row[4] ?? '')),
        'SgstPer' => trim((string) ($row[5] ?? '2.5')),
        'CgstPer' => trim((string) ($row[6] ?? '2.5')),
        'IgstPer' => trim((string) ($row[7] ?? '0')),
        'CgstAmt' => trim((string) ($row[8] ?? '')),
        'SgstAmt' => trim((string) ($row[9] ?? '')),
        'IgstAmt' => trim((string) ($row[10] ?? '')),
        'PurchasePrice' => trim((string) ($row[11] ?? '0')),
        'ProdPrice' => trim((string) ($row[12] ?? '')),
        'GstAmt' => trim((string) ($row[13] ?? '')),
        'MinPrice' => trim((string) ($row[14] ?? '')),
        'SrNo' => trim((string) ($row[0] ?? $fallbackSrNo)),
    ], $fallbackSrNo, $defaultBrandId);
}

/**
 * Save one MRP product — tbl_cust_products2, tbl_cust_products_2025, tbl_vendor_products.
 *
 * @throws Exception
 */
function mrp_import_save_product($conn, array $data, $admin_id)
{
    $fields = [
        'TempPrdId', 'ProductName', 'CatId', 'SubCatId', 'BrandId', 'MinPrice', 'SubTotal', 'DiscPer', 'Discount',
        'CgstPer', 'SgstPer', 'IgstPer', 'GstAmt', 'ProdPrice', 'CgstAmt', 'SgstAmt', 'IgstAmt', 'BarcodeNo',
        'StockQty', 'MinQty', 'ProdType2', 'Transfer', 'PurchasePrice', 'Unit', 'Status', 'SrNo',
    ];

    foreach ($fields as $field) {
        $$field = mrp_import_sanitize($conn, $data[$field] ?? '');
    }

    $StockQty = ($StockQty === '') ? 0 : (int) $StockQty;
    $TempPrdId = ($TempPrdId === '') ? 0 : (int) $TempPrdId;
    $SubTotal = ($SubTotal === '') ? 0 : $SubTotal;
    $Discount = ($Discount === '') ? 0 : $Discount;
    $DiscPer = ($DiscPer === '') ? 0 : $DiscPer;
    $SrNo = ($SrNo === '') ? 0 : $SrNo;
    $Division = 0;
    $Segment = 0;
    $Family = 0;
    $ClassId = 0;
    $McDesc = 0;
    $BrandDesc = 0;

    if ($ProductName === '') {
        throw new Exception('Product Name is required');
    }
    if ($CatId === '' || (int) $CatId <= 0) {
        throw new Exception('Category Id is required for ' . $ProductName);
    }
    if ($BrandId === '' || (int) $BrandId <= 0) {
        throw new Exception('Brand Id is required for ' . $ProductName);
    }

    $CreatedDate = date('Y-m-d');
    $modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
    $Photo = '';
    $Code = mrp_import_random_code(10);

    $sql = "INSERT INTO tbl_cust_products2 
            SET ProdId='0', Division='$Division', Segment='$Segment', Family='$Family', ClassId='$ClassId',
                McDesc='$McDesc', BrandDesc='$BrandDesc', SubTotal='$SubTotal', DiscPer='$DiscPer',
                Discount='$Discount', Unit='$Unit', PurchasePrice='$PurchasePrice', Transfer='$Transfer',
                ProdType2='$ProdType2', BrandId='$BrandId', SubCatId='$SubCatId', ProductName='$ProductName',
                CatId='$CatId', MinPrice='$MinPrice', Status='$Status', SrNo='$SrNo', CreatedDate='$CreatedDate',
                CgstPer='$CgstPer', SgstPer='$SgstPer', IgstPer='$IgstPer', GstAmt='$GstAmt', ProdPrice='$ProdPrice',
                CgstAmt='$CgstAmt', SgstAmt='$SgstAmt', IgstAmt='$IgstAmt', BarcodeNo='$BarcodeNo',
                StockQty='$StockQty', TempPrdId='$TempPrdId', MinQty='$MinQty', Photo='$Photo',
                ProdType='0', CreatedBy='$admin_id', ModifiedBy='0', push_flag='0', delete_flag='0',
                Assets='0', checkstatus='0', tempstatus='0', CrossSell='0', MatchMrpProdId='0',
                OldNew='0', allotstatus='0'";

    if (!$conn->query($sql)) {
        throw new Exception('Insert tbl_cust_products2 failed: ' . $conn->error);
    }

    $ProdId = $conn->insert_id;
    $Code2 = $Code . $ProdId;

    if (!$conn->query("UPDATE tbl_cust_products2 SET code='$Code2' WHERE id='$ProdId'")) {
        throw new Exception('Code update failed: ' . $conn->error);
    }

    $sql = "INSERT INTO tbl_cust_products_2025 SET ProdId='$ProdId',
                Division='$Division', Segment='$Segment', Family='$Family', ClassId='$ClassId',
                McDesc='$McDesc', BrandDesc='$BrandDesc', SubTotal='$SubTotal', DiscPer='$DiscPer',
                Discount='$Discount', Unit='$Unit', PurchasePrice='$PurchasePrice', Transfer='$Transfer',
                ProdType2='$ProdType2', BrandId='$BrandId', SubCatId='$SubCatId', ProductName='$ProductName',
                CatId='$CatId', MinPrice='$MinPrice', Status='$Status', SrNo='$SrNo', CreatedDate='$CreatedDate',
                CgstPer='$CgstPer', SgstPer='$SgstPer', IgstPer='$IgstPer', GstAmt='$GstAmt', ProdPrice='$ProdPrice',
                CgstAmt='$CgstAmt', SgstAmt='$SgstAmt', IgstAmt='$IgstAmt', BarcodeNo='$BarcodeNo',
                StockQty='$StockQty', TempPrdId='$TempPrdId', MinQty='$MinQty', Photo='$Photo',
                ProdType='0', CreatedBy='0', ModifiedBy='0', code='$Code2', checkstatus='1',
                push_flag='1', delete_flag='0', Assets='0', CrossSell='0', MatchMrpProdId='0',
                OldNew='0', allotstatus='0', modified_time='$modified_time'";
    if (!$conn->query($sql)) {
        throw new Exception('Insert tbl_cust_products_2025 failed: ' . $conn->error);
    }

    if (!empty($BrandId)) {
        $sql = "INSERT INTO tbl_vendor_products 
                SET ProdId='$ProdId', Division='$Division', Segment='$Segment', Family='$Family', ClassId='$ClassId',
                    McDesc='$McDesc', BrandDesc='$BrandDesc', SubTotal='$SubTotal', DiscPer='$DiscPer', Discount='$Discount',
                    Unit='$Unit', PurchasePrice='$PurchasePrice', Transfer='$Transfer', ProdType2='$ProdType2',
                    BrandId='$BrandId', SubCatId='$SubCatId', ProductName='$ProductName', CatId='$CatId',
                    MinPrice='$MinPrice', Status='$Status', SrNo='$SrNo', CreatedDate='$CreatedDate',
                    CgstPer='$CgstPer', SgstPer='$SgstPer', IgstPer='$IgstPer', GstAmt='$GstAmt', ProdPrice='$ProdPrice',
                    CgstAmt='$CgstAmt', SgstAmt='$SgstAmt', IgstAmt='$IgstAmt', BarcodeNo='$BarcodeNo',
                    StockQty='$StockQty', TempPrdId='$TempPrdId', MinQty='$MinQty', Photo='$Photo',
                    CreatedBy='$BrandId', ModifiedBy='0', code='$Code2', ProdType='0', push_flag='0',
                    delete_flag='0', Assets='0', checkstatus='1', tempstatus='1', CrossSell='0',
                    MatchMrpProdId='0', OldNew='0', allotstatus='0'";
        if (!$conn->query($sql)) {
            throw new Exception('Insert tbl_vendor_products failed: ' . $conn->error);
        }
    }

    return $ProdId;
}

/**
 * Process uploaded spreadsheet rows.
 *
 * @return array{inserted:int,skipped:int,errors:array}
 */
function mrp_import_process_file($conn, $filePath, $admin_id, $defaultBrandId = '')
{
    require_once dirname(__DIR__) . '/excel_vendor/php-excel-reader/excel_reader2.php';
    require_once dirname(__DIR__) . '/excel_vendor/SpreadsheetReader.php';

    $Reader = new SpreadsheetReader($filePath);
    $inserted = 0;
    $skipped = 0;
    $errors = [];
    $headers = null;
    $useLegacy = null;
    $lineNo = 0;
    $srCounter = 0;

    foreach ($Reader as $Row) {
        $lineNo++;
        if (!is_array($Row)) {
            continue;
        }

        if ($headers === null && mrp_import_is_header_row($Row)) {
            $headers = $Row;
            $useLegacy = (mrp_import_normalize_header($headers[3] ?? '') === 'categoryid'
                && mrp_import_normalize_header($headers[2] ?? '') === 'barcodeno'
                && mrp_import_normalize_header($headers[1] ?? '') === 'productname'
                && mrp_import_normalize_header($headers[3] ?? '') !== 'brandid');
            continue;
        }

        if ($headers !== null) {
            $raw = mrp_import_row_from_headers($headers, $Row);
            if (empty($raw['ProductName']) && isset($Row[1])) {
                $raw['ProductName'] = trim((string) $Row[1]);
            }
            $srCounter++;
            $prepared = mrp_import_prepare_row($raw, $srCounter, $defaultBrandId);
        } else {
            $srCounter++;
            $prepared = mrp_import_row_legacy($Row, $srCounter, $defaultBrandId);
        }

        if ($prepared === null) {
            continue;
        }

        $conn->begin_transaction();
        try {
            mrp_import_save_product($conn, $prepared, $admin_id);
            $conn->commit();
            $inserted++;
        } catch (Throwable $e) {
            $conn->rollback();
            $skipped++;
            $errors[] = 'Row ' . $lineNo . ' (' . ($prepared['ProductName'] ?? '') . '): ' . $e->getMessage();
        }
    }

    return [
        'inserted' => $inserted,
        'skipped' => $skipped,
        'errors' => $errors,
    ];
}
