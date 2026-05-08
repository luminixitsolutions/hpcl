<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "Expense Request";
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
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
  $sql11 = "DELETE FROM tbl_expense_request WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      //alert("Deleted Successfully!");
      window.location.href="view-expenses.php";
    </script>
<?php } ?>

  
        <div class="main-container">
            <div class="container">
                <br><br>
               
               
               <?php 
           
               $sql = "SELECT te.*,tu.Fname,tu.Lname,tu.Photo AS Uphoto,tu2.Fname AS MgrName FROM tbl_expense_request te 
                LEFT JOIN tbl_users tu ON tu.id=te.UserId 
                LEFT JOIN tbl_users tu2 ON tu2.id=te.MrgBy WHERE tu.UnderUser='$user_id'"; 
            if($_REQUEST['val'] =='today'){
                $sql.=" AND te.CreatedDate='".date('Y-m-d')."'";
            }
            $sql.=" ORDER BY te.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                    $MgrName = $row['MgrName'];
               
             ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="mb-1">&#8377;<?php echo number_format($row['Amount'],2);?> </h5>
                                        <h5 class="mb-1"><?php echo $row['Fname']." ".$row['Lname']; ?> </h5>
                                        <p class="text-secondary"><?php echo $row['Narration'];?><br><?php echo $row['ExpenseDate'];?>
                                        <br>Pay By <?php echo $row['PaymentMode'];?></p>
                                        
                                        <?php if($row['ManagerStatus']=='1'){ echo "<h6 style='color:green;'>Approved By $MgrName </h6>";} else if($row['ManagerStatus']=='2'){ echo "<h6 style='color:red;'>Rejected By $MgrName</h6>";} else {?>
                                           Pending<?php } ?>
 
                                      
                                        

                                    </div>
                                    <div class="col-auto pl-0">
                                        <?php if($row['ManagerStatus']=='1' || $row['ManagerStatus']=='2'){} else{?>
                                      <a href="approve-expense-by-manager.php?id=<?php echo $row['id']; ?>" class="btn btn-default mb-2 mx-auto rounded" style="background-color: #e36012;padding: 1px;padding-left: 10px;padding-right: 10px;">Approve</a> 
                                        <?php } ?>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                         <?php } ?>
                        
                        <input type="text" class="Exp_Id" value="">
                    
                  

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
