<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['User']['id'];
$FranchiseId = $_SESSION['FranchiseId'];
if($_POST['action'] == 'Add'){
    $ProductName = addslashes(trim($_POST['ProductName']));
    $CatId = $_POST['CatId'];
    $MinPrice = $_POST['MinPrice'];
    $CgstPer = addslashes(trim($_POST['CgstPer']));
    $SgstPer = addslashes(trim($_POST['SgstPer']));
    $IgstPer = addslashes(trim($_POST['IgstPer']));
    $GstAmt = addslashes(trim($_POST['GstAmt']));
    $ProdPrice = addslashes(trim($_POST['ProdPrice']));

    $CreatedDate = date('Y-m-d');
    $Status = $_POST['Status'];
    $SrNo = $_POST['SrNo'];
    $randno = rand(1,100);
    $src = $_FILES['Photo']['tmp_name'];
    $fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
    $fnm = str_replace(" ","_",$fnm);
    $ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
    $dest = '../../uploads/'. $randno . "_".$fnm . $ext;
    $imagepath =  $randno . "_".$fnm . $ext;
    if(move_uploaded_file($src, $dest))
    {
    $Photo = $imagepath ;
    } 
    else{
      $Photo = $_POST['OldPhoto'];
    }
    
    
    function RandomStringGenerator($n)
    {
        $generated_string = "";   
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $len = strlen($domain);
        for ($i = 0; $i < $n; $i++)
        {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        return $generated_string;
    } 
    $n = 10;
    $Code = RandomStringGenerator($n); 
    
    $sql = "INSERT INTO tbl_cust_products SET ProductName='$ProductName',CatId='$CatId',MinPrice='$MinPrice',Status='$Status',SrNo='$SrNo',Photo='$Photo',CreatedDate='$CreatedDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',ProdPrice='$ProdPrice',CreatedBy='$FranchiseId'";
    $conn->query($sql);
    $ProdId = mysqli_insert_id($conn); 
$Code2 = $Code."".$ProdId; 
$sql2 = "UPDATE tbl_cust_products SET code='$Code2' WHERE id='$ProdId'";
$conn->query($sql2);
if (isset($_FILES['Files'])) {
    $errors = array();
    foreach ($_FILES['Files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $key . $_FILES['Files']['name'][$key];
        $file_size = $_FILES['Files']['size'][$key];
        $file_tmp = $_FILES['Files']['tmp_name'][$key];
        $file_type = $_FILES['Files']['type'][$key];
        $FileName = $_FILES['Files']['name'][$key];
        
        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB';
        }
        if ($file_name == '0' || $file_size == '0') {} else {
             $query = "INSERT into tbl_cust_product_images SET ProductId='$ProdId',Files='$file_name',FileName='$FileName'";
            $desired_dir = "../../uploads/";
            if (empty($errors) == true) {
                if (is_dir($desired_dir) == false) {
                    mkdir("$desired_dir", 0700); // Create directory if it does not exist
                }
                if (is_dir("$desired_dir/" . $file_name) == false) {
                    move_uploaded_file($file_tmp, "../../uploads/" . $file_name);
                } else {
                    // rename the file if another one exist
                    $new_dir = "../../uploads/" . $file_name . time();
                    rename($file_tmp, $new_dir);
                }
                $conn->query($query);
            } else {
                print_r($errors);
            }
        }
        if (empty($error)) {
           
           
        }
    }
}

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Added",
  text: "New Product Added Successfully!",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="../product-lists.php";
  }
}); });</script>

<?php
}


