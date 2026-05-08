<div class="color-picker" style="background-color:#009eff ">
        <div class="row">
            <div class="col text-left">
                <h6 class="subtitle " style="color:#fff;">All Shops </h6>
            </div>
            <div class="col-auto">
                <button class="btn btn-link text-secondary btn-round colorsettings2"><span style="color:#fff;" class="material-icons">cancel</span></button>
            </div>
        </div>     
 <style>
      div.scroll {
        width: 100%;
        height: 550px;
        overflow-x: hidden;
        overflow-y: auto;
        padding: 10px;
        background-color:#009eff ;
      }
    </style>
        <div  class="scroll">
            
           
                <div class="card mb-2" style="padding: 0px;">
           
                    <div class="card-body px-0" style="padding: 0px;">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item" style="background-color: antiquewhite;">
                                <div class="row ">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-bold mb-1">DEALERS:</h6>
                                        
                                    </div>
                                    
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                </div>
                
            <?php 
                
                    //$sql33 = "SELECT * FROM `tbl_users_bill` WHERE Roll=5 AND id IN (253,344,248,265,281,47,89,357,385,394)";
                    $sql33 = "SELECT * FROM `tbl_users_bill` WHERE Roll=5 AND OwnFranchise=1 AND Status=1";
                    if($Roll != 1){
                    if($CocoFranchiseAccess != '' || $CocoFranchiseAccess != 0){
                        $sql33.=" AND id IN ($CocoFranchiseAccess)";
                    }
                    }
                    //echo $sql33;
                $row33 = getList($sql33);
                foreach($row33 as $result){
            ?>
        <div class="card mb-2">
            <a href="home2.php?frid=<?php echo $result['id'];?>">
                    <div class="card-body px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item">
                                <div class="row ">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-bold mb-1"><?php echo $result['ShopName'];?></h6>
                                        <p class="small text-secondary">Branch : <?php echo substr($result['Address'],0,50);?>...</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="arrow material-icons">chevron_right</span>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div></a>
                </div>
            <?php } ?>
            


 <div class="card mb-2" style="padding: 0px;">
           
                    <div class="card-body px-0" style="padding: 0px;">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item" style="background-color: antiquewhite;">
                                <div class="row ">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-bold mb-1">NFB PARTNERS:</h6>
                                        
                                    </div>
                                    
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                </div>
                
            <?php 
                
                    //$sql33 = "SELECT * FROM `tbl_users_bill` WHERE Roll=5 AND id IN (253,344,248,265,281,47,89,357,385,394)";
                    $sql33 = "SELECT * FROM `tbl_users_bill` WHERE Roll=5 AND OwnFranchise=2 AND Status=1";
                    if($Roll != 1){
                    if($CocoFranchiseAccess != '' || $CocoFranchiseAccess != 0){
                        $sql33.=" AND id IN ($CocoFranchiseAccess)";
                    }
                    }
                    //echo $sql33;
                $row33 = getList($sql33);
                foreach($row33 as $result){
            ?>
        <div class="card mb-2">
            <a href="home2.php?frid=<?php echo $result['id'];?>">
                    <div class="card-body px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item">
                                <div class="row ">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-bold mb-1"><?php echo $result['ShopName'];?></h6>
                                        <p class="small text-secondary">Branch : <?php echo substr($result['Address'],0,50);?>...</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="arrow material-icons">chevron_right</span>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div></a>
                </div>
            <?php } ?>
            
            <!-- <div class="card mb-2">
            <a href="home2.php?frid=0">
                    <div class="card-body px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item">
                                <div class="row ">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-bold mb-1">MAHA CHAI PVT LTD</h6>
                                        <p class="small text-secondary">Branch : KHAMALA (Main)</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="arrow material-icons">chevron_right</span>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div></a>
                </div>-->
                
                
            <div style="text-align:center;">
                <a href="home.php" class="btn btn-sm btn-default rounded" style="background-color: white;color: black;">View All</a>
                </div>

            </div>

    </div>