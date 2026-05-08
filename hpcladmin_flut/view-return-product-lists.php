<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Godown";
$Page = "Return-MRP-Product";
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
<h4 class="font-weight-bold py-3 mb-0">Return Product List
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               <th>Invoice No</th> 
               <th>Product Name</th> 
                <th>Qty</th> 
               
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT trp.*,tp.InvNo,tcp.ProductName FROM tbl_return_prod_items trp 
                    INNER JOIN tbl_return_prod_stock tp ON trp.InvId=tp.id 
                    INNER JOIN tbl_godown_products tcp ON trp.ProdId=tcp.id WHERE trp.InvId='".$_GET['id']."'";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
               
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['InvNo']; ?></td>
             
                <td><?php echo $row['ProductName']; ?></td>
                
                   <td><?php echo $row['Qty']; ?></td>
                    
               
           
           
        
              
            </tr>
           <?php $i++;} ?>
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
    });
});
</script>
</body>
</html>
