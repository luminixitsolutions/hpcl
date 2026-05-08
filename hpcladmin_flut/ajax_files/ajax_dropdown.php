<?php 
session_start();
include_once __DIR__ . '/../config.php';
$user_id = $_SESSION['Admin']['id'];
$action = req('action', '');
if ($action === 'getState'){?>
    <option value="" selected="selected" disabled="">Select State</option>
<?php 
    $CountryId = $_POST['id'];
        $q = "select * from tbl_state WHERE CountryId = '$CountryId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($action == 'getCity'){?>
    <option value="" selected="selected" disabled="">Select City</option>
<?php 
    $StateId = $_POST['id'];
        $q = "select * from tbl_city WHERE StateId = '$StateId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($action == 'getArea'){?>
    <option value="" selected="selected" disabled="">Select Area</option>
<?php 
    $CityId = $_POST['id'];
        $q = "select * from tbl_area WHERE CityId = '$CityId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if ($action == 'getPincode') {

    $CityId = $_POST['id'];

    // 1. Selected City pincodes (selected)
    $q1 = "SELECT Pincode FROM tbl_pincode WHERE CityId='$CityId' AND Status='1'";
    $r1 = $conn->query($q1);
    while ($rw = $r1->fetch_assoc()) {
        echo '<option value="'.$rw['Pincode'].'" selected>'.$rw['Pincode'].'</option>';
    }

    // 2. Other City pincodes (not selected)
    $q2 = "SELECT Pincode FROM tbl_pincode WHERE CityId!='$CityId' AND Status='1'";
    $r2 = $conn->query($q2);
    while ($rw = $r2->fetch_assoc()) {
        echo '<option value="'.$rw['Pincode'].'">'.$rw['Pincode'].'</option>';
    }
}



if($action == 'getCourse'){?>
    <option value="" selected="selected" disabled="">Select Course</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_courses WHERE DeptId = '$DeptId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($action == 'getSubCat'){?>
    <option value="" selected="selected" disabled="">Select Sub Category</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_cust_sub_category_2025 WHERE CatId = '$DeptId' AND Status='1' AND ProdType=0 ORDER BY Name";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($action == 'getGodownSubCat'){?>
    <option value="" selected="selected" disabled="">Select Sub Category</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_godown_prod_sub_category WHERE CatId = '$DeptId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }


if($action == 'getCustProdCategory'){?>
<option selected="" value="all">All</option>
<?php 
    $FrId = $_POST['id'];
        $q = "select * from tbl_cust_category WHERE CreatedBy = '$FrId'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($action == 'getCustProduct'){?>
<option selected="" value="all">All</option>
<?php 
    $FrId = $_POST['id'];
        $q = "select * from tbl_cust_products WHERE CreatedBy = '$FrId' AND ProdType=0";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['ProductName']; ?></option>
<?php } } 

if($action == 'getCustProduct_2025'){?>
<option selected="" value="all">All</option>
<?php 
    $FrId = $_POST['id'];
        $q = "select * from tbl_cust_products_2025 WHERE CreatedBy = '$FrId' AND ProdType=0";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['ProductName']; ?></option>
<?php } }


if($action == 'getBatch'){?>
    <option value="" selected="selected" disabled="">Select Batch</option>
<?php 
    $CourseId = $_POST['id'];
        //$q = "select * from tbl_batches WHERE CourseId = '$CourseId' AND Status='1'";
    $q = "select * from tbl_batches WHERE DeptId = '$CourseId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['BatchName']." (".$rw['Month']." - ".$rw['Year'].")"; ?></option>
<?php } }

if($action == 'getStudent'){?>
    <option value="" selected="selected" disabled="">Select Student</option>
<?php 
    $BatchId = $_POST['id'];
        $q = "select * from tbl_users WHERE BatchId = '$BatchId' AND Status='1' AND Roll=2";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']." ".$rw['Lname']; ?></option>
<?php } }

