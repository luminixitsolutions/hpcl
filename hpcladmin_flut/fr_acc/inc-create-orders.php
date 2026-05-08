<style type="text/css">
    .mr_5{
        margin-right: 3rem !important;
    }
    .tabbable .nav-tabs {
   overflow-x: auto;
   overflow-y:hidden;
   flex-wrap: nowrap;
}
.tabbable .nav-tabs .nav-link {
  white-space: nowrap;
}

</style>
<!--<div class="navbar-nav " style="padding-left: 30px;">
                           
                            <label class="nav-item navbar-text navbar-search-box p-0 active">
                                <i class="feather icon-search navbar-icon align-middle"></i>
                                <span class="navbar-search-input pl-2">
                                    <input type="text" class="form-control navbar-text mx-2" placeholder="Search..." data-toggle="modal" data-target="#modals-default" id="add_button">
                                </span>
                            </label>
                        </div>-->
                        
                        <div class="modal fade insert_frm" id="modals-default">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Add 
<span class="font-weight-light">Product</span>
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body">
  <input type="hidden" name="action" id="action" value="Add">
   <input type="hidden" name="id" id="id" /> 
  <div class="form-row">
<div class="form-group col">
<label class="form-label">Barcode Scan No <span class="text-danger">*</span></label>
<input type="text" name="BarcodeNo" class="form-control" id="BarcodeNo" placeholder="" value="" required autofocus oninput="getProdDetails()">
<div class="clearfix"></div>
</div>
</div>

 <div class="form-row">
<div class="form-group col">
<label class="form-label">Product Name <span class="text-danger">*</span></label>
<input type="text" name="ProdName" class="form-control" id="ProdName" placeholder="" value="" readonly>
<div class="clearfix"></div>
</div>
</div>

 <div class="form-row">
<div class="form-group col-lg-6">
<label class="form-label">Product Price <span class="text-danger">*</span></label>
<input type="text" name="Price" class="form-control" id="Price" placeholder="" value="" readonly>
<div class="clearfix"></div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Qty <span class="text-danger">*</span></label>
<input type="number" name="Qty" class="form-control" id="Qty" placeholder="" value="1" required min="1">
<div class="clearfix"></div>
</div>
</div>
<input type="hidden" id="ProdId">
<input type="hidden" id="ProdCode">

</div>
<div class="modal-footer">

<button type="button" class="btn btn-danger" id="addtocart" name="submit" onclick="addCart3()">Add To Cart</button>
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</form>
</div>
</div>


<div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container-fluid flex-grow-1 " style="padding-top:10px;padding-right: 5px;padding-left: 5px;">
                       
                        <div class="row">
                            <div class="col-md-8">
                                 <div class="">
                        <div class="tabbable">
                        <ul id="gallery-filter" class="nav nav-tabs tabs-alt mb-2">
                            <li class="nav-item">
                                <a class="nav-link active" href="#all">All</a>
                            </li> 
                            <?php 
                                $i=1;
                                $sql = "SELECT * FROM tbl_cust_category WHERE Status=1 AND CreatedBy IN ($BillSoftFrId,0) ORDER BY srno asc";
                                $row = getList($sql);
                                foreach($row as $result){
                                     $sql2 = "SELECT * FROM tbl_cust_products WHERE CatId='".$result['id']."' AND CreatedBy IN ($BillSoftFrId)";
                                    $rncnt2 = getRow($sql2);
                                    if($rncnt2 > 0){
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#cat<?php echo $result['id'];?>" style="color:#fff;"><?php echo $result['Name'];?></a>
                            </li>
                            <?php }$i++;} ?>
                            
                        </ul>
                        </div>
                          <div class="form-row">
