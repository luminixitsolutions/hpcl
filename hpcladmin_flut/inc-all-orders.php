 <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding: 5px;padding-left: 20px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">


 <div class="form-group col-lg-2">
                                            <label class="form-label">Select Report</label>
                                            <select class="form-control" id="ReportType" name="ReportType" onchange="showdate(this.value)">
                                               
                                                <option value="Today" <?php if ($_POST['ReportType'] == 'Today') { ?> selected <?php } ?>>Today</option>
                                                <option <?php if ($_POST['ReportType'] == 'Yesterday') { ?> selected <?php } ?> value="Yesterday">Yesterday</option>
                                                <option <?php if ($_POST['ReportType'] == 'Week') { ?> selected <?php } ?> value="Week">This Week</option>
                                                <option <?php if ($_POST['ReportType'] == 'Month') { ?> selected <?php } ?> value="Month">This Month</option>
                                                 <option <?php if ($_POST['ReportType'] == 'Custom') { ?> selected <?php } ?> value="Custom">Custom</option>
                                               
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

<div class="form-group col-md-2 customfmdt" <?php if ($_POST['ReportType'] == 'Custom') { ?> style="display:block;" <?php } else {?> style="display:none;" <?php } ?>>
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2 customtodt" <?php if ($_POST['ReportType'] == 'Custom') { ?> style="display:block;" <?php } else {?> style="display:none;" <?php } ?>>
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>

<div class="form-group col-lg-2">
                                            <label class="form-label">Payment Type</label>
                                            <select class="form-control" id="PayType2" name="PayType">
                                                <option selected=""  value="all">All</option>
                                                <option value="Cash" <?php if ($_REQUEST['PayType'] == 'Cash') { ?> selected <?php } ?>>Cash</option>
                                                <option <?php if ($_REQUEST['PayType'] == 'Phone Pay') { ?> selected <?php } ?> value="Phone Pay">Phone Pay</option>
                                                <option <?php if ($_REQUEST['PayType'] == 'Google Pay') { ?> selected <?php } ?> value="UPI">Google Pay</option>
                                                <option <?php if ($_REQUEST['PayType'] == 'Paytm') { ?> selected <?php } ?> value="Paytm">Paytm</option>
                                                 <option <?php if ($_REQUEST['PayType'] == 'Other UPI') { ?> selected <?php } ?> value="Other UPI">Other UPI</option>
                                                 
                                                   <option <?php if ($_REQUEST['PayType'] == 'Borrowing') { ?> selected <?php } ?> value="Borrowing">Borrowing / उधार</option>
                                                   
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>
   
  
                                        
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="button" name="submit" class="btn btn-primary btn-finish" onclick="allOrdersLists()">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="button" id="refreshbtn" class="btn btn-success btn-finish" onclick="allOrdersLists()">Refresh</button>
</div>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
<div class="card-datatable table-responsive" id="custresult">

</div>