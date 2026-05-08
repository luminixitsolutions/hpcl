<?php 
$UserId = $_SESSION['User']['id'];
$user_id = $_SESSION['User']['id'];
$sql110 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row110 = getRecord($sql110);
$Name = $row110['Fname']." ".$row110['Lname'];
$Phone = $row110['Phone'];
$EmailId = $row110['EmailId'];
$AccName = $row110['AccName'];
$Roll = $row110['Roll'];
$Member = $row110['Member'];
$PkgDate = $row110['PkgDate'];
$Validity = $row110['Validity'];
$ExpDate = date("d/m/Y", strtotime(str_replace('-', '/',$row110['Validity'])));

$CurrDate = date('Y-m-d');
$diff = strtotime($Validity) - strtotime($CurrDate);
$Days = ($diff / 86400);
$RemainDays2 = $Days + 1;
if($RemainDays2 == 1){
    $RemainDays = "Today";
}
else{
$RemainDays = $RemainDays2." days";
}

$sql11x = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as credit,(case when Status='Dr' then sum(Amount) else 0 end) as debit FROM wallet WHERE UserId='$user_id' group by Status) as a";
                        $res11x = $conn->query($sql11x);
                        $row11x = $res11x->fetch_assoc();
$mybalance = $row11x['credit'] - $row11x['debit'];

$sql12x = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when CrDr='cr' then sum(Points) else 0 end) as credit,(case when CrDr='dr' then sum(Points) else 0 end) as debit FROM tbl_points WHERE UserId='$user_id' group by CrDr) as a";
$res12x = $conn->query($sql12x);
$row12x = $res12x->fetch_assoc();
$mybalancepnts = $row12x['credit'] - $row12x['debit'];
 ?>
<div class="main-menu">
        <div class="row mb-4 no-gutters">
            <div class="col-auto"><button class="btn btn-link btn-40 btn-close text-white"><span class="material-icons">chevron_left</span></button></div>
              <?php if(isset($_SESSION['User'])) {?>
            <div class="col-auto">
                <div class="avatar avatar-40 rounded-circle position-relative">
                    <figure class="background">
                       <?php 
                        if($row110['Photo'] == ''){
                     ?>
                    <img src="<?php echo $SiteUrl;?>/user_icon.jpg" alt="" style="width: 140px;height: 140px;">
                <?php } else  {?>
                     <img src="<?php echo $Uploadurl;?>/uploads/<?php echo $row110['Photo']; ?>" alt="" style="width: 140px;height: 140px;">
                
                 <?php } ?>
                    </figure>
                </div>
            </div>
            <div class="col pl-3 text-left align-self-center">
                <h6 class="mb-1"><?php echo $Name; ?></h6>
            </div>
        <?php } else{?>
             <div class="col-auto">
                
            </div>
            <div class="col pl-3 text-left align-self-center">
                <h6 class="mb-1"><?php echo $Proj_Title; ?></h6>
                <p class="small text-default-secondary"></p>
            </div>
        <?php } ?>
        </div>
         <div class="menu-container">
              
            <ul class="nav nav-pills flex-column ">
                <li class="nav-item">
                    <a class="nav-link active" href="home.php">
                        <div>
                            <span class="material-icons icon">home</span>
                            Home
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>

                 <li class="nav-item">
                    <a class="nav-link" href="#">
                        <div>
                            <span class="material-icons icon">shopping_cart</span>
                            Masters
                        </div>
                      
                    </a>
                </li>
               

            </ul>
            <?php if(isset($_SESSION['User'])){?>
            <div class="text-center">
                <a href="JavaScript:Void(0);" onclick="logout()" class="btn btn-outline-danger  rounded my-3 mx-auto">Sign out</a>
            </div>
             <?php } ?>
        </div>
    </div>