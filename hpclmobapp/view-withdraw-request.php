<?php session_start();
require_once 'config.php';
$id = $_GET['id'];
$PageName = "Amount Req";
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

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendor/daterangepicker-master/daterangepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 

        <!-- page content start -->

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_withdraw_request WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
     // alert("Deleted Successfully!");
      window.location.href="view-withdraw-request.php";
    </script>
<?php } ?>
       
            <div class="container mb-4" style="padding-right: 1px;
padding-left: 1px;">

                   
                <div class="card">
                   
                    <div class="card-body px-0 pt-0"><br>
                        <div style="float:right;padding-right: 10px;">
                    <a href="add-withdraw-request.php" class="btn btn-default mb-2 mx-auto rounded">Send Req</a>
              </div><br><br>
                        <ul class="list-group list-group-flush" id="show_prod" style="width: 100%;">
                             <?php 
                                $sql2 = "SELECT * FROM tbl_withdraw_request WHERE UserId='$UserId' ORDER BY id DESC"; 
                                $res2 = $conn->query($sql2);
                                $row_cnt = mysqli_num_rows($res2);
                                    if($row_cnt > 0){
                                    while($row = $res2->fetch_assoc()){
                                       

                                        if($row['Status'] == '1'){
                                        $Status = '<h6 class="text-success">Approved</h6>';
                                        }   
                                        else{
                                        $Status = '<h6 class="text-danger">Pending</h6>';
                                        }
                                     ?>
                            <li class="list-group-item">
                               <div class="row align-items-center">
                                   
                                    <div class="col align-self-center pr-0">
                                        <h6>&#8377;<?php echo number_format($row['Amount'],2);?></h6>
                                        <!--<h7 class="font-weight-normal mb-1" style="color: #212529"><?php echo $row['Narration']; ?><br></h7>-->
                                       
                                        <p class="small text-secondary"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['ReqDate'])))?></p>
                                    </div>
                                    <div class="col-auto">
                                        <?php echo $Status; ?>
                                         <!--<a href="edit-mobile-details.php?id=<?php echo $result['CustId'];?>&mbid=<?php echo $result['id'];?>" class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-edit"></i></a>-->
                                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $result['id']; ?>&action=delete" class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-trash"></i></a>
                         
                                    </div>
                                </div>
                            </li>
                        <?php }} else{ ?><br>
                          <!--  <div class="col-auto">
                                        <h6 class="text-danger">Sorry! No Customer Found..</h6>
                                    </div> -->
                           <?php } ?>
                        </ul>
                    </div>
                </div>
             
                
            
        </div>
    </main>
 <?php include_once 'footer.php';?>

    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>

  <script src="vendor/daterangepicker-master/moment.min.js"></script>
    <script src="vendor/daterangepicker-master/daterangepicker.js"></script>
    <!-- page level custom script -->
    <script src="js/app.js"></script>

    </body>

</html>
