<?php
header("Content-Type: application/json");
include 'db.php'; // Secure DB connection

$data = json_decode(file_get_contents("php://input"), true);
/*$data = [
    "CustId" => 123,
    "CellNo" => "9876543210",
    "CustName" => "John Doe",
    "InvoiceNo" => "20250628153030123",
    "InvoiceDate" => "2025-06-28 15:30:30",
    "CreatedBy" => 1,
    "CreatedDate" => "2025-06-28 15:30:30",
    "PkgDiscount" => 10.0,
    "TotalAmount" => "500.00",
    "PayType" => "Cash",
    "user_id" => "253",
    "cart" => [
        [
            "id" => 1,
            "ServerInvId" => 0,
            "InvId" => "7",
            "CatId" => 5,
            "ProdId" => 101,
            "MainProdId" => "Tea",
            "ActPrice" => 20,
            "Qty" => 2,
            "Price" => 40,
            "CgstPer" => "2.5",
            "SgstPer" => "2.5",
            "IgstPer" => "",
            "GstAmt" => 2,
            "Total" => 42,
            "CreatedDate" => "2025-06-28 15:30:30",
            "CustProd" => 0,
            "CgstAmt" => 1,
            "SgstAmt" => 1,
            "IgstAmt" => 0,
            "PkgId" => 0,
            "PkgAmt" => 0,
            "PkgDiscount" => 0,
            "PkgDate" => "",
            "PkgValidity" => "",
            "PrimeDiscount" => 0,
            "FrId" => 1,
            "upstatus" => 0,
            "modified_time" => "2025-06-28 15:30:30.000",
            "user_id" => "253"
        ]
    ]
];*/


$uid = intval($data['user_id']);
$CustName = addslashes(trim($data['CustName']));
$CellNo = addslashes(trim($data['CellNo']));
$EmailId = addslashes(trim($data['EmailId']));
$id = addslashes(trim($data['id']));
$InvoiceNo = addslashes(trim($data['InvoiceNo']));
$InvoiceDate = addslashes(trim($data['InvoiceDate']));
$CreatedBy = addslashes(trim($data['user_id'])); 
$CreatedDate = addslashes(trim($data['CreatedDate'])); 
$SubTotal = addslashes(trim($data['SubTotal'])); 
$GstAmt = addslashes(trim($data['GstAmt'])); 
$TotalAmount = addslashes(trim($data['TotalAmount'])); 
$DiscPer = floatval($data['DiscPer']);
$Discount = floatval($data['Discount']);
$NetAmount = floatval($data['NetAmount']);
$ac_per = floatval($data['ac_per']);
$ac_charges = floatval($data['ac_charges']);
$user_id = intval($data['user_id']);
$FrId = intval($data['FrId']);
$PayType = addslashes(trim($data['PayType']));
$PayType2 = addslashes(trim($data['PayType2']));
$Amount1 = addslashes(trim($data['Amount1']));
$Amount2 = addslashes(trim($data['Amount2']));
$CreatedTime= floatval($data['CreatedTime']);

$RedeemAmount = addslashes(trim($data['RedeemAmount']));
$RedeemStatus = addslashes(trim($data['RedeemStatus']));
$order_instructions = addslashes(trim($data['order_instructions']));
$table_id = addslashes(trim($data['table_id']));
$table_number = addslashes(trim($data['table_number']));
$table_name = addslashes(trim($data['table_name']));
$table_alias = addslashes(trim($data['table_alias']));
$table_label = addslashes(trim($data['table_label']));
$CouponAmt = addslashes(trim($data['CouponAmt']));
$CouponName = addslashes(trim($data['CouponName']));
$SrNo = addslashes(trim($data['SrNo']));

$exchange_invoice_no = addslashes(trim($data['exchange_invoice_no']));
$exchange_credit = addslashes(trim($data['exchange_credit']));
$is_exchange_order = addslashes(trim($data['is_exchange_order']));
$exchange_details = addslashes(trim($data['exchange_details']));
 $modified_time = gmdate('Y-m-d H:i:s.').gettimeofday()['usec'];
$created_at = date('Y-m-d H:i:s');
$cart = $data['cart'];
$InvId = addslashes(trim($data['id']));

// Fetch Roll & BillSoftFrId
/*$sqlFr = "SELECT Roll, BillSoftFrId FROM tbl_users_bill WHERE id='$uid'";
$resultFr = $conn->query($sqlFr);
$BillSoftFrId = $uid; // Default fallback
if ($resultFr && $resultFr->num_rows > 0) {
    $rowFr = $resultFr->fetch_assoc();
    $BillSoftFrId = $rowFr['Roll'] == 5 ? $uid : $rowFr['BillSoftFrId'];
}*/