if($action == 'getSubject'){?>
    <option value="" selected="selected" disabled="">Select Subject</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_subjects WHERE DeptId = '$DeptId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($action == 'getTsSubject'){?>
    <option value="" selected="selected" disabled="">Select Subject</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_ts_subjects WHERE DeptId = '$DeptId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($action == 'getSubTopic'){?>
    <option value="" selected="selected" disabled="">Select Sub Topic</option>
<?php 
    $SubjectId = $_POST['id'];
        $q = "select * from tbl_sub_topics WHERE SubjectId = '$SubjectId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($action == 'getCourseDetails'){
 $id = $_POST['id'];
    $query = "SELECT Type,Duration,CourseFees FROM tbl_courses WHERE id = '$id'";
    $row = getRecord($query);
    echo json_encode($row);
}

if($action == 'getBatchDetails'){
 $id = $_POST['id'];
    $query = "SELECT StartDate,BatchTime,Strength FROM tbl_batches WHERE id = '$id'";
    $row = getRecord($query);

    $sql = "SELECT * FROM tbl_users WHERE BatchId='$id' AND Roll=2";
    $rncnt = getRow($sql);

    $Seats = $row['Strength'] - $rncnt;

    echo json_encode(array('StartDate'=> $row['StartDate'],'BatchTime'=> $row['BatchTime'],'Seats'=> $Seats));
}

if($action == 'getStudentDetails'){
 $StudId = $_POST['id'];
 $DeptId = $_POST['DeptId'];
 $CourseId = $_POST['CourseId'];
 $BatchId = $_POST['BatchId'];
    $query = "SELECT CourseFees FROM tbl_users WHERE id = '$StudId'";
    $row = getRecord($query);

    $sql = "SELECT SUM(Amount) As Amount FROM tbl_general_ledger WHERE CustId='$StudId' AND Type='PR' AND DeptId='$DeptId' AND CourseId='$CourseId' AND BatchId='$BatchId' AND CrDr='dr'";
    $row2 = getRecord($sql);

    $BalAmt = $row['CourseFees'] - $row2['Amount'];

    echo json_encode(array('CourseFees'=> $row['CourseFees'],'BalAmt'=> $BalAmt));
}

if($action == 'getTestSeries'){?>
    <option value="" selected="selected" disabled="">Select Test Series</option>
<?php 
    //$CourseId = $_POST['id'];
    $DeptId = $_POST['id'];
        $q = "select * from tbl_test_series WHERE Status='1'";
        if($DeptId!=''){
      $q.= " AND DeptId='$DeptId'";
      }
      if($CourseId!=''){
      $q.= " AND BatchId = '$CourseId'";
        }
        //echo $q;
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['TestName']; ?></option>
<?php } }

if($action == 'getMulSubjects'){
    $deptid = $_POST['id'];
      $sql22 = "SELECT * FROM tbl_subjects WHERE Status=1 AND DeptId='$deptid'";
      /*if($deptid!=''){
      $sql22.= " AND DeptId='$deptid'";
      }
      if($courseid!=''){
      $sql22.= " AND CourseId='$courseid'";  
      }
      if($subjectid!=''){
      $sql22.= " AND SubjectId='$subjectid'";  
      }
      if($subtopicid!=''){
      $sql22.= " AND SubTopicId='$subtopicid'";  
      }*/
      //echo $sql22;
      $row22 = getList($sql22);
      foreach($row22 as $result22){
  ?>
  <div class="col-lg-4">
    <div class="form-group">
      <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" id="m<?php echo $result22['id']; ?>" name="Subjects[]" class="custom-control-input advisior" value="<?php echo $result22['id']; ?>" > <label class="custom-control-label" for="m<?php echo $result22['id']; ?>"><?php echo $result22['Name']; ?></label></div>
  </div></div>
<?php }}


if($action == 'getSubMenu'){?>
    <option value="" selected="selected" >Select Sub Menu</option>
<?php 
    $MenuId = $_POST['id'];
        $q = "select * from tbl_pages WHERE MenuId = '$MenuId'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Title']; ?></option>
<?php } } 

