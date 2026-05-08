<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "MahaTube";
$UserId = $_SESSION['User']['id'];
 ?>
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" id="style">
  
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
    <main class="flex-shrink-0 main">
      
      <style>


.bell{
  display:block;

 
  -webkit-animation: ring 4s .7s ease-in-out infinite;
  -webkit-transform-origin: 50% 4px;
  -moz-animation: ring 4s .7s ease-in-out infinite;
  -moz-transform-origin: 50% 4px;
  animation: ring 4s .7s ease-in-out infinite;
  transform-origin: 50% 4px;
}

@-webkit-keyframes ring {
  0% { -webkit-transform: rotateZ(0); }
  1% { -webkit-transform: rotateZ(30deg); }
  3% { -webkit-transform: rotateZ(-28deg); }
  5% { -webkit-transform: rotateZ(34deg); }
  7% { -webkit-transform: rotateZ(-32deg); }
  9% { -webkit-transform: rotateZ(30deg); }
  11% { -webkit-transform: rotateZ(-28deg); }
  13% { -webkit-transform: rotateZ(26deg); }
  15% { -webkit-transform: rotateZ(-24deg); }
  17% { -webkit-transform: rotateZ(22deg); }
  19% { -webkit-transform: rotateZ(-20deg); }
  21% { -webkit-transform: rotateZ(18deg); }
  23% { -webkit-transform: rotateZ(-16deg); }
  25% { -webkit-transform: rotateZ(14deg); }
  27% { -webkit-transform: rotateZ(-12deg); }
  29% { -webkit-transform: rotateZ(10deg); }
  31% { -webkit-transform: rotateZ(-8deg); }
  33% { -webkit-transform: rotateZ(6deg); }
  35% { -webkit-transform: rotateZ(-4deg); }
  37% { -webkit-transform: rotateZ(2deg); }
  39% { -webkit-transform: rotateZ(-1deg); }
  41% { -webkit-transform: rotateZ(1deg); }

  43% { -webkit-transform: rotateZ(0); }
  100% { -webkit-transform: rotateZ(0); }
}

@-moz-keyframes ring {
  0% { -moz-transform: rotate(0); }
  1% { -moz-transform: rotate(30deg); }
  3% { -moz-transform: rotate(-28deg); }
  5% { -moz-transform: rotate(34deg); }
  7% { -moz-transform: rotate(-32deg); }
  9% { -moz-transform: rotate(30deg); }
  11% { -moz-transform: rotate(-28deg); }
  13% { -moz-transform: rotate(26deg); }
  15% { -moz-transform: rotate(-24deg); }
  17% { -moz-transform: rotate(22deg); }
  19% { -moz-transform: rotate(-20deg); }
  21% { -moz-transform: rotate(18deg); }
  23% { -moz-transform: rotate(-16deg); }
  25% { -moz-transform: rotate(14deg); }
  27% { -moz-transform: rotate(-12deg); }
  29% { -moz-transform: rotate(10deg); }
  31% { -moz-transform: rotate(-8deg); }
  33% { -moz-transform: rotate(6deg); }
  35% { -moz-transform: rotate(-4deg); }
  37% { -moz-transform: rotate(2deg); }
  39% { -moz-transform: rotate(-1deg); }
  41% { -moz-transform: rotate(1deg); }

  43% { -moz-transform: rotate(0); }
  100% { -moz-transform: rotate(0); }
}

@keyframes ring {
  0% { transform: rotate(0); }
  1% { transform: rotate(30deg); }
  3% { transform: rotate(-28deg); }
  5% { transform: rotate(34deg); }
  7% { transform: rotate(-32deg); }
  9% { transform: rotate(30deg); }
  11% { transform: rotate(-28deg); }
  13% { transform: rotate(26deg); }
  15% { transform: rotate(-24deg); }
  17% { transform: rotate(22deg); }
  19% { transform: rotate(-20deg); }
  21% { transform: rotate(18deg); }
  23% { transform: rotate(-16deg); }
  25% { transform: rotate(14deg); }
  27% { transform: rotate(-12deg); }
  29% { transform: rotate(10deg); }
  31% { transform: rotate(-8deg); }
  33% { transform: rotate(6deg); }
  35% { transform: rotate(-4deg); }
  37% { transform: rotate(2deg); }
  39% { transform: rotate(-1deg); }
  41% { transform: rotate(1deg); }

  43% { transform: rotate(0); }
  100% { transform: rotate(0); }
}
</style>
<?php if($pageval == 'placeorder'){} else{?>

<?php } ?>
 <?php 