// Insert customer if not exists
$CustId = 0;
if (!empty($CellNo)) {
    $sqlCust = "SELECT id FROM tbl_users WHERE Phone='$CellNo' AND Roll=55";
    $resultCust = $conn->query($sqlCust);
    if ($resultCust && $resultCust->num_rows > 0) {
        $rowCust = $resultCust->fetch_assoc();
        $CustId = $rowCust['id'];
        $sql = "UPDATE tbl_users SET Fname='$CustName',EmailId='$EmailId' WHERE id='$CustId' AND Roll=55";
        $conn->query($sql);
    } else {
        $sqlInsertCust = "INSERT INTO tbl_users (Fname, Phone, EmailId,CreatedBy, CreatedDate, Roll, Status)
                          VALUES ('$CustName', '$CellNo','$EmailId', '$uid', NOW(), 55, 1)";
        $conn->query($sqlInsertCust);
        $CustId = $conn->insert_id;
        $CustomerId = "C" . $CustId;
        $conn->query("UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$CustId'");
    }
}

$sql = "SELECT * FROM tbl_customer_invoice_2025 
        WHERE FrId='$FrId' AND InvoiceNo='$InvoiceNo' AND InvoiceDate='$InvoiceDate'";
$resultCust = $conn->query($sql);
if ($resultCust && $resultCust->num_rows > 0) {}
    else{
// Insert Invoice (using provided InvoiceNo)
 $sqlInvoice = "INSERT INTO tbl_customer_invoice_2025 SET SrNo='$SrNo',modified_time='$modified_time',exchange_invoice_no='$exchange_invoice_no',exchange_credit='$exchange_credit',
 is_exchange_order='$is_exchange_order',exchange_details='$exchange_details',CouponAmt='$CouponAmt',CouponName='$CouponName',EmailId='$EmailId',RedeemAmount='$RedeemAmount',
 RedeemStatus='$RedeemStatus',order_instructions='$order_instructions',table_id='$table_id',
 table_number='$table_number',table_name='$table_name',table_alias='$table_alias',table_label='$table_label',flag=0,id='$id', CustId='$CustId', CustName='$CustName', CellNo='$CellNo',
    InvoiceDate='$InvoiceDate', InvoiceNo='$InvoiceNo', CreatedBy='$CreatedBy', CreatedDate=CURDATE(), CreatedTime=CURTIME(),
    TotalAmount='$TotalAmount', NetAmount='$NetAmount', PayType='$PayType',PayType2='$PayType2',Amount1='$Amount1',Amount2='$Amount2',
    SubTotal='$SubTotal', GstAmt='$GstAmt',DiscPer='$DiscPer',Discount='$Discount',ac_per='$ac_per',ac_charges='$ac_charges',FrId='$FrId', push_flag=1, Roll=2,user_id='$user_id'";
$conn->query($sqlInvoice);
$ServerInvId = mysqli_insert_id($conn);

if ($PayType == 'Borrowing') {
        $sql4 = "INSERT INTO tbl_cust_general_ledger SET 
            SrNo='0', Code='', FrId='$FrId', UserId='$CustId', AccountName='$CustName',
            InvoiceNo='$InvoiceNo', Amount='$NetAmount', CrDr='dr', Roll=55, Type='CINV',
            CreatedDate='$CreatedDate', PaymentDate='$InvoiceDate', PayMode='$PayType',
            Narration='Total Invoice Amount', InvId='$id', UniqInvId='$ServerInvId',
            SellType='CustInv', CreatedBy='$user_id', CustPhone='$CellNo'";
        $conn->query($sql4);
    }
    
if($CellNo!=''){
    $rupees = $NetAmount*(10/100);
    $points = $rupees * 10; // 1 Rs = 10 Points
    $sql = "INSERT INTO tbl_customer_points SET invid='$ServerInvId',custid='$CustId',phone='$CellNo',total_amount='$NetAmount',rupees='$rupees',points='$points',invoicedate='$InvoiceDate',frid='$FrId',status='cr'";
    $conn->query($sql);
}
if($RedeemAmount>0){
    $points = $RedeemAmount*10;
    $sql = "INSERT INTO tbl_customer_points SET invid='$ServerInvId',custid='$CustId',phone='$CellNo',total_amount='$NetAmount',rupees='$RedeemAmount',points='$points',invoicedate='$InvoiceDate',frid='$FrId',status='dr'";
    $conn->query($sql);
}
}
// Insert Items
foreach ($cart as $item) {
    $itemid = addslashes(trim($item['id']));
    $CatId = intval($item['CatId']);
    $ProdId = intval($item['ProdId']);
    $MainProdId = addslashes($item['MainProdId'] ?? '');
    $ActPrice = floatval($item['ActPrice']);
    $Qty = floatval($item['Qty']);
    $Price = floatval($item['Price']);
    $CgstPer = floatval($item['CgstPer']);
    $SgstPer = floatval($item['SgstPer']);
    $IgstPer = floatval($item['IgstPer']);
    $Total = floatval($item['Total']);
    $CgstAmt = floatval($item['CgstAmt']);
    $SgstAmt = floatval($item['SgstAmt']);
    $IgstAmt = floatval($item['IgstAmt']);
    $GstAmt = floatval($item['GstAmt']);
    
   

    // $cgst_amt = ($cgst_per > 0) ? round(($price * $cgst_per) / 100, 2) : 0;
    // $sgst_amt = ($sgst_per > 0) ? round(($price * $sgst_per) / 100, 2) : 0;
    // $igst_amt = ($igst_per > 0) ? round(($price * $igst_per) / 100, 2) : 0;
    // $gst_amt  = $cgst_amt + $sgst_amt + $igst_amt;

    
    // Skip if already exists, else insert
    $sqlCheck = "SELECT id FROM tbl_customer_invoice_details_2025 WHERE id='$itemid'";
    $resultCheck = $conn->query($sqlCheck);
    
    if ($resultCheck && $resultCheck->num_rows > 0) {
        // ✅ Update if exists
        $sqlUpdate = "UPDATE tbl_customer_invoice_details_2025 
                      SET push_flag=1, modified_time='$modified_time' 
                      WHERE id='$itemid'";
        $conn->query($sqlUpdate);
    } else {
        // ✅ Get MainProdId
        $sqlProd = "SELECT ProdId,CatId FROM tbl_cust_products_2025 
                    WHERE id='$ProdId' AND CreatedBy='$FrId'";
        $resultProd = $conn->query($sqlProd);
        $MainProdId = 0;
        if ($resultProd && $resultProd->num_rows > 0) {
            $rowProd = $resultProd->fetch_assoc();
            $MainProdId = $rowProd['ProdId'];
            $CatId = $rowProd['CatId'];
        }
    
        // ✅ Insert into invoice details
        $sql = "INSERT INTO tbl_customer_invoice_details_2025 SET 
            id='$itemid', InvId='$InvId', ServerInvId='$ServerInvId', FrId='$FrId', 
            MainProdId='$MainProdId', ProdId='$ProdId', Qty='$Qty', Price='$Price', 
            CreatedDate='$CreatedDate', CgstPer='$CgstPer', SgstPer='$SgstPer', IgstPer='$IgstPer', 
            GstAmt='$GstAmt', Total='$Total', CustProd='$CustProd', CgstAmt='$CgstAmt', 
            SgstAmt='$SgstAmt', IgstAmt='$IgstAmt', ActPrice='$ActPrice', CatId='$CatId', 
            push_flag=1, modified_time='$modified_time'";
        $conn->query($sql);
    
        // ✅ Stock record
        $Narration = "Stock Used Against Invoice No : $InvoiceNo";
        $stockSql = "INSERT INTO tbl_cust_prod_stock_2025 SET 
            InvoiceId='$InvId', SellPrice='$Price', FrId='$FrId', MainProdId='$MainProdId', 
            ProdId='$ProdId', Qty='$Qty', CreatedBy='$uid', StockDate='$CreatedDate', 
            Narration='$Narration', Status='Dr', UserId='$FrId', 
            CreatedDate='$CreatedDate', InvId='$ServerInvId'";
        $conn->query($stockSql);
        $StockId = mysqli_insert_id($conn);
    
        // ✅ Backup
        $backupSql = str_replace("tbl_cust_prod_stock_2025", "tbl_cust_prod_stock_2025_backup", $stockSql) . ", orgstockid='$StockId'";
        $conn->query($backupSql);
    
        
        // 🔹 Fetch raw material composition
$sqlRaw = "SELECT * FROM tbl_raw_prod_make_qty_2025 WHERE CustProdId='$MainProdId'";
$resultRaw = $conn->query($sqlRaw);

if ($resultRaw && $resultRaw->num_rows > 0) {
    while ($raw = $resultRaw->fetch_assoc()) {

        $MainRawProdId = $raw['RawProdId'];

        // 🔹 Get customer's corresponding raw product
        $sqlCust = "SELECT id FROM tbl_cust_products_2025 WHERE ProdId='$MainRawProdId' AND CreatedBy='$FrId'";
        $resultCust = $conn->query($sqlCust);
        if ($resultCust && $resultCust->num_rows > 0) {
            $rowCust = $resultCust->fetch_assoc();
            $RawProdId = $rowCust['id'];

            // 🔹 Base calculation
            $BaseQty = $raw['MakingQty'] * $Qty;  
            $Unit = trim($raw['RawUnit']);

            // 🔹 Default converted values
            $ConvertedQty = $BaseQty;
            $ConvertedUnit = $Unit;

            // 🔹 Get conversion target unit (like Kg, Ltr)
            $sqlUnit = "SELECT Name2 FROM tbl_units WHERE Name='$Unit' LIMIT 1";
            $resUnit = $conn->query($sqlUnit);

            if ($resUnit && $resUnit->num_rows > 0) {
                $rowUnit = $resUnit->fetch_assoc();
                $ConvertedUnit = $rowUnit['Name2'];
            }

            // 🔹 Apply manual conversions
            $UnitLower = strtolower($Unit);
            $ConvertedUnitLower = strtolower($ConvertedUnit);

            if ($UnitLower == 'gm' && $ConvertedUnitLower == 'kg') {
                $ConvertedQty = $BaseQty / 1000;
            } elseif ($UnitLower == 'ml' && $ConvertedUnitLower == 'ltr') {
                $ConvertedQty = $BaseQty / 1000;
            } else {
                $ConvertedQty = $BaseQty; // same unit (no change)
            }

            // ✅ Save stock record
            $rawStockSql = "INSERT INTO tbl_cust_prod_stock_2025 SET 
                InvoiceId='$InvId',
                SellPrice='$Price',
                UserId='$FrId',
                FrId='$FrId',
                MainProdId='$MainRawProdId',
                ProdId='$RawProdId',
                Qty='$BaseQty', 
                Unit='$Unit',
                Qty2='$ConvertedQty', 
                Unit2='$ConvertedUnit',
                CreatedBy='$uid',
                StockDate='$CreatedDate',
                Narration='$Narration',
                Status='Dr',
                CreatedDate='$CreatedDate',
                InvId='$ServerInvId',
                ProdType=1";

            $conn->query($rawStockSql);
            $RawStockId = mysqli_insert_id($conn);

            // ✅ Backup entry
            $rawBackupSql = str_replace("tbl_cust_prod_stock_2025", "tbl_cust_prod_stock_2025_backup", $rawStockSql) . ", orgstockid='$RawStockId'";
            $conn->query($rawBackupSql);
            
             
        }
    }
}


        
    }


}

