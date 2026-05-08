<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];


if ($_POST['action'] == 'getInvoice') {
    $id = $_POST['id']; ?>
    <option value="" selected>...</option>
    <?php
    $sql1 = "SELECT * FROM tbl_request_product_stock WHERE FinancerStatus2=1 AND VedId='$id'";
    $row1 = getList($sql1);
    foreach ($row1 as $result) {
    ?>
        <option value="<?php echo $result['id']; ?>">INV<?php echo $result['id']; ?></option>
    <?php }
}

if ($_POST['action'] == 'getInvoiceDetails') {
    $id = $_POST['id'];
    $CustId = $_POST['CustId'];
    $sql = "SELECT TotalAmount AS Amount FROM tbl_request_product_stock WHERE id='$id'";
    $row = getRecord($sql);
    $sql2 = "SELECT SUM(Amount) As TotPaidAmt FROM tbl_vendor_ledger WHERE InvoiceNo='$id' AND Type='PR' AND CrDr='cr' AND UserId='$CustId'";
    $row2 = getRecord($sql2);
    $TotAmt = $row['Amount'];
    $TotPaidAmt = $row2['TotPaidAmt'];
    $TotBalAmt = $TotAmt - $TotPaidAmt;
    echo json_encode(array('TotAmt' => $TotAmt, 'TotPaidAmt' => $TotPaidAmt, 'TotBalAmt' => $TotBalAmt));
}

if ($_POST['action'] == 'getCustBalAmt') {
    $uid = $_POST['uid'];
    //paid amt
    $sql2 = "SELECT SUM(Amount) As TotPaidAmt FROM tbl_vendor_ledger WHERE UserId='$uid' AND CrDr='dr' AND AccRoll='Vendor'";
    $row2 = getRecord($sql2);
    $TotPaidAmt = $row2['TotPaidAmt'];

    //debit amt
    $sql2 = "SELECT SUM(Amount) As TotAmt FROM tbl_vendor_ledger WHERE UserId='$uid' AND CrDr='cr' AND AccRoll='Vendor'";
    $row2 = getRecord($sql2);
    $TotAmt = $row2['TotAmt'];
    $TotBalAmt = $TotAmt - $TotPaidAmt;
    echo json_encode(array('TotAmt' => $TotAmt, 'TotPaidAmt' => $TotPaidAmt, 'TotBalAmt' => $TotBalAmt));
}

if ($_POST['action'] == 'getCollections') {
    $id = $_POST['id']; 
    $CustId = $_POST['CustId'];?>
    <h5>Payment Transactions</h5>
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr style="text-align:center;">
                <th>Date</th>
                <th>Mode</th>
                <th>Cr</th>
                <th>Dr</th>
               
                <th>Narration</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $srno = 1;
            $sql = "SELECT * FROM tbl_vendor_ledger WHERE UserId='$CustId' AND InvoiceNo='$id' AND Type IN ('PR','INV') ORDER BY id";
            $rx = $conn->query($sql);
            while ($nx = $rx->fetch_assoc()) {
                if ($nx['CrDr'] == 'cr') {
                    $Creditamt = number_format($nx['Amount'], 2);
                    $DebitAmt = "0";
                    $TotCreditAmt += $nx['Amount'];
                } else {
                    $DebitAmt = number_format($nx['Amount'], 2);
                    $Creditamt = "0";
                    $TotDebitAmt += $nx['Amount'];
                }

                if($nx['Type'] == 'OB'){
                    $Narration = "Invoice Amount";
                    $PayMode = "";
                }
                else{
                    $Narration = $nx['Narration'];
                    $PayMode = $nx['PayMode'];
                }
            ?>
                <tr>
                    <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $nx['PaymentDate']))); ?></td>
                    <td><?php echo $PayMode; ?></td>
                    <td>₹<?php echo $Creditamt; ?></td>
                    <td>₹<?php echo $DebitAmt; ?></td>
                    
                    <td><?php echo $Narration; ?></td>



                </tr>
            <?php $srno++;
            } ?>
            <tr>
                <th colspan="2">Total</th>
                <th>₹<?php echo number_format($TotCreditAmt, 2); ?></th>
                <th>₹<?php echo number_format($TotDebitAmt, 2); ?></th>
                
                <td></td>

            </tr>

            <tr>
                <th colspan="2">Balance</th>
                <th colspan="3">₹<?php echo number_format($TotDebitAmt - $TotCreditAmt, 2); ?></th>

            </tr>
            
        </tbody>
    </table>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({
      "scrollX": true
    });
        });
    </script>
<?php
}



if($_POST['action'] == 'SavePaymentReceive'){
	$id = $_POST['id'];
$CustId = addslashes(trim($_POST["CustId"]));
$CustName = addslashes(trim($_POST["CustName"]));
$CellNo = addslashes(trim($_POST['CellNo']));
$Address = addslashes(trim($_POST['Address']));
$AccCode = addslashes(trim($_POST['AccCode']));
$InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
$InvId = addslashes(trim($_POST['InvId']));
$Amount = addslashes(trim($_POST['Amount']));
$Interest = addslashes(trim($_POST['Interest']));
$TotAmount = addslashes(trim($_POST['TotAmount']));
$PayDate = addslashes(trim($_POST['PayDate']));
$PayType = addslashes(trim($_POST['PayType']));
$Narration = addslashes(trim($_POST['Narration']));
$MonthPeriod = addslashes(trim($_POST['MonthPeriod']));
$Month = date('m', strtotime($PayDate));
$ChequeNo = addslashes(trim($_POST['ChequeNo']));
$ChqDate = addslashes(trim($_POST['ChqDate']));
$BankName = addslashes(trim($_POST['BankName']));
$UpiNo = addslashes(trim($_POST['UpiNo']));
$CreatedDate = date('Y-m-d');

$sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_vendor_ledger WHERE Type='PR'";
    $row2 = getRecord($sql2);
    if($row2['maxid'] == ''){
        $SrNo = 1;
        $Code = "PR".$SrNo;
    }
    else{
        $SrNo = $row2['maxid']+1;
        $Code = "PR".$SrNo;
    }

if($id==''){
 $sql = "INSERT INTO tbl_vendor_ledger SET Code='$Code',SrNo='$SrNo',UserId='$CustId',AccCode='$AccCode',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$Amount',PaymentDate='$PayDate',CrDr='cr',Roll=3,Type='PR',PayMode='$PayType',Narration='$Narration',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
  $conn->query($sql);

	echo 1;
	}
	else{
        $sql = "DELETE FROM tbl_vendor_ledger WHERE id='$id'";
        $conn->query($sql);
        $sql = "INSERT INTO tbl_vendor_ledger SET Code='$Code',SrNo='$SrNo',UserId='$CustId',AccCode='$AccCode',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$Amount',PaymentDate='$PayDate',CrDr='cr',Roll=3,Type='PR',PayMode='$PayType',Narration='$Narration',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
        $conn->query($sql);

	echo 1;
	}
}
?>