$user_id = $_SESSION['User']['id'];
$UserId = $_SESSION['User']['id'];
$sql110 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row110 = getRecord($sql110);
$AccName = $row110['AccName'];
$Roll = $row110['Roll'];
$Member = $row110['Member'];
$FullName = $row110['Fname']." ".$row110['Lname'];
$PackageStatus = $row110['PackageStatus'];
/*$sql55 = "SELECT * FROM tbl_offer_percentage WHERE AccountId='7'";
$row55 = getRecord($sql55);
$DiscPer = $row55['Percentage'];*/
$sql11x = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as credit,(case when Status='Dr' then sum(Amount) else 0 end) as debit FROM wallet WHERE UserId='$user_id' group by Status) as a";
                        $res11x = $conn->query($sql11x);
                        $row11x = $res11x->fetch_assoc();
$mybalance = $row11x['credit'] - $row11x['debit'];

$sql12x = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when CrDr='cr' then sum(Points) else 0 end) as credit,(case when CrDr='dr' then sum(Points) else 0 end) as debit FROM tbl_points WHERE UserId='$user_id' group by CrDr) as a";
$res12x = $conn->query($sql12x);
$row12x = $res12x->fetch_assoc();
$mybalancepnts = $row12x['credit'] - $row12x['debit'];

/*
$sql_11 = "SELECT * FROM tbl_service_fee WHERE id=1";
$row_11 = getRecord($sql_11);
$OrderPrice = $row_11['OrderPrice']; 
if($PackageStatus==1 && $Member == 1){
$ServiceFee = 0;
$ServiceFeeSub = 0;*/
/*$Service_Fee = $row_11['Fee']; 
$Service_Fee_Sub = $row_11['SubFee'];*/
/*$Service_Fee = 0; 
$Service_Fee_Sub = 0;
} else{
$ServiceFee = $row_11['Fee']; 
$ServiceFeeSub = $row_11['SubFee'];
$Service_Fee = $row_11['Fee']; 
$Service_Fee_Sub = $row_11['SubFee'];
}*/

