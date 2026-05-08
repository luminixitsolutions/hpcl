<div class="color-picker2" style="background-color:#009eff ">
        <div class="row">
            <div class="col text-left">
                <h6 class="subtitle " style="color:#fff;">Calendar </h6>
            </div>
            <div class="col-auto">
                <button class="btn btn-link text-secondary btn-round colorsettings2" onclick="closeCalender()"><span style="color:#fff;" class="material-icons">cancel</span></button>
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
            <div class="card mb-2">
            <a href="javascript:void(0)" onclick="searchResult('<?php echo $url;?>','today');">
                    <div class="card-body px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item">
                                <div class="row ">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-bold mb-1">Today</h6>
                                     
                                    </div>
                                    <div class="col-auto">
                                        <span class="arrow material-icons">chevron_right</span>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div></a>
                </div>
          
          <div class="card mb-2">
            <a href="javascript:void(0)" onclick="searchResult('<?php echo $url;?>','yesterday');">
                    <div class="card-body px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item">
                                <div class="row ">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-bold mb-1">Yesterday</h6>
                                     
                                    </div>
                                    <div class="col-auto">
                                        <span class="arrow material-icons">chevron_right</span>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div></a>
                </div>

                <div class="card mb-2">
            <a href="javascript:void(0)" onclick="searchResult('<?php echo $url;?>','week');">
                    <div class="card-body px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item">
                                <div class="row ">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-bold mb-1">This Week</h6>
                                     
                                    </div>
                                    <div class="col-auto">
                                        <span class="arrow material-icons">chevron_right</span>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div></a>
                </div>

                <div class="card mb-2">
            <a href="javascript:void(0)" onclick="searchResult('<?php echo $url;?>','month');">
                    <div class="card-body px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item">
                                <div class="row ">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-bold mb-1">This Month</h6>
                                     
                                    </div>
                                    <div class="col-auto">
                                        <span class="arrow material-icons">chevron_right</span>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div></a>
                </div>
          
          <div class="card mb-2">
            <a href="javascript:void(0)" onclick="showDateRange('Custom')">
                    <div class="card-body px-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item">
                                <div class="row ">
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-bold mb-1">Custom</h6>
                                     
                                    </div>
                                    <div class="col-auto">
                                        <span class="arrow material-icons">chevron_right</span>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div></a>
                </div>

                
                <div class="card customfmdt" <?php if ($_REQUEST['calendar'] == 'Custom') { ?> style="display:block;" <?php } else {?> style="display:none;" <?php } ?>>
                   <div class="card-body">
                        <div class="form-group float-label active">
                            <input type="date" class="form-control" name="FromDate" id="FromDate" value="<?php echo $_REQUEST['FromDate']; ?>">
                            <label class="form-control-label">From Date</label>                            
                        </div>

                        <div class="form-group float-label active">
                            <input type="date" class="form-control" name="ToDate" id="ToDate" value="<?php echo $_REQUEST['ToDate']; ?>">
                            <label class="form-control-label">To Date</label>                            
                        </div>

                         <div class="form-group float-label active">
                        <button type="button" class="btn btn-danger" id="submit" name="submit" onclick="searchDate('<?php echo $url;?>')">Search</button>
                        </div>
                    </div>

                    </div>

            </div>

    </div>

    <script>
        function searchResult(url,val){
            var genurl = url+"?calendar="+val;
            console.log(genurl);
            window.location.href=genurl;
        }
        function showDateRange(val){
            if(val == 'Custom'){
        $('.customfmdt').show();
    }
    else{
        $('.customfmdt').hide();
        $('#FromDate').val('');
        $('ToDate').val('');
    }
        }

        function searchDate(url){
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var genurl = url+"?calendar=Custom&FromDate="+FromDate+"&ToDate="+ToDate;
            console.log(genurl);
            window.location.href=genurl;
        }
    </script>