$Phone = '91'.$CellNo;
if($CustName==''){
    $Name = "Dear";
}
else{
    $Name = $CustName;
}
$smstxt = "POS Receipt: https://kwickbill.com/in/invoice/index.php?Unqid=".$ServerInvId." Thank you, ".$Name.", for purchasing with MAHACHAI. (www.mahachai.in) Total Amount: ".$NetAmount." Your Feedback Matters: https://kwickbill.com/feedback/index.php?InvNo=".$InvoiceNo."&user_id=".$FrId;

        $dltentityid = "1501701120000037351";
        $dlttempid = "1707175637531996762";

        //include 'incsmsapi.php'; // send SMS

// Prepare response

$sqlCust = "SELECT Unqid,id,push_flag,delete_flag FROM tbl_customer_invoice_2025 WHERE id='$invid'";
    $resultCust = $conn->query($sqlCust);
    if ($resultCust && $resultCust->num_rows > 0) {
        $rowCust = $resultCust->fetch_assoc();
        $responseData[] = $rowCust;
    }
        
   
    // Final output
$response = [
    "success" => true,
    'uid' => $uid,
    'invoice_details' => $responseData,
    "message" => "Invoice saved successfully"
];

echo json_encode($response, JSON_PRETTY_PRINT);

/*echo json_encode([
    "success" => true,
    "invoice_id" => $invoice_id,
    "message" => "Invoice saved successfully"
]);*/
?>
