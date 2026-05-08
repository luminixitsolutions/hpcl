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
            <?php 
                $sql33 = "SELECT * FROM `tbl_users_bill` WHERE Roll=5 AND id='$FranchiseId'";
                $row33 = getList($sql33);
                foreach($row33 as $result){
            ?>
        <div class="card mb-2">
            <a href="home.php?frid=<?php echo $result['id'];?>">
                    <div class="card-body px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item">
                                <div class="row ">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-bold mb-1"><?php echo $result['ShopName'];?></h6>
                                        <p class="small text-secondary">Branch : <?php echo $result['Address'];?></p>
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
            

                

            </div>

    </div>