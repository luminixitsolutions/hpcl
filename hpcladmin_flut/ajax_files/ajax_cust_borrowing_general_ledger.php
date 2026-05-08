<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];


if ($_POST['action'] == 'getCustBalAmt') {
    $id = $_POST['id'];
    $CustId = $_POST['CustId'];
    
       $sql2 = "SELECT sum(creditqty)-sum(debitqty) AS balqty,sum(creditqty) AS Amount,sum(debitqty) AS TotPaidAmt FROM (SELECT (case when CrDr='dr' then sum(Amount) else '0' end) as debitqty,(case when CrDr='cr' then sum(Amount) else '0' end) as creditqty FROM `tbl_general_ledger` WHERE Type='PR' AND UserId='$CustId' AND BorrowFlag='1' GROUP by CrDr) as a";
    $row2 = getRecord($sql2);
                                    	
                                    	
   /* $sql2 = "SELECT SUM(Amount) As TotPaidAmt FROM tbl_general_ledger WHERE InvoiceNo='$id' AND Type='PR' AND CrDr='cr' AND UserId='$CustId' AND Flag='Customer-Invoice' AND AccRoll='Customer' AND PayMode='Borrowing'";
    $row2 = getRecord($sql2);*/
    $TotAmt = $row2['Amount'];
    $TotPaidAmt = $row2['TotPaidAmt'];
    $TotBalAmt = $row2['balqty'];
    //$TotBalAmt = $TotAmt - $TotPaidAmt;
    echo json_encode(array('TotAmt' => $TotAmt, 'TotPaidAmt' => $TotPaidAmt, 'TotBalAmt' => $TotBalAmt));
}



if ($_POST['action'] == 'getCollections') {
    $id = $_POST['id']; 
    $CustId = $_POST['CustId'];?>
    <h5>Payment Receive Transactions</h5>
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
            $sql = "SELECT * FROM tbl_general_ledger WHERE UserId='$CustId' AND Type IN ('PR') AND Flag='Customer-Invoice' AND AccRoll='Customer'AND BorrowFlag='1' ORDER BY id";
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
                <th colspan="3">₹<?php echo number_format($TotCreditAmt-$TotDebitAmt, 2); ?></th>

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

$sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_general_ledger WHERE Type='PR'";
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
    
 $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',
                            AccCode='$AccCode',
                            AccountName='$CustName',
                            InvoiceNo='$InvoiceNo',Amount='$Amount',CrDr='dr',Roll=5,
                            Type='PR',CreatedDate='$CreatedDate',PaymentDate='$PayDate',
                            PayMode='$PayType',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',Narration='$Narration',
                            SellId='$SellId',Flag='Customer-Invoice',AccRoll='Customer',CreatedBy='$user_id',BorrowFlag=1";
                        $conn->query($sql4);
                        $PostId = mysqli_insert_id($conn);
                        $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='0',
                            AccCode='AC1',
                            AccountName='Company Account',
                            InvoiceNo='$InvoiceNo',Amount='$Amount',CrDr='cr',Roll=5,
                            Type='PR',CreatedDate='$CreatedDate',PaymentDate='$PayDate',
                            PayMode='$PayType',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',Narration='$Narration',
                            SellId='$SellId',Flag='Customer-Invoice',AccRoll='Company',PostId='$PostId',CreatedBy='$user_id',BorrowFlag=1";
                        $conn->query($sql4);

	echo 1;
	}
	else{
        $sql = "DELETE FROM tbl_general_ledger WHERE id='$id'";
        $conn->query($sql);
        $sql = "DELETE FROM tbl_general_ledger WHERE PostId='$id'";
        $conn->query($sql);
        $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',
        AccCode='$AccCode',
        AccountName='$CustName',
        InvoiceNo='$InvoiceNo',Amount='$Amount',CrDr='dr',Roll=5,
        Type='PR',CreatedDate='$CreatedDate',PaymentDate='$PayDate',
        PayMode='$PayType',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',Narration='$Narration',
        SellId='$SellId',Flag='Customer-Invoice',AccRoll='Customer',CreatedBy='$user_id',BorrowFlag=1";
    $conn->query($sql4);
    $PostId = mysqli_insert_id($conn);
    $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='0',
        AccCode='AC1',
        AccountName='Company Account',
        InvoiceNo='$InvoiceNo',Amount='$Amount',CrDr='cr',Roll=5,
        Type='PR',CreatedDate='$CreatedDate',PaymentDate='$PayDate',
        PayMode='$PayType',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',Narration='$Narration',
        SellId='$SellId',Flag='Customer-Invoice',AccRoll='Company',PostId='$PostId',CreatedBy='$user_id',BorrowFlag=1";
    $conn->query($sql4);
	echo 1;
	}
}
?>