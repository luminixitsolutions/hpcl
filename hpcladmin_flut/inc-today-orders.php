 <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">




<div class="form-group col-lg-3">
                                            <label class="form-label">Payment Type</label>
                                            <select class="form-control" id="PayType" name="PayType">
                                                <option selected=""  value="all">All</option>
                                                <option value="Cash" <?php if ($_POST['PayType'] == 'Cash') { ?> selected <?php } ?>>Cash</option>
                                                <option <?php if ($_POST['PayType'] == 'Phone Pay') { ?> selected <?php } ?> value="Phone Pay">Phone Pay</option>
                                                <option <?php if ($_POST['PayType'] == 'Google Pay') { ?> selected <?php } ?> value="UPI">Google Pay</option>
                                                <option <?php if ($_POST['PayType'] == 'Paytm') { ?> selected <?php } ?> value="Paytm">Paytm</option>
                                                 <option <?php if ($_POST['PayType'] == 'Other UPI') { ?> selected <?php } ?> value="Other UPI">Other UPI</option>
                                                 
                                                  <option <?php if ($_POST['PayType'] == 'Borrowing') { ?> selected <?php } ?> value="Borrowing">Borrowing / उधार</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
                                        
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="button" id="todaysearch" class="btn btn-primary btn-finish" onclick="todayOrderLists()">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="button" id="refreshbtn" class="btn btn-success btn-finish" onclick="todayOrderLists()">Refresh</button>
</div>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
<div class="card-datatable table-responsive" id="custresult2">

</div>