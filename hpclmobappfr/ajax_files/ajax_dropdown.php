<?php 
session_start();
include_once '../config.php';
if($_POST['action'] == 'getState'){?>
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

if($_POST['action'] == 'getCity'){?>
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

if($_POST['action'] == 'getArea'){?>
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

if($_POST['action'] == 'saveLatlng'){
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $_SESSION['lat'] = $lat;
    $_SESSION['lng'] = $lng;
    echo "saved";
}   

if($_POST['action'] == 'checkSponserId'){
    $SponserId = $_POST['SponserId'];
    $sql = "SELECT * FROM customers WHERE (CustomerId='$SponserId' OR Phone='$SponserId') AND Roll=7 AND Status=1";
    $rncnt = getRow($sql);
    if($rncnt > 0){
    $row = getRecord($sql);
    $id = $row['id'];
    $MemberName = $row['Fname']." ".$row['Lname'];
    echo json_encode(array('status'=>1,'name'=>$MemberName,'id'=>$id));  
        //echo 1;//Member id exist
    }
    else{
         echo json_encode(array('status'=>0,'msg'=>'Sponsor id does not exist')); 
        //echo 0;//Member id not exist
    }
} 

if($_POST['action'] == 'getAccNo'){
    $id = $_POST['id'];
    $sql = "SELECT * FROM tbl_banks WHERE id='$id'";
    $row = getRecord($sql);
    echo json_encode(array('BankName'=>$row['BankName'],'AccNo'=>$row['AccNo']));
} 

if($_POST['action'] == 'getProduct'){?>
	<option value="" selected="selected" disabled="">Select Product</option>
<?php 
	$CatId = $_POST['catid'];
	$SubCatId = $_POST['subcatid'];
	$FranchiseId = $_SESSION['FranchiseId'];
         $q = "SELECT id,ProductName FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$FranchiseId' AND ProdType=0 AND ProdType2 IN (1,3) AND delete_flag=0 AND checkstatus=1 AND CatId='$CatId' AND SubCatId='$SubCatId' ORDER BY ProductName";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['ProductName']; ?></option>
<?php } }  

if($_POST['action'] == 'getRawProduct'){?>
	<option value="" selected="selected" disabled="">Select Product</option>
<?php 
	$CatId = $_POST['catid'];
	$SubCatId = $_POST['subcatid'];
	$AllocatedProdIds = $_POST['allocateprod'];
         $q = "SELECT tp.ProductName,tp.id FROM tbl_cust_products2 tp WHERE tp.id IN ($AllocatedProdIds) AND tp.ProdType='1' AND tp.CatId='$CatId' AND tp.SubCatId='$SubCatId' ORDER BY tp.ProductName";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['ProductName']; ?></option>
<?php } } 


if($_POST['action'] == 'getSubCat'){?>
	<option value="" selected="selected" disabled="">Select Sub Catgeory</option>
<?php 
	$CatId = $_POST['catid'];
	$FranchiseId = $_SESSION['FranchiseId'];
         $q = "SELECT id,Name FROM tbl_cust_sub_category_2025 WHERE CatId='$CatId' AND Status=1 AND ProdType=0";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

 if($_POST['action'] == 'getProdList'){
        
        $ProdName = $_POST['ProdName'];
        	$CatId = $_POST['CatId'];
	$SubCatId = $_POST['SubCatId'];
	$FranchiseId = $_SESSION['FranchiseId'];
    $data = [];

$q = "SELECT id,ProductName FROM tbl_cust_products_2025 WHERE Status='1' AND CreatedBy='$FranchiseId' AND ProdType=0 AND ProdType2 IN (1,3) AND delete_flag=0 AND checkstatus=1 AND (ProductName LIKE '%$ProdName%')";
if($CatId!=''){
$q.=" AND CatId='$CatId' ";
}
if($SubCatId!=''){
$q.=" AND SubCatId='$SubCatId'";
}
$q.=" ORDER BY ProductName";
    $r = $conn->query($q);

   while ($rw = $r->fetch_assoc()) {
        $data[] = [
            'id' => $rw['id'],
            'ProductName' => $rw['ProductName']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
    } 
    
    
     if($_POST['action'] == 'getRawProdList'){
        
        $ProdName = $_POST['ProdName'];
        	$CatId = $_POST['CatId'];
	$SubCatId = $_POST['SubCatId'];
	$FranchiseId = $_SESSION['FranchiseId'];
		$AllocatedProdIds = $_POST['allocateprod'];
    $data = [];

//$q = "SELECT tp.ProductName,tp.id FROM tbl_cust_products2 tp WHERE tp.id IN ($AllocatedProdIds) AND tp.ProdType='1' AND tp.OldNew=0 AND (tp.ProductName LIKE '%$ProdName%')";
$q = "SELECT tp.ProductName,tp.id FROM tbl_cust_products_2025 tp WHERE tp.ProdType='1' AND tp.checkstatus=1 AND tp.CreatedBy='$FranchiseId' AND (tp.ProductName LIKE '%$ProdName%')";
if($CatId!=''){
$q.=" AND tp.CatId='$CatId'";
}
if($SubCatId!=''){
$q.=" AND tp.SubCatId='$SubCatId'";
}
$q.=" ORDER BY tp.ProductName";
//echo $q;
    $r = $conn->query($q);

   while ($rw = $r->fetch_assoc()) {
        $data[] = [
            'id' => $rw['id'],
            'ProductName' => $rw['ProductName']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
    } 
?>