/*$sql_22 = "SELECT * FROM tbl_free_subscribe WHERE UserId='$user_id'";
$rncnt_22 = getRow($sql_22);


  $Location = $row110['Location'];

$sql_33 = "SELECT * FROM city WHERE id='$Location'";
$row_33 = getRecord($sql_33);
$FieldName = $row_33['Name'];*/
/*if($LocName == 'Pune'){
    $FieldName = "Pune";
}
if($LocName == 'Ahemadnagar'){
    $FieldName = "Ahemadnagar";
}
if($LocName == 'Dhule'){
    $FieldName = "Dhule";
}*/
//echo $FieldName;
  ?>
        <header class="header">
            <div class="row">
                <div class="col-auto px-0">
                    <?php if($pageval == 'placeorder'){?>
                        <a href="<?php echo $SiteUrl;?>/product-category.php" class="btn btn-40 btn-link back-btn">
                        <span class="material-icons">keyboard_arrow_left</span>
                    </a>

                    <?php } else{?>
                    <button class="btn btn-40 btn-link back-btn" type="button">
                        <span class="material-icons">keyboard_arrow_left</span>
                    </button>
                <?php } ?>
                </div>
                <div class="text-left  align-self-center">
                    <a class="navbar-brand" href="#">
                        <h5 class="mb-0"><?php echo $PageName; ?></h5>
                    </a>
                      
                </div>
                <div class="ml-auto  col-auto pl-0">
                    
                    <!--  <span style="padding-right:10px;"> 
                    <a href="#" class=" btn btn-40 btn-link" style="padding-top:10px;">
                        <span class="material-icons bell" >redeem</span>
                        <span  class="counter" style="color:#fff;font-weight:600;font-size:13px;"><span style="background-color:#287939;padding:4px;border-radius:10px;"><?php echo $mybalancepnts;?></span></span>
                    </a>
                    </span> -->
                    
                       <?php if(isset($_SESSION['User'])){?>
                    <!-- <a href="#" class="mb-1 font-weight-normal" style="font-size: 15px;
    color: white;">&#8377;<?php echo number_format($mybalance,2);?></a>&nbsp;&nbsp;&nbsp;
                    <a href="add-money.php" class="btn btn-default btn-30 rounded-circle"><i class="material-icons">add</i></a> -->
                    <?php } ?>
                    <?php if($PageName == 'My Address') {
                        if($_GET['page']){
                            $page = "add-address.php?page=".$_GET['page'];
                        }
                        else{
                           $page = "add-address.php"; 
                        }
                    ?>
                        <a href="<?php echo $page;?>" class="btn btn-40 text-white">
                        <span class="material-icons vm">add</span>
                    </a>
                <?php } else{?>
                    <a href="shopping-cart.php" class=" btn btn-40 btn-link" style="padding-top:10px;" >
                        <span class="material-icons bell">local_mall</span>
                        <span  class="counter" style="color:#fff;font-weight:600;font-size:13px;"><span style="background-color:#287939;padding:4px;border-radius:10px;" id="showcount"><?php echo count($_SESSION["cart_item"]);?></span></span>
                    </a>
                <?php } ?>
                <?php if($_SESSION['Roll'] == 3){
                    $profileurl = "profile.php";
                }
                else{
                   $profileurl = "profile.php"; 
                }
                    ?>
                   <a href="<?php echo $profileurl; ?>" class="avatar avatar-30 shadow-sm rounded-circle ml-2">
                        <figure class="m-0">
                              <?php if($row110['Photo']==''){ ?>
                            
                            <i class="material-icons" style="width: 30px;height: 30px;color: #000;padding-top: 2px;">account_circle</i>
                            <?php } else { ?>
                            <img src="<?php echo $Uploadurl;?>/uploads/<?php echo $row110['Photo']; ?>" alt="" style="width: 30px;height: 30px;">
                            <?php } ?>
                        </figure>
                    </a>
                </div>
            </div>
        </header>
 <?php 
    if($WallMsg == 'NotShow'){} else{
        if(isset($_SESSION['User'])){
  ?>       
<!-- <div class="container mt-3 mb-4 text-center" style="margin-bottom: 0rem !important;margin-top: -1rem !important;">
            <h2 class="text-white"><span class="material-icons" style="font-size: 2rem;">account_balance_wallet</span>&nbsp;&nbsp;&#8377;<?php echo number_format($mybalance,2);?></h2>
        </div> --><?php }} ?>
        <div class="main-container">
            
            <div class="container mb-4">
               
                <div class="row">
                    
                      <div class="col-12 col-md-6">
                        <video width="100%" height="240"  poster="../videos/11.jpg" controls>
  <source src="../videos/v2/Instant Ready - Tanduri Chai Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Instant Ready - Tandoor Chai</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/22.jpg" controls>
  <source src="../videos/v2/Instant Ready - Vanilla Coffee Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Instant Ready - Vanilla Coffee</h5>
                    </div>
                    
                     <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/33.jpg" controls>
  <source src="../videos/v2/Lemon Tea Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Lemon Tea Recipe</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/44.jpg" controls>
  <source src="../videos/v2/Misal Gravy Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Misal Gravy Recipe</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/55.jpg" controls>
  <source src="../videos/v2/Pavbhaji Premix Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Pavbhaji Premix Recipe</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/66.jpg" controls>
  <source src="../videos/v2/Puri Bhaji Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Puri Bhaji Recipe</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/77.jpg" controls>
  <source src="../videos/v2/Rose Tea Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Rose Tea Recipe</h5>
                    </div>
                    
                     <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/88.jpg" controls>
  <source src="../videos/v2/Sabudana Vada Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Sabudana Vada Recipe</h5>
                    </div>
                    
                     <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/99.jpg" controls>
  <source src="../videos/v2/Tea Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Tea Recipe</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/100.jpg"  controls>
  <source src="../videos/v2/Vadapav Masala Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Vadapav Masala Recipe</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240"  poster="../videos/101.jpg" controls>
  <source src="../videos/v2/Vanilla Coffee Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Vanilla Coffee Recipe</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/102.jpg" controls>
  <source src="../videos/v2/Mocktail_Recipes/Mocktail - Chilly Guava Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Mocktail - Chilly Guava Recipe</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/103.jpg" controls>
  <source src="../videos/v2/Mocktail_Recipes/Mocktail - Jamun Kala Khatta Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Mocktail - Jamun Kala Khatta Recipe</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/104.jpg" controls>
  <source src="../videos/v2/Mocktail_Recipes/Mocktail - Lychee Lemon Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Mocktail - Lychee Lemon Recipe</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/105.jpg" controls>
  <source src="../videos/v2/Mocktail_Recipes/Mocktail - Mango Passion Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Mocktail - Mango Passion Recipe</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/106.jpg" controls>
  <source src="../videos/v2/Mocktail_Recipes/Mocktail - Mint Mojito Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Mocktail - Mint Mojito Recipe</h5>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <video width="100%" height="240" poster="../videos/107.jpg" controls>
  <source src="../videos/v2/Mocktail_Recipes/Mocktail - Orange Mint Recipe.mp4" type="video/mp4">
  <source src="movie.ogg" type="video/ogg">
  Your browser does not support the video tag.
</video>
<h5 style="text-align: center;">Mocktail - Orange Mint Recipe</h5>

                    </div>
                    
                  
                    
                    
                </div>
            </div>

        </div>
    </main><br><br><br>
 <?php include_once 'footer.php'; ?>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="vendor/swiper/js/swiper.min.js"></script>
<script src="js/main.js"></script>
<script src="js/color-scheme-demo.js"></script>
<script src="js/app.js"></script>

</body>
</html>
