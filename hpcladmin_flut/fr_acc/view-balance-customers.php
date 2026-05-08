<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Dashboard";
$Page = "Dashboard";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | View Customers List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">





<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Balance Customer List
</h4>
<br>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Customer Name</th>
                <th>Contact No</th>
                <th>Address</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
               
                <th>Balance Amount</th>
   
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tu.* FROM tbl_users tu  WHERE tu.Roll=3 ORDER BY tu.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql4 = "SELECT sum(creditqty) AS TotAmt,sum(debitqty) AS PaidAmt,sum(creditqty)-sum(debitqty) AS balqty from (SELECT (case when CrDr='dr' then sum(Amount) else '0' end) as debitqty,(case when CrDr='cr' then sum(Amount) else '0' end) as creditqty FROM `tbl_general_ledger` WHERE Flag='Invoice' AND AccRoll='Customer' AND UserId='".$row['id']."' GROUP by CrDr) as a";
                 $row4 = getRecord($sql4);
                 $balqty = $row4['balqty'];
                 $tot_amt+=$row4['TotAmt'];
                 $tot_paidamt+=$row4['PaidAmt'];
                 $tot_balamt+=$row4['balqty'];
                 if($balqty > 0){
             ?>
            <tr>
               <td> <?php echo $i; ?></td>
             
              <td><?php echo $row['Name']; ?></td>
            
           
                <td><?php echo $row['Phone']; ?></td>
              
                
                  <td><?php echo $row['Address']; ?></td>
              
            <td>&#8377;<?php echo number_format($row4['TotAmt'],2); ?></td>
            <td>&#8377;<?php echo number_format($row4['PaidAmt'],2); ?></td>
            <td>&#8377;<?php echo number_format($row4['balqty'],2); ?></td>
               
          
              
            </tr>
           <?php $i++;} } ?>
           
           <tr>
               <td><?php echo $i; ?></td>
               <th></th>
               <th></th>
               <th>Total</th>
               <th>&#8377;<?php echo number_format($tot_amt,2); ?></th>
            <th>&#8377;<?php echo number_format($tot_paidamt,2); ?></th>
            <th>&#8377;<?php echo number_format($tot_balamt,2); ?></th>
           </tr>
        </tbody>
    </table>
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

<script type="text/javascript">
 
	$(document).ready(function() {
    $('#example').DataTable({
      "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });

});
</script>
</body>
</html>
