<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Franchise-Report-2025";
$Page = "Raw-Product-Stock-Report-2025";
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
<h4 class="font-weight-bold py-3 mb-0">Raw Product Stock Difference Report
</h4>

<div class="card" style="padding: 10px;">
       <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">
    
    <div class="form-group col-md-2">
                                            <label class="form-label">Zone </label>
                                            <select class="form-control" id="ZoneId" name="ZoneId" >
                                                <option selected=""  value="all">All</option>
                                                <?php $sql = "SELECT * FROM tbl_zone WHERE Status=1";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($_POST["ZoneId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                              
 <div class="form-group col-md-2">
                                            <label class="form-label">Sub Zone </label>
                                            <select class="form-control" id="SubZoneId" name="SubZoneId" >
                                                <option selected=""  value="all">All</option>
                                                <?php $sql = "SELECT * FROM tbl_sub_zone WHERE Status=1";
                                                    $row = getList($sql);
                                                    foreach($row as $result){?>
                                                <option value="<?php echo $result['id'];?>" <?php if($_POST["SubZoneId"]==$result['id']) {?> selected
                                                    <?php } ?>><?php echo $result['Name'];?></option>
                                                <?php } ?>
                                                  
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
    <div class="form-group col-md-4">
    <label class="form-label">Franchise <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="FrId" id="FrId">
            <option value="all" selected>All</option>
            <?php
                $sql4 = "SELECT * FROM tbl_users_bill WHERE Status=1 AND Roll=5";
                $row4 = getList($sql4);
                foreach ($row4 as $result) {
            ?>
            <option <?php if ($_REQUEST["FrId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ShopName']; ?></option>
            <?php } ?>
        </select>
    </div>

   

<div class="form-group col-md-2">
<label class="form-label"> Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off">
</div> 
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1">
    <label class="form-label">&nbsp;</label>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="form-group col-md-1">
<label class="form-label">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
    <?php if(isset($_POST['Search'])) {?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
               <th>Franchise Name</th>
               <th>Zone</th>
                 <th>Sub Zone</th>
                <th>Raw Product Name</th>
                 <th>Category</th>
                 <th>Last Purchase Price</th>
               <!--  <th>Credit</th>
              <th>Debit</th>-->
             <th>System Stock</th>
             <th>Store Stock</th>
             <th>Differece</th>
              
              
             
            
            </tr>
        </thead>
        <tbody>
           <?php 
            $i=1;
            
            $sql = "SELECT ts.FrId,ts.ProdId,ts.Unit,ts.Unit2,p.ProductName,tcc.Name As CatName,p.MinQty,tu.ZoneId,tu.SubZoneId   FROM tbl_cust_prod_stock_2025 ts 
                    INNER JOIN tbl_cust_products2 p ON ts.ProdId=p.id 
                   INNER JOIN tbl_users_bill tu ON ts.FrId=tu.id
                    INNER JOIN tbl_cust_category_2025 tcc ON p.CatId=tcc.id WHERE ts.Status='Cr' AND ts.FrId!=0 AND ts.ProdType=1";

                    if($_POST['FrId']){
                $FrId = $_POST['FrId'];
                if($FrId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.FrId='$FrId'";
                }
            }
            
            
            
             if($_POST['ZoneId']){
                $ZoneId = $_POST['ZoneId'];
                if($ZoneId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.ZoneId='$ZoneId'";
                }
                }
                
                 if($_POST['SubZoneId']){
                $SubZoneId = $_POST['SubZoneId'];
                if($SubZoneId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.SubZoneId='$SubZoneId'";
                }
                }

            if($_POST['CatId']){
                $CatId = $_POST['CatId'];
                if($CatId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND p.CatId='$CatId'";
                }
            }
            
            if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql.= " AND ts.StockDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql.= " AND ts.StockDate<='$ToDate'";
            }
            
            $sql.=" GROUP BY p.id ORDER BY ts.id DESC";    
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql3 = "SELECT ShopName FROM tbl_users_bill WHERE id='".$row['FrId']."'";
                $row3 = getRecord($sql3);
                $sql2 = "SELECT sum(creditqty) AS creditqty,sum(debitqty) AS debitqty,sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock_2025` WHERE FrId='".$row['FrId']."' AND ProdId='".$row['ProdId']."' AND ProdType=1";
                if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql2.= " AND StockDate>='$FromDate'";
                }
                if($_REQUEST['ToDate']){
                    $ToDate = $_REQUEST['ToDate'];
                    $sql2.= " AND StockDate<='$ToDate'";
                }
               
                $sql2.= " GROUP by Status) as a";
                $row2 = getRecord($sql2);
                
                $MinQty = $row['MinQty'];
                $BalQty = $row2['balqty'];
                if($BalQty <  $MinQty){
                    $bgcolor = "background-color: #ff9f9f;";
                }
                else{
                    $bgcolor = "";
                }
                
                if($row['Unit']!='Pieces'){
                    $creditqty = ($row2['creditqty']/1000)." ".$row['Unit2'];
                    $debitqty = ($row2['debitqty']/1000)." ".$row['Unit2'];
                    $balqty = ($row2['balqty']/1000)." ".$row['Unit2'];
                    
                }
                else{
                    $creditqty = $row2['creditqty']." ".$row['Unit2'];
                    $debitqty = $row2['debitqty']." ".$row['Unit2'];
                    $balqty = $row2['balqty']." ".$row['Unit2'];
                }
                
                 $sql24 = "SELECT * FROM tbl_zone WHERE id='".$row['ZoneId']."'";
                $row24 = getRecord($sql24);
                
                $sql21 = "SELECT * FROM tbl_sub_zone WHERE id='".$row['SubZoneId']."'";
                $row21 = getRecord($sql21);
                
                $sql25 = "SELECT PurchasePrice FROM `tbl_cust_prod_stock_2025` WHERE FrId='".$row['FrId']."' AND ProdType=1 AND ProdId='".$row['ProdId']."' AND Status='Cr' ORDER BY StockDate,id DESC LIMIT 1";
                $row25 = getRecord($sql25);
                
                $sqlCurrent2 = "
    SELECT 
        IFNULL(SUM(creditqty), 0) AS creditqty, 
        IFNULL(SUM(debitqty), 0) AS debitqty, 
        IFNULL(SUM(creditqty) - SUM(debitqty), 0) AS balqty
    FROM (
        SELECT 
            CASE WHEN Status = 'Cr' THEN IFNULL(SUM(Qty2), 0) ELSE 0 END AS creditqty,
            CASE WHEN Status = 'Dr' THEN IFNULL(SUM(Qty2), 0) ELSE 0 END AS debitqty
        FROM tbl_mrp_available_stock
        WHERE FrId = '{$row['FrId']}' 
          AND ProdId = '{$row['Prod_Id']}' 
          AND ProdType = 1 
          $dateFilter
        GROUP BY Status
    ) a
";
$curr2 = getRecord($sqlCurrent2);

             ?>
            <tr>
               <td><?php echo $i; ?></td>
               <td><?php echo $row3['ShopName'];?></td>
                <td><?php echo $row24['Name']; ?></td>
             <td><?php echo $row21['Name']; ?></td>
               <td><?php echo $row['ProductName']; ?></td>
              <td><?php echo $row['CatName'];?></td>
              <td><?php echo $row25['PurchasePrice'];?></td>
               <!-- <td><?php echo $creditqty; ?></td>
                <td><?php echo $debitqty; ?></td>-->
                <td><?php echo $balqty; ?></td>
                 <td><?php echo $curr2['balqty']." ".$row['Unit2']; ?></td>
                <td><?php echo $balqty-$curr2['balqty']." ".$row['Unit2']; ?></td>
                
              
            </tr>
           <?php $i++;} ?>

          
        </tbody>
    </table>
</div>
<?php } ?>
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
        order: [[8, 'asc']],
        buttons: [
            {
                extend: 'excelHtml5',
                title: null,   // remove default auto-title
                filename: 'product_available_raw_stock_diff_report',
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];

                    var downrows = 5;
                    var clRow = $('row', sheet);

                    // shift all rows down
                    clRow.each(function() {
                        var attr = $(this).attr('r');
                        var ind = parseInt(attr);
                        ind = ind + downrows;
                        $(this).attr("r", ind);
                    });

                    // update row references in cells
                    $('row c', sheet).each(function() {
                        var attr = $(this).attr('r');
                        var pre = attr.substring(0, 1);
                        var ind = parseInt(attr.substring(1));
                        ind = ind + downrows;
                        $(this).attr("r", pre + ind);
                    });

                    // helper to insert new rows
                    function AddRow(index, data) {
                        var row = '<row r="' + index + '">';
                        for (var i = 0; i < data.length; i++) {
                            row += '<c t="inlineStr" r="' + data[i].cell + index + '" s="2">';
                            row += '<is><t>' + data[i].text + '</t></is>';
                            row += '</c>';
                        }
                        row += '</row>';
                        return row;
                    }

                    // custom headers for Product Available Stock Diff Report
                    var title1   = AddRow(1, [{ cell: 'A', text: 'Maha Chai Pvt. Ltd.' }]);
                    var title2   = AddRow(2, [{ cell: 'A', text: 'Product Available Raw Stock Difference Report' }]);
                    var period   = AddRow(3, [{ cell: 'A', text: 'As on Date: <?php echo date("d/m/Y"); ?>' }]);
                    var datetime = AddRow(4, [{ cell: 'A', text: 'Generated on: <?php echo date("d/m/Y h:i A"); ?>' }]);
                    var blankRow = AddRow(5, [{ cell: 'A', text: '' }]);

                    // prepend our custom rows before actual data
                    sheet.childNodes[0].childNodes[1].innerHTML =
                        title1 + title2 + period + datetime + blankRow + sheet.childNodes[0].childNodes[1].innerHTML;
                }
            }
        ]
    });
});
</script>
</body>
</html>