<div class="form-group col">
<input type="text" name="SearchProdName" class="form-control" id="SearchProdName" placeholder="Search Product" value="" autofocus oninput="searchProduct()">
</div>
</div>
                        <!-- Lightbox template -->
                        <div id="gallery-lightbox" class="blueimp-gallery blueimp-gallery-controls">
                            <div class="slides"></div>
                            <h3 class="title"></h3>
                            <a class="prev">‹</a>
                            <a class="next">›</a>
                            <a class="close">×</a>
                            <a class="play-pause"></a>
                            <ol class="indicator"></ol>
                        </div>
                        
                        <div id="gallery-thumbnails" class="row form-row" style="height: 100px;">
                        <div class="gallery-sizer col-sm-4 col-md-4 col-3 col-xl-3 position-absolute"></div>
                        
                            <?php 
                                $i=1;
                                /*$sql = "SELECT id FROM tbl_cust_category WHERE Status=1 ORDER BY srno asc LIMIT 1";
                                $row = getRecord($sql);*/
                                $sql = "SELECT * FROM tbl_cust_category WHERE Status=1 AND CreatedBy IN (0,$BillSoftFrId) ORDER BY srno asc";
                                $row = getList($sql);
                                foreach($row as $result){
                                    $sql2 = "SELECT * FROM tbl_cust_products WHERE CatId='".$result['id']."' AND CreatedBy IN ($BillSoftFrId) ORDER BY SrNo asc ";
                                    $row2 = getList($sql2);
                                    foreach($row2 as $result2){
                                        $code = $result2['code'];
                                        $sessqty2 =  $_SESSION["cart_item"][$code]['quantity'];
                                        if($sessqty2 == ''){
                                            $sessqty = 0;
                                        }
                                        else{
                                            $sessqty = $sessqty2;
                                        }
                                        
                                        $sql2 = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when Status='Dr' then sum(Qty) else '0' end) as debitqty,(case when Status='Cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_cust_prod_stock` WHERE ProdId='".$result2['id']."' GROUP by Status) as a";
                                    	$row2 = getRecord($sql2);
                                    	if($row2['balqty'] > 0){
                                    	$stockqty = $row2['balqty'];
                                    	}
                                    	else{
                                    	    $stockqty = 0;
                                    	}
                            ?>
                            <div align="center" class="gallery-thumbnail col-sm-4 col-md-4  col-4 col-xl-3 " data-tag="cat<?php echo $result['id'];?>" style="border:1px solid #e6e6e6;margine-right:10px;padding-top: 5px;border-radius:10px;" >
                                <a href="javascript:void(0)" onclick="addCart(<?php echo $result2['id'];?>)">
                                   <!-- <span class="img-thumbnail-overlay bg-dark opacity-25"></span>
                                    <span class="img-thumbnail-content display-4 text-white">
                                        <i class="ion ion-ios-search"></i>
                                    </span>-->
                                    <?php if($result2['Photo']==''){?>
                                     <!--<img src="no_image.jpg" class="img-fluid" alt="images" style="width:163px;110px;">-->
                                    <?php } else { ?>
                                    <!--<img src="../uploads/<?php echo $result2['Photo'];?>" class="img-fluid" alt="images" style="width:163px;110px;">-->
                                    <?php } ?>
                                </a>
                                <!--<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm">
                                                                    <button type="button" id="decrease"  onclick="changeMinus(<?php echo $result2["id"].",'".$result2["code"]."'"; ?>);" class="btn btn-secondary">-</button>
                                                                    <input class="wid-65 text-center" type="number" id="qntno<?php echo $result2["code"];?>" value="<?php echo $sessqty;?>" >
                                                                    <button type="button" id="increase" onclick="changePlus(<?php echo $result2["id"].",'".$result2["code"]."'"; ?>);" class="btn btn-secondary">+</button>
                                                                </div>-->
                                <a href="javascript:void(0)" onclick="addCart(<?php echo $result2['id'];?>)">
                                    <div align="center" style="padding:6px;">
                                <strong style="text-align:center;font-size: 12px;letter-spacing: 0.5px;color:#000;padding:5px;"><?php echo $result2['ProductName'];?></strong><br>
                                <span style="font-size: 10px;color: black;">Stock : <?php echo $stockqty;?></span><br>
                                <strong style="font-size:17px;color:#f06721;">&#8377; <?php echo number_format($result2['MinPrice'],2); ?></strong>
                                <!--<div class="btn-group btn-group-sm" role="group" aria-label="button groups sm" style="padding-bottom: 10px;">
                                                                    <button style="background-color:#3d8b28;" type="button" id="decrease" onclick="decreaseValue(<?php echo $result2["id"];?>)" class="btn btn-secondary">-</button>
                                                                    <input class="wid-35 text-center" type="text" id="qntno<?php echo $result2["id"];?>" value="1">
                                                                    <button style="background-color:#3d8b28;"  type="button" id="increase" onclick="increaseValue(<?php echo $result2["id"];?>)" class="btn btn-secondary">+</button>
                                                                </div><br>-->
                                                                
                             <input class="wid-35 text-center qntno" type="hidden" id="qntno<?php echo $result2["code"];?>" value="<?php echo $sessqty;?>">                        
                               <!--  <button type="button" id="add-cart<?php echo $result2['id'];?>" class="btn btn-primary btn-finish" onclick="addCart(<?php echo $result2['id'];?>)">Add Cart</button>-->
                                </div> </a>
                            </div>

                            
                            <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">

   <input type="hidden" id="pid<?php echo $result2["id"];?>" value="<?php echo $result2["id"];?>">
  
    <input type="hidden" id="code<?php echo $result2["id"];?>" value="<?php echo $result2['code'];?>">
     <input type="hidden" id="prd_price<?php echo $result2["id"];?>" value="<?php echo $result2['MinPrice'];?>"> 

                            <?php } $i++;} ?>
                          
                        </div>

                    </div>
                    <!-- [ content ] End -->            
                                           
                                       
                                    </div>
                                
                            <div class="col-md-4  d-none d-md-block">
                                <div class="card">
                                    <div class="card-header" style="padding: 2px 10px;">
                                       
                                        <h5 style="line-height: 2.1;">Order Summary
                                         <button type="button" id="saveprint2" class="btn btn-success  mt-md-0 mt-2" onclick="savePrint()" style="float:right;" disabled>
                                                                Save & Print
                                                            </button></h5>
                                    </div>
                                    <ul class="list-group list-group-flush" id="showcart">
                                        
                                       
                                    </ul>
                                    <div class="card-body py-2">
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0 w-auto table-sm float-right text-right">
                                                <tbody id="showtotal">
                                                    
                                                </tbody>
                                                <tbody id="showdiscount">
                                                    
                                                </tbody>
                                                <tbody id="showpkgamt">
                                                    
                                                </tbody>
                                            </table>
                                            
                                            
                                        </div>
                                    </div>
                                    
                                    
                                 <div class="row" style="padding-left: 10px;">
                                    <div class="form-group col-lg-6 col-6">
                                            <label class="form-label">Contact No </label>
                                            <input type="number" name="CellNo" id="CellNo" class="form-control" placeholder="" value="<?php echo $row7["CellNo"]; ?>" autocomplete="off" oninput="getUserDetails()">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-lg-6 col-6">
                                            <label class="form-label">Customer Name </label>
                                            <input type="text" name="CustName" id="CustName" class="form-control" placeholder="" value="<?php echo $row7["CustName"]; ?>" autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        <input type="hidden" name="CustId" id="CustId">
                                        <input type="hidden" name="AccCode" id="AccCode">
                                          </div>
                                        <div class="row" style="padding-left: 10px;">
                                        <div class="form-group col-lg-6 col-6">
                                            <label class="form-label">Discount</label>
                                            <select class="form-control" id="DiscPer" name="DiscPer" onchange="calDiscount(this.value)">
                                               
                                                <option selected="" value="0">No Discount</option>
                                                <?php 
                                                $sql44 = "SELECT * FROM tbl_billsoft_discount";
                                                $row44 = getList($sql44);
                                                foreach($row44 as $result){?>
                                                <option value="<?php echo $result['Percentage'];?>"><?php echo $result['Percentage'];?>%</option>
                                                <?php } ?>
                                               
                                                 
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-lg-6 col-6">
                                            <label class="form-label">Payment Type</label>
                                            <select class="form-control" id="PayType" name="PayType">
                                                <option selected="" disabled="" value="">Select Payment Type</option>
                                                <option selected="" value="Cash">Cash</option>
                                                <option <?php if ($row7['PayType2'] == 'Phone Pay') { ?> selected <?php } ?> value="Phone Pay">Phone Pay</option>
                                                <option <?php if ($row7['PayType2'] == 'Google Pay') { ?> selected <?php } ?> value="UPI">Google Pay</option>
                                                <option <?php if ($row7['PayType2'] == 'Paytm') { ?> selected <?php } ?> value="Paytm">Paytm</option>
                                                 <option <?php if ($row7['PayType2'] == 'Other UPI') { ?> selected <?php } ?> value="Other UPI">Other UPI</option>
                                                 
                                                  <option <?php if ($row7['PayType2'] == 'Borrowing') { ?> selected <?php } ?> value="Borrowing">Credit / उधार</option>
                                                  
                                                    <option <?php if ($row7['PayType2'] == 'Zomato') { ?> selected <?php } ?> value="Zomato">Zomato</option>
                                                 
                                                 
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        </div>


 
                   <div class="row" style="padding-left: 10px;">                      
 <div class="form-group col-lg-6 col-6" style="padding-top: 10px;">
                                 <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="Featured" value="1" onclick="featured()">
                                    <span class="custom-control-label">Pending</span>
                                </label>
                                </div>
                                
                                 <div class="form-group col-lg-6 col-6" style="padding-top: 10px;">
                                 <label class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="Print" value="1" onclick="Print()" checked>
                                    <span class="custom-control-label">Print</span>
                                </label>
                                </div>
                                </div>
                                        
                                      <!-- <div class="form-group col-lg-12 showform">
                                            <label class="form-label">Package </label>
                                            <select class="form-control" style="width: 100%" data-allow-clear="true" name="PkgId" id="PkgId">
                                               <option value="0">No Package</option>
                                                <?php
                                                $sql4 = "SELECT * FROM tbl_packages WHERE Status=1";
                                                $row4 = getList($sql4);
                                                foreach ($row4 as $result) {
                                                    if($result['Period'] == 1){
                                                        $Duration = $result['Duration']." Month";
                                                    }
                                                    else{
                                                      $Duration = $result['Duration']." Year";  
                                                    }
                                                ?>
                                                    <option <?php if ($row7["PkgId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Name']." ( ₹".$result['Amount']." ) - ".$Duration; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <input type="hidden" value="" id="PackageId" name="PackageId">
                                          <div class="form-group col-lg-12 showform">
                                            <label class="form-label">Amount </label>
                                            <input type="text" name="PkgAmt" id="PkgAmt" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                         <div class="form-group col-lg-12 showform">
                                            <label class="form-label">Prime Discount % </label>
                                            <input type="text" name="PkgDiscount" id="PkgDiscount" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-lg-12 showform">
                                            <label class="form-label">Valid Upto  </label>
                                            <input type="date" name="PkgValidity" id="PkgValidity" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>-->
                                        
                                      
                                      <div style="padding-top:10px;"></div>   
                        <button type="button" id="saveprint" class="btn btn-success  mt-md-0 mt-2" onclick="savePrint()" disabled>
                                                                Save & Print
                                                            </button>
                                       <div style="padding-top:10px;"></div>   
                                 <!--   <a  href="add-customer-invoice.php" class="btn btn-danger  mt-md-0 mt-2">
                                                                Continue to Order
                                                            </a>-->
                                                            
                                                            
                                                             </div>
                                                             
                                                             
                               <div class="card">
                                    <div class="card-header">
                                        <h5>Pending Orders</h5>  
                                    </div>
                                    <div class="row" id="showpendingorders">
                                       
                                    </div>
                                     
                                </div>    
                                    
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Recent Orders</h5>  
                                    </div>
                                    <div class="row" id="showrecentorders">
                                       
                                    </div>
                                     
                                </div>    
                                    
                                    
                            </div>
                        </div>
                    </div>




</div>