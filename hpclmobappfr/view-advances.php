<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "Advance Requests";
$Page = "Recharge";
$WallMsg = "NotShow"; ?>
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?>

        <!-- page content start -->
<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_advance_request WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      //alert("Deleted Successfully!");
      window.location.href="view-expenses.php";
    </script>
<?php } ?>

        <div class="main-container">
            <div class="container">
                 <div style="float:right;">
                                                                   
                 <a href="add-advance.php" class="btn btn-sm btn-default rounded">Add New</a>
            
                                
                                                                </div><br><br>
               
                        <?php 
                             $sql = "SELECT * FROM tbl_advance_request WHERE UserId='$user_id' ORDER BY id DESC";
                            $row = getList($sql);
                            foreach($row as $result){
                            ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="mb-1">&#8377;<?php echo number_format($result['Amount'],2);?> </h5>
                                        <p class="text-secondary"><?php echo $result['Narration'];?><br><?php echo $result['ExpenseDate'];?>
                                       </p>
                                        <?php if($result['Status'] == 1){?>
                                        <h6 class="text-success">Approved</h6>
                                        <?php } else {?>
                                        <h6 class="text-danger">Pending</h6>
                                        <?php } ?>
                                        

                                    </div>
                                    <div class="col-auto pl-0">
                                        <button class="btn btn-40 bg-default-light text-default rounded-circle">
                                            <a href="add-advance.php?id=<?php echo $result['id'];?>" class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-edit"></i></a>
                                        <!--<a href="javascript:void(0)" onclick="getExpId(<?php echo $result['id'];?>)"  class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-trash"></i></a>-->
                                        <a href="view-advances.php?action=delete&id=<?php echo $result['id'];?>"  class="btn-danger btn btn-sm btn-30 rounded"><i class="fa fa-trash"></i></a>
                     
                                        </button>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                         <?php } ?>
                        
                        <input type="text" class="Exp_Id" value="">
                    
                    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h5 class="modal-title">Are you sure?</h5>
      </div>
      <div class="modal-body">
          
        <button type="button" class="btn btn-success" onclick="delete()">YES</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">NO</button>
      </div>
     
    </div>

  </div>
</div>

            </div>
        </div>
    </main>

<?php include 'footer.php';?>
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


    <!-- page level custom script -->
    <script src="js/app.js"></script>
<script>
    function getExpId(id){
        $('#myModal').modal('show');
        $('.Exp_Id').val(id);
    }
</script>
   
</body>

</html>
