<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Ask Query";
$UserId = $_SESSION['User']['id'];
//print_r($_SESSION["cart_item"]);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link href="css/toastr.min.css" rel="stylesheet" id="style">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?> 
        <div class="main-container">
            
            
           
            
            <div class="container mb-4">
                <?php if($DoughtStatus == 1){} else{?>
                <div style="float:right;">
                                                                   
                 <a href="add-query.php" class="btn btn-sm btn-default rounded">Ask Query</a>
            
                                
                                                                </div>
      <br><br>
      <?php } ?>
      
      <div class="form-group">
                               
                                <input type="text" class="form-control is-valid" id="SearchText" placeholder="Search Your Query..." oninput="searchDoughts()">
                               
                            </div>
                            
                <input type="hidden" id="name" value="<?php echo $CustName; ?>">
                 <input type="hidden" id="phone" value="<?php echo $CustPhone; ?>">
                  <input type="hidden" id="email" value="<?php echo $CustEmail; ?>">
                <div class="row" id="showResult">
                    <?php 
                    $i=1;
                   
                  
                    $sql2 = "SELECT tq.*,tu.Fname,tu.Lname,tu.Photo FROM tbl_customer_queries tq LEFT JOIN tbl_users tu ON tq.CustId=tu.id WHERE tq.CustId='$user_id'"; 
                    $rncnt2 = getRow($sql2);
                    if($rncnt2 > 0){
                    $res2 = getList($sql2);
                    foreach($res2 as $row){ 
                         if($i%2==0){
                            $bgcolor = "background-color: #e6f2ff;";
                        }
                        else{
                           $bgcolor = "background-color: #b3d9ff;"; 
                        }
                    $url =  $SiteUrl.'uploads/'.$row["Files"];
                    
                    /*$sql = "SELECT * FROM tbl_query_comments WHERE QueryId='".$row["id"]."'";
                    $rncnt = getRow($sql);
                    
                    $sql3 = "SELECT * FROM tbl_query_views WHERE QueryId='".$row["id"]."'";
                    $rncnt3 = getRow($sql3);*/
                    
                    ?>
                    
                    <input type="hidden" id="user_id" value="<?php echo $UserId; ?>">
 
   <input type="hidden" id="pid<?php echo $row["id"];?>" value="<?php echo $row["id"];?>">
 <input type="hidden" id="code<?php echo $row["id"];?>" value="<?php echo $row['code'];?>">
     <input type="hidden" id="prd_price<?php echo $row["id"];?>" value="<?php echo $row['Amount'];?>"> 
      <input type="hidden" id="qntno<?php echo $row["id"];?>" value="1">         
      
      
            <div class="col-12 col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <a href="query-details.php?id=<?php echo $row['id'];?>">
                                <div class="row align-items-center">
                                    <div class="col-auto pr-0">
                                        <div class="avatar avatar-40 rounded">
                                            <div class="background" style="background-image: url('<?php echo $UploadUrl;?>/uploads/<?php echo $row['Photo'];?>');">
                                                <img src="<?php echo $UploadUrl;?>/uploads/<?php echo $row['Photo'];?>" alt="" style="display: none;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pr-0">
                                        <h6 class=" mb-1" style="font-weight:500;font-size:16px;"><?php echo $row["Details"];?></h6>
                                        <span style="font-size:12px;color:#0066ff;"><?php echo $row['Fname']." ".$row['Lname'];?></span>
                                        <p class="small text-secondary" style="font-size:14px;">
                                            <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?>
                                        </p>
                                    </div>
                                  
                                </div></a>
                                
                              
                            </div>
                        </div>
                    </div>
                    
                  
                     <?php $i++;} } else{?>
                        <h3 style="color:grey;padding-left: 10px;font-size: 15px;">No Queries Found</h3>
                    <?php } ?>
                   
                </div>
                
          
            </div>

        </div>
    </main>
 <?php include_once 'footer.php'; ?>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="vendor/swiper/js/swiper.min.js"></script>
<script src="js/main.js"></script>
<script src="js/color-scheme-demo.js"></script>
<script src="js/app.js"></script>
<script src="js/toastr.min.js"></script>
<script>
    function searchDoughts(){
        var SearchText = $('#SearchText').val();
         var action = "searchDoughts";
    $.ajax({
    url:"ajax_files/ajax_queries.php",
    method:"POST",
    data : {action:action,SearchText:SearchText},
    success:function(data)
    {
        console.log(data);
      $('#showResult').html(data);
    }
    });
    }
</script>
</body>
</html>
