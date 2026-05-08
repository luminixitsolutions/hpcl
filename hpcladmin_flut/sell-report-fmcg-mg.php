<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Selling-Products";
$Page = "Products";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | View Product List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
</head>
<body>

 <div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">

<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


<div class="layout-container">



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">FMCG MG Report

</h4>

<div class="card">
    
    <div class="row mb-3">
  <div class="form-group col-lg-2">
    <label class="form-label">Division</label>
    <select class="form-control filter" id="Division">
      <option value="">Select Division</option>
      <?php 
        $q = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=3";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc()){
          echo "<option value='{$rw['Name']}'>{$rw['Name']}</option>";
        }
      ?>
    </select>
  </div>

  <div class="form-group col-lg-2">
    <label class="form-label">Segment</label>
    <select class="form-control filter" id="Segment">
      <option value="">Select Segment</option>
      <?php 
        $q = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=4";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc()){
          echo "<option value='{$rw['Name']}'>{$rw['Name']}</option>";
        }
      ?>
    </select>
  </div>

  <div class="form-group col-lg-2">
    <label class="form-label">Family</label>
    <select class="form-control filter" id="Family">
      <option value="">Select Family</option>
      <?php 
        $q = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=5";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc()){
          echo "<option value='{$rw['Name']}'>{$rw['Name']}</option>";
        }
      ?>
    </select>
  </div>

  <div class="form-group col-lg-2">
    <label class="form-label">Class</label>
    <select class="form-control filter" id="ClassId">
      <option value="">Select Class</option>
      <?php 
        $q = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=6";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc()){
          echo "<option value='{$rw['Name']}'>{$rw['Name']}</option>";
        }
      ?>
    </select>
  </div>

  <div class="form-group col-lg-2">
    <label class="form-label">Mc. Desc</label>
    <select class="form-control filter" id="McDesc">
      <option value="">Select Mc. Desc</option>
      <?php 
        $q = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=7";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc()){
          echo "<option value='{$rw['Name']}'>{$rw['Name']}</option>";
        }
      ?>
    </select>
  </div>

  <div class="form-group col-lg-2">
    <label class="form-label">Brand Desc</label>
    <select class="form-control filter" id="BrandDesc">
      <option value="">Select Brand Desc</option>
      <?php 
        $q = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=8";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc()){
          echo "<option value='{$rw['Name']}'>{$rw['Name']}</option>";
        }
      ?>
    </select>
  </div>
</div>