if($_POST['action'] == 'Edit'){
    $id = $_POST['id'];
    $ProductName = addslashes(trim($_POST['ProductName']));
    $CatId = $_POST['CatId'];
    $MinPrice = $_POST['MinPrice'];
    $CgstPer = addslashes(trim($_POST['CgstPer']));
    $SgstPer = addslashes(trim($_POST['SgstPer']));
    $IgstPer = addslashes(trim($_POST['IgstPer']));
    $GstAmt = addslashes(trim($_POST['GstAmt']));
    $ProdPrice = addslashes(trim($_POST['ProdPrice']));
    $CreatedDate = date('Y-m-d');
    $Status = $_POST['Status'];
    $SrNo = $_POST['SrNo'];
    $randno = rand(1,100);
    $src = $_FILES['Photo']['tmp_name'];
    $fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
    $fnm = str_replace(" ","_",$fnm);
    $ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
    $dest = '../../uploads/'. $randno . "_".$fnm . $ext;
    $imagepath =  $randno . "_".$fnm . $ext;
    if(move_uploaded_file($src, $dest))
    {
    $Photo = $imagepath ;
    } 
    else{
      $Photo = $_POST['OldPhoto'];
    }
    
    $sql = "UPDATE tbl_cust_products SET ProductName='$ProductName',CatId='$CatId',MinPrice='$MinPrice',Status='$Status',SrNo='$SrNo',Photo='$Photo',ModifiedDate='$CreatedDate',CgstPer='$CgstPer',SgstPer='$SgstPer',IgstPer='$IgstPer',GstAmt='$GstAmt',ProdPrice='$ProdPrice' WHERE id='$id'";
    $conn->query($sql);
    $ProdId = $_POST['id'];
    
if (isset($_FILES['Files'])) {
    $errors = array();
    foreach ($_FILES['Files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $key . $_FILES['Files']['name'][$key];
        $file_size = $_FILES['Files']['size'][$key];
        $file_tmp = $_FILES['Files']['tmp_name'][$key];
        $file_type = $_FILES['Files']['type'][$key];
        $FileName = $_FILES['Files']['name'][$key];
        
        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB';
        }
        if ($file_name == '0' || $file_size == '0') {} else {
             $query = "INSERT into tbl_cust_product_images SET ProductId='$ProdId',Files='$file_name',FileName='$FileName'";
            $desired_dir = "../../uploads/";
            if (empty($errors) == true) {
                if (is_dir($desired_dir) == false) {
                    mkdir("$desired_dir", 0700); // Create directory if it does not exist
                }
                if (is_dir("$desired_dir/" . $file_name) == false) {
                    move_uploaded_file($file_tmp, "../../uploads/" . $file_name);
                } else {
                    // rename the file if another one exist
                    $new_dir = "../../uploads/" . $file_name . time();
                    rename($file_tmp, $new_dir);
                }
                $conn->query($query);
            } else {
                print_r($errors);
            }
        }
        if (empty($error)) {
           
           
        }
    }
}

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Updated",
  text: "Product Updated Successfully!",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="../product-lists.php";
  }
}); });</script>
<?php
}

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_cust_products SET Photo='' WHERE id=$id";
        $conn->query($q);
        // $src = "../../uploads/$Photo";
        // unlink($src);

    echo "Product Photo Delete Successfully";
} 
if($_POST['action'] == 'deletePhoto2'){
    $id = $_POST['id'];
    $pid = $_POST['pid'];
    $Photo = $_POST['Photo'];
        $q = "DELETE FROM tbl_cust_product_images WHERE id=$id AND ProductId='$pid'";
        $conn->query($q);
        // $src = "../../uploads/$Photo";
        // unlink($src);

    echo "Product Photo Delete Successfully";
}

if($_POST['action'] == 'showProdImages'){ 
    $id = $_POST['id'];
  $sql2 = "SELECT * FROM tbl_cust_product_images WHERE ProductId='$id'";
  $res2 = $conn->query($sql2);
  $rncnt = mysqli_num_rows($res2);
  if($rncnt > 0){
    while($row2 = $res2->fetch_assoc()){?>
    <input type="hidden" name="OldMulImage" id="OldMulImage<?php echo $row2["id"]; ?>" value="<?php echo $row2["Files"]; ?>">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" onclick="delete_photo2(<?php echo $row2["id"]; ?>,<?php echo $_POST["id"]; ?>)"></a><img src="../uploads/<?php echo $row2['Files'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
<?php }} } 

?>