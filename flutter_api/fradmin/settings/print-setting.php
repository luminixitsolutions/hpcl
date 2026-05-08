<?php 
session_start();
include_once '../config.php';
//include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Selling-Products";
$Page = "Products";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add Customer Product | <?php echo $Proj_Title; ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  background-color: #f6f4fa;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  color: #2f2f2f;
  padding: 1px;
}
.card {
  background-color: #fff;
  border: none;
  border-radius: 16px;
  box-shadow: 0 3px 12px rgba(90, 60, 200, 0.08);
  padding: 10px 10px;
}
.page-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #5b3cc4;
  text-align: center;
  margin-bottom: 25px;
}
.form-label {
  font-weight: 600;
  font-size: 14px;
  color: #4a3aa0;
}
.form-control, .form-select {
  border: 1px solid #d8d1ff;
  border-radius: 10px;
  font-size: 14px;
  padding: 8px 10px;
  box-shadow: none;
  transition: all 0.2s ease-in-out;
}
.form-control:focus, .form-select:focus {
  border-color: #7a5fff;
  box-shadow: 0 0 0 0.2rem rgba(122, 95, 255, 0.15);
}
.btn-primary {
  background-color: #6a4fe0 !important;
  border: none;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 600;
  padding: 10px 20px;
}
.btn-primary:hover {
  background-color: #5a41c6 !important;
}
.btn-secondary {
  background-color: #b2bec3;
  border: none;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 600;
  padding: 10px 20px;
}
.section-title {
  background-color: #f5f2ff;
  border-left: 5px solid #6a4fe0;
  padding: 8px 12px;
  margin-bottom: 15px;
  font-weight: 600;
  color: #4a3aa0;
  border-radius: 6px;
}
@media (max-width: 768px) {
  .form-label { font-size: 13px; }
  .form-control, .form-select { font-size: 13px; }
  .btn-primary, .btn-secondary { width: 100%; margin-bottom: 8px; }
}
</style>
</head>

<body>

<?php 
 $BillSoftFrId = $_REQUEST['user_id'] ?? '';
$sql55 = "SELECT * FROM tbl_users_bill WHERE id='$BillSoftFrId'";
$row55 = getRecord($sql55);

if(isset($_POST['submit'])){
    $ShopName = addslashes(trim($_POST['ShopName']));
    $Address = addslashes(trim($_POST['Address']));
    $Phone = addslashes(trim($_POST['Phone']));
    $GstNo = addslashes(trim($_POST['GstNo']));
    $terms_condition = addslashes(trim($_POST['terms_condition']));
    $bottom_title = addslashes(trim($_POST['bottom_title']));
    $FssaiNo = addslashes(trim($_POST['FssaiNo']));
     $modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];
    $sql = "UPDATE tbl_users_bill SET PrintCompName='$ShopName',Address='$Address',PrintMobNo='$Phone',GstNo='$GstNo',terms_condition='$terms_condition',bottom_title='$bottom_title',FssaiNo='$FssaiNo',modified_time='$modified_time' WHERE id='$BillSoftFrId' AND Roll=5";
    $conn->query($sql);
    
    $sql = "UPDATE tbl_users SET PrintCompName='$ShopName',Address='$Address',PrintMobNo='$Phone',GstNo='$GstNo',terms_condition='$terms_condition',bottom_title='$bottom_title',FssaiNo='$FssaiNo',modified_time='$modified_time' WHERE id='$BillSoftFrId' AND Roll=5";
    $conn->query($sql);
    echo "<script>alert('Setting Updated Successfully!');window.location.href='print-setting.php?user_id=$BillSoftFrId';</script>";
}
     ?>
     
<div class="container-fluid">
  <div class="card">
         
   <!-- <div class="page-title"><i class="bi bi-box-seam"></i> Add / Edit Customer Product</div>-->

    <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
      <input type="hidden" name="action" value="Add">
      <input type="hidden" id="TempPrdId" name="TempPrdId" value="<?php echo rand(10000,99999); ?>">
      <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>"> 
       <input type="hidden" name="userid" id="userid" value="<?php echo $_GET['user_id']; ?>"> 

      <!-- Basic Info -->
      <div class="section-title"><i class="bi bi-info-circle"></i> Header</div>
      <div class="row g-3">
       <div class="form-group col-md-12">
   <label class="form-label">Comapany Name <span class="text-danger">*</span></label>
     <input type="text" name="ShopName" id="ShopName" class="form-control"
                                                placeholder="" value="<?php echo $row55['PrintCompName'];?>"
                                                autocomplete="off" >
    <div class="clearfix"></div>
 </div>
 
 <div class="form-group col-md-12">
   <label class="form-label">Comapany Address <span class="text-danger">*</span></label>
     <textarea name="Address" id="Address" class="form-control"
                                                placeholder=""
                                                autocomplete="off" ><?php echo htmlspecialchars($row55['Address']);?></textarea>
    <div class="clearfix"></div>
 </div>
 
 <div class="form-group col-md-4">
   <label class="form-label">Customer Care Number <span class="text-danger">*</span></label>
     <input type="text" name="Phone" id="Phone" class="form-control"
                                                placeholder="" value="<?php echo $row55['PrintMobNo'];?>"
                                                autocomplete="off" >
    <div class="clearfix"></div>
 </div>
 
  <div class="form-group col-md-4">
   <label class="form-label">GST No <span class="text-danger">*</span></label>
     <input type="text" name="GstNo" id="GstNo" class="form-control"
                                                placeholder="" value="<?php echo $row55['GstNo'];?>"
                                                autocomplete="off" >
    <div class="clearfix"></div>
 </div>
 <div class="form-group col-md-4">
   <label class="form-label">FSSAI No </label>
     <input type="text" name="FssaiNo" id="FssaiNo" class="form-control"
                                                placeholder="" value="<?php echo $row55['FssaiNo'];?>"
                                                autocomplete="off" >
    <div class="clearfix"></div>
 </div>
      </div>

      <!-- Pricing Section -->
      <div class="section-title mt-4"><i class="bi bi-info-circle"></i> Footer</div>
      <div class="row g-3">
        <div class="form-group col-md-12">
   <label class="form-label">Terms & Condition <span class="text-danger">*</span></label>
     <textarea name="terms_condition" id="terms_condition" class="form-control"
                                                placeholder=""
                                                autocomplete="off" ><?php echo $row55['terms_condition'];?></textarea>
    <div class="clearfix"></div>
 </div>
 
<div class="form-group col-md-12">
   <label class="form-label">Bottom Title<span class="text-danger">*</span></label>
     <textarea name="bottom_title" id="bottom_title" class="form-control"
                                                placeholder=""
                                                autocomplete="off" ><?php echo $row55['bottom_title'];?></textarea>
    <div class="clearfix"></div>
 </div>
 
      </div>



      <!-- Buttons -->
      <div class="text-center mt-4">
        <button type="submit" name="submit" class="btn btn-primary me-2"><i class="bi bi-save"></i> Save</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</script>
</body>
</html>
