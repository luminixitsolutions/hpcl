<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if ($_POST['action'] == 'getCustList') {

    $VedName = $_POST['SearchCust'];
    $FrId = $_POST['FrId'];
    $data = [];

    $q = "SELECT UserId AS id,CustPhone AS Phone,AccountName AS Fname FROM tbl_cust_general_ledger WHERE Roll=55 AND FrId='$FrId' AND (AccountName LIKE '%$VedName%' OR CustPhone LIKE '%$VedName%') GROUP BY CustPhone";
    $r = $conn->query($q);

    while ($rw = $r->fetch_assoc()) {
        $data[] = [
            'id' => $rw['id'],
            'Fname' => $rw['Fname'],
            'Phone' => $rw['Phone']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

if ($_POST['action'] == 'getCustDetails') {
    $id = $_POST['id'];
    $sql = "SELECT tu.UserId AS id,tu.CustPhone AS Phone,tu.AccountName AS Fname FROM tbl_cust_general_ledger tu WHERE tu.UserId='$id' AND tu.Roll=55 AND tu.Type='CINV' AND tu.CrDr='dr'";
    $row = getRecord($sql);
    echo json_encode($row);
}


if ($_POST['action'] == 'getCollections') {
    $id = $_POST['uid'];
    $FrId = $_POST['FrId'];
?>
    <h5>Customer Transactions Report</h5>
    <div style="overflow-x: auto; width: 125%;">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr style="text-align:center;">
                    <th>Invoice No</th>
                    <th>Date</th>
                    <th>Mode</th>
                    <th>Cr</th>
                    <th>Dr</th>
                    <th>Narration</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $TotCreditAmt = 0;
                $TotDebitAmt = 0;
                $allEntries = [];

                // Fetch all records ordered by date
                $sql = "SELECT * FROM tbl_cust_general_ledger WHERE UserId='$id' AND FrId='$FrId' ORDER BY PaymentDate";
                $rx = $conn->query($sql);

                while ($nx = $rx->fetch_assoc()) {
                    $allEntries[] = $nx;
                    if ($nx['CrDr'] == 'cr') {
                        $TotCreditAmt += $nx['Amount'];
                    } else {
                        $TotDebitAmt += $nx['Amount'];
                    }
                }

                // First show debit (Dr) entries
                foreach ($allEntries as $nx) {
                    if ($nx['CrDr'] == 'dr') {
                ?>
                        <tr>
                            <td>
  <a href="javascript:void(0)" onclick="loadInvoiceDetails('<?php echo $nx['UniqInvId']; ?>')">
    <?php echo $nx['InvoiceNo']; ?>
  </a>
</td>
                            <td><?php echo date("d/m/Y", strtotime($nx['PaymentDate'])); ?></td>
                            <td><?php echo $nx['PayMode']; ?></td>
                            <td>₹0</td>
                            <td>₹<?php echo number_format($nx['Amount'], 2); ?></td>
                            <td><?php echo $nx['Narration']; ?></td>
                        </tr>
                <?php
                    }
                }

                // Then show credit (Cr) entries
                foreach ($allEntries as $nx) {
                    if ($nx['CrDr'] == 'cr') {
                ?>
                        <tr>
                            <td></td>
                            <td><?php echo date("d/m/Y", strtotime($nx['PaymentDate'])); ?></td>
                            <td><?php echo $nx['PayMode']; ?></td>
                            <td>₹<?php echo number_format($nx['Amount'], 2); ?></td>
                            <td>₹0</td>
                            <td><?php echo $nx['Narration']; ?></td>
                        </tr>
                <?php
                    }
                }
                ?>
                <tr style="font-weight: bold; background: #e9ecef;">
                    <td colspan="3" style="text-align: right;">Total</td>
                    <td>₹<?php echo number_format($TotCreditAmt, 2); ?></td>
                    <td>₹<?php echo number_format($TotDebitAmt, 2); ?></td>
                    <td></td>
                </tr>
                <tr style="font-weight: bold; background: #d1ecf1;">
                    <td colspan="3" style="text-align: right;">Balance</td>
                    <td colspan="3">₹<?php echo number_format($TotDebitAmt -$TotCreditAmt , 2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({});
        });
    </script>
<?php
}

    
    if ($_POST['action'] == 'getCustCommissionDetails') {
        $id = $_POST['uid'];
        $dealerid = $_POST['FrId'];
        $sql2 = "SELECT SUM(Amount) AS TotAmt FROM tbl_cust_general_ledger WHERE FrId='$dealerid' AND UserId='$id' AND Type='CINV'";
        $row2 = getRecord($sql2);
        $sql = "SELECT SUM(Amount) AS PaidAmt FROM tbl_cust_general_ledger WHERE FrId='$dealerid' AND UserId='$id' AND Type!='CINV' AND CrDr='cr'";
        $row = getRecord($sql);
        $BalAmt = $row2['TotAmt'] - $row['PaidAmt'];
        echo json_encode(array('PaidAmt' => $row['PaidAmt'], 'BalAmt' => $BalAmt, 'TotAmt' => $row2['TotAmt']));
    }


 if ($_POST['action'] == 'loadInvoiceDetails') {
    $invid = $_POST['invid'];

    $sql = "SELECT tcid.*, tcp.ProductName 
            FROM tbl_customer_invoice_details_2025 tcid 
            INNER JOIN tbl_cust_products_2025 tcp ON tcid.ProdId = tcp.id 
            WHERE tcid.ServerInvId = '$invid'";

    $res = $conn->query($sql);
    $output = '';

    $totalQty = 0;
    $totalRate = 0;
    $totalAmt = 0;

    while ($row = $res->fetch_assoc()) {
        $output .= '<tr>
            <td class="align-middle">' . htmlspecialchars($row['ProductName']) . '</td>
            <td class="align-middle">' . htmlspecialchars($row['Qty']) . '</td>
            <td class="align-middle">&#8377; ' . number_format($row['Price'], 2) . '</td>
            <td class="align-middle">&#8377; ' . number_format($row['Total'], 2) . '</td>
        </tr>';

        $totalQty += $row['Qty'];
        $totalRate += $row['Price'];
        $totalAmt += $row['Total'];
    }

    // Add totals row
    $output .= '<tr style="font-weight: bold; background: #f0f0f0;">
        <td class="text-right">Total</td>
        <td>' . $totalQty . '</td>
       <td></td>
        <td>&#8377; ' . number_format($totalAmt, 2) . '</td>
    </tr>';

    echo $output;
}

?>