<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
             
                <th>Id</th>
                <th>Product name</th>
                <th>Barcode No</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Product Type</th>
                <th>Purchase Price</th>
                <th>Total Price</th>
                <th>Discount %</th>
                <th>Discount Amt</th>
                <th>Sell Price</th>
                <th>Division</th>
                <th>Segment</th>
                <th>Family</th>
                <th>Class</th>
                <th>Mc. Desc</th>
                <th>Brand Desc</th>
                <th>Status</th>
              
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql = "SELECT p.*,c.Name As Category,cs.Name As SubCatName,tcm.Name AS DivisionName,tcm2.Name AS SegmentName,tcm3.Name AS FamilyName,tcm4.Name AS ClassName,tcm5.Name AS McDescName,tcm6.Name AS BrandDescName FROM tbl_cust_products2 p 
                    LEFT JOIN tbl_cust_category_2025 c ON c.id=p.CatId 
                    LEFT JOIN tbl_cust_sub_category_2025 cs ON cs.id=p.SubCatId 
                    LEFT JOIN tbl_common_master tcm ON tcm.id=p.Division 
                    LEFT JOIN tbl_common_master tcm2 ON tcm2.id=p.Segment 
                    LEFT JOIN tbl_common_master tcm3 ON tcm3.id=p.Family 
                    LEFT JOIN tbl_common_master tcm4 ON tcm4.id=p.ClassId 
                    LEFT JOIN tbl_common_master tcm5 ON tcm5.id=p.McDesc 
                    LEFT JOIN tbl_common_master tcm6 ON tcm6.id=p.BrandDesc WHERE p.ProdType=0 AND p.ProdType2!=3";
            $sql.= " ORDER BY p.id DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
             
             ?>
            <tr>
              
  
  <td><?php echo $row['id'];?></td>
                <td><?php echo $row['ProductName']; ?></td>
              <td><?php echo $row['BarcodeNo']; ?></td>
               
                <td><?php echo $row['Category']; ?></td>
                <td><?php echo $row['SubCatName']; ?></td>
                <td><?php if($row['ProdType2']=='1'){ echo "<span style='color:green;'>MRP Product</span>";} else if($row['ProdType2']=='2'){ echo "<span style='color:red;'>Making Product</span>";} else { echo "<span style='color:orange;'>Other</span>";} ?></td>
                <td class="align-middle"><?php echo number_format($row["PurchasePrice"],2); ?></td>
                 <td class="align-middle"><?php echo number_format($row["SubTotal"],2); ?></td>
               <td class="align-middle"><?php echo number_format($row["DiscPer"],2); ?></td>
                <td class="align-middle"><?php echo number_format($row["Discount"],2); ?></td>
                <td>&#8377;<?php echo number_format($row["MinPrice"],2); ?></td>
              
                    <td><?php echo $row['DivisionName']; ?></td>
                     <td><?php echo $row['SegmentName']; ?></td>
                      <td><?php echo $row['FamilyName']; ?></td>
                       <td><?php echo $row['ClassName']; ?></td>
                        <td><?php echo $row['McDescName']; ?></td>
                        <td><?php echo $row['BrandDescName']; ?></td>
                 
               <td><?php if($row['Status']=='1'){ echo "<span style='color:green;'>Publish</span>";} else { echo "<span style='color:red;'>Not Publish</span>";} ?></td>
            
            </tr>
           <?php } ?>
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


<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    var table = $('#example').DataTable({
        "scrollX": true,
        "pageLength": 10,
        "lengthMenu": [10, 25, 50, 100],
        dom: 'Bfrtip',
        order: [[0, 'asc']],
        buttons: [
            {
                extend: 'excelHtml5',
                title: null,
                filename: 'sell_report_fmcg_mg',
                exportOptions: {
                    modifier: { page: 'all' } // ✅ export all rows, not just current page
                },
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var downrows = 5;

                    // shift rows
                    $('row', sheet).each(function() {
                        var r = parseInt($(this).attr('r'));
                        $(this).attr('r', r + downrows);
                    });
                    $('row c', sheet).each(function() {
                        var attr = $(this).attr('r');
                        var pre = attr.substring(0, 1);
                        var ind = parseInt(attr.substring(1));
                        $(this).attr('r', pre + (ind + downrows));
                    });

                    // helper
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

                    var title1   = AddRow(1, [{ cell: 'A', text: 'Maha Chai Pvt. Ltd.' }]);
                    var title2   = AddRow(2, [{ cell: 'A', text: 'Sell Report FMCG MG' }]);
                    var generated= AddRow(3, [{ cell: 'A', text: 'Generated on: <?= date("d/m/Y h:i A"); ?>' }]);
                    var blankRow = AddRow(4, [{ cell: 'A', text: '' }]);

                    sheet.childNodes[0].childNodes[1].innerHTML =
                        title1 + title2 + generated + blankRow + sheet.childNodes[0].childNodes[1].innerHTML;
                }
            }
        ]
    });

    // 🔎 Apply filters
    $('.filter').on('change', function() {
        var colIndex = {
            "Division": 11,
            "Segment": 12,
            "Family": 13,
            "ClassId": 14,
            "McDesc": 15,
            "BrandDesc": 16
        };
        var id = $(this).attr('id');
        var val = $(this).val();
        table.column(colIndex[id]).search(val ? '^' + val + '$' : '', true, false).draw();
    });
});
</script>



</body>
</html>