if($action == 'getSubMenu2'){?>
    <option value="" selected="selected">Select Sub Sub Menu</option>
<?php 
    $SubId = $_POST['id'];
    $MenuId = $_POST['MenuId'];
        $q = "select * from tbl_sub_sub_menu WHERE MenuId = '$MenuId' AND SubId = '$SubId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 


if($action == 'checkCourse'){
$CourseId = $_POST['id'];
$sql = "SELECT Type FROM tbl_courses WHERE id='$CourseId'";
$row = getRecord($sql);
$Type = $row['Type'];
if($Type == 'Paid'){
echo 1;
}
else{
echo 0;
}

    }
    
 if($action == 'getSubjectRoll'){
     $testid = $_POST['id'];
     $sql = "SELECT * FROM tbl_test_series WHERE id='$testid'";
     $row = getRecord($sql);
     $SubjectRoll = $row['SubjectRoll'];
     echo $SubjectRoll;
 }
    
 if($action == 'getUserDetails'){
    $id = $_POST['id'];
    $sql = "SELECT tu.* FROM tbl_users tu WHERE tu.id='$id'";
    $row = getRecord($sql);
    echo json_encode($row);
    }
    
     if($action == 'getUserDetails2'){
    $CellNo = $_POST['CellNo'];
    $sql = "SELECT tu.* FROM tbl_users tu WHERE tu.Phone='$CellNo' AND tu.Roll=55";
    $rncnt = getRow($sql);
    if($rncnt > 0){
    $row = getRecord($sql);
    echo json_encode($row);
    }
    else{
        echo 0;
    }
    }
    
    if($action == 'getPkgDetails'){
    $id = $_POST['id'];
    $date = date('Y-m-d');
    $sql = "SELECT * FROM tbl_packages WHERE id='$id'";
    $row = getRecord($sql);
    if($row['Period'] == 1){
        $Duration = $row['Duration'];
        $validity = date('Y-m-d', strtotime($date. ' + '.$Duration.' months'));
        
    }
    else{
        $Duration = $row['Duration'];
        $validity = date('Y-m-d', strtotime($date. ' + '.$Duration.' year'));
    }
    echo json_encode(array('PkgAmt'=>$row['Amount'],'PkgDiscount'=>$row['Discount'],'PkgValidity'=>$validity));
    }
    
if($action == 'getInvoiceNos'){?>
         <option value="all" selected>All</option>
    <?php 
        $CustId = $_POST['id'];
            $q = "SELECT * FROM tbl_invoice WHERE Status=1 AND CustId='$CustId'";
            $r = $conn->query($q);
            while($rw = $r->fetch_assoc())
        {
    ?>
                    <option value="<?php echo $rw['InvoiceNo']; ?>"><?php echo $rw['InvoiceNo']; ?></option>
    <?php } } 
    
    
     if($action == 'approveRequest'){
    $id = $_POST['id'];
    $status = $_POST['status'];
    $ApproveDate = date('Y-m-d');
    $ApproveTime = date('h:i a');
    if($status == 1){
        $line = "Approve By";
    }
    else{
        $line = "Pending By";
    }
    $sql = "UPDATE tbl_request_product_stock SET Status='$status',ApproveBy='$user_id',ApproveDate	='$ApproveDate',ApproveTime='$ApproveTime',ApproveLine='$line' WHERE id='$id'";
    $conn->query($sql);
    echo $status;
    
    } 
    
    
    if($action == 'getRawSubCat'){?>
    <option value="" selected="selected" disabled="">Select Sub Category</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_cust_sub_category_2025 WHERE CatId = '$DeptId' AND Status='1' AND ProdType=1";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($action == 'getRawProductDetails'){
    $id = $_POST['id'];
    $sql = "SELECT Unit FROM tbl_cust_products2 WHERE id='$id'";
    $row = getRecord($sql);
    echo json_encode(array('Unit'=>$row['Unit']));
    }
    
    if($action == 'getSubZone'){?>
     <option value="all" selected="selected" >All</option>
<?php 
    $DeptId = $_POST['id'];
         $q = "select * from tbl_sub_zone WHERE CatId = '$DeptId' AND Status='1' ";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }    
?>