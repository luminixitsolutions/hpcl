<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "Request-Product-Stock";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?></title>
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
<h4 class="font-weight-bold py-3 mb-0">Download Excel Of Request Product Stock
  
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               <th>Invoice No</th>
               <th>Franchise</th> 
            
                <th>Request Date</th> 
                <th>Total Product</th>
           
                <th>Narration</th>
             
                <th>Created Date</th>
                <th>Item Name</th>
              <th>Qty</th>
              
             
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $id = $_GET['id'];
            $sql = "SELECT tp.*,tu.Fname AS GodownName FROM tbl_request_product_stock_2025 tp
                    LEFT JOIN tbl_users_bill tu ON tu.id=tp.FromFrId WHERE tp.id='$id'";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT * FROM tbl_request_product_stock_items_2025 WHERE TransferId='".$row['id']."'";
               $rncnt2 = getRow($sql2);
                
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td>#<?php echo $row['id']; ?></td>
               <td><?php echo $row['GodownName']; ?></td>
               <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StockDate']))); ?></td>
               <td><?php echo $rncnt2; ?></td>
               <td><?php echo $row['Narration']; ?></td> 
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
                <td>-</td>
                <td>-</td>
            </tr>

             <?php 
                $sql44 = "SELECT ttg.*,tgp.ProductName FROM tbl_request_product_stock_items_2025 ttg 
                        LEFT JOIN tbl_cust_products_2025 tgp ON ttg.ProdId=tgp.id WHERE ttg.TransferId='$id'";
                $row44 = getList($sql44);
                foreach($row44 as $result){?>
                    <tr>
               <td><?php echo $i; ?> </td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
              
               <td><?php echo $result['ProductName']; ?></td> 
               <td><?php echo $result['Qty']." ".$result['Unit']; ?></td> 
            </tr>
            <?php } ?> 
           <?php $i++;}  ?>
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
