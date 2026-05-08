<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'getAvailProdStock'){
    $id = $_POST['id'];
    $sql = "SELECT sum(creditqty)-sum(debitqty) AS balqty FROM (SELECT (case when crdr='dr' then sum(Qty) else '0' end) as debitqty,(case when crdr='cr' then sum(Qty) else '0' end) as creditqty FROM `tbl_product_stocks` WHERE ProdId='$id' AND Roll='Company-Stock' GROUP by CrDr) as a";
    $row = getRecord($sql);
    if($row['balqty'] == ''){
        $balqty = 0;
    }
    else{
        $balqty = $row['balqty'];
    }
    echo $balqty;
}

if($_POST['action'] == 'getPrice'){
    $id = $_POST['id'];
    $sql = "SELECT * FROM products WHERE id='$id'";
    $row = getRecord($sql);
    echo json_encode(array('ProdPrice'=>$row['ProdPrice'],'CgstPer'=>$row['CgstPer'],'SgstPer'=>$row['SgstPer'],'IgstPer'=>$row['IgstPer'],'OrgPrice'=>$row['MinPrice']));
}

if($_POST['action'] == 'getMore'){
    $i = $_POST['id']; ?>
     <div class="form-row" id="row<?php echo $i;?>">
                                                            <div class="form-group col-md-3">
                                                                <label class="form-label">Product </label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="ProdId[]" id="ProdId<?php echo $i;?>" onchange="getPrice(this.value,document.getElementById('srno<?php echo $i;?>').value)">
                                                                    <option selected value="" disabled>...</option>
                                                                    <?php
                                                                    $sql4 = "SELECT * FROM products WHERE Status=1 AND ProdFor IN (1,3)";
                                                                    $row4 = getList($sql4);
                                                                    foreach ($row4 as $result) {
                                                                    ?>
                                                                        <option <?php if ($row7["TravelId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                           

                                                          
                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">Price </label>
                                                                <input type="text" name="Price[]" id="Price<?php echo $i;?>" class="form-control" placeholder="e.g.,S12" value="" autocomplete="off"
                                                               readonly>
                                                            </div>
                                                            <input type="hidden" id="OrgPrice1">
                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">Avail Stock </label>
                                                                <input type="text" name="AvailStock[]" id="AvailStock<?php echo $i;?>" class="form-control" placeholder="e.g.,S12" value="" autocomplete="off"
                                                                readonly>
                                                            </div>

                                                             <div class="form-group col-md-1">
                                                                <label class="form-label">Qty </label>
                                                                <input type="text" name="Qty[]" id="Qty<?php echo $i;?>" class="form-control" placeholder="e.g.,S12" value="1" autocomplete="off"
                                                                oninput="TotAmount(document.getElementById('Price<?php echo $i;?>').value,
                                                                document.getElementById('Qty<?php echo $i;?>').value,
                                                                document.getElementById('SgstPer<?php echo $i;?>').value,
                                                                document.getElementById('CgstPer<?php echo $i;?>').value,
                                                                document.getElementById('IgstPer<?php echo $i;?>').value,
                                                                document.getElementById('srno<?php echo $i;?>').value)">
                                                            </div>

                                                          
                                                             <div class="form-group col-md-1">
                                                                <label class="form-label">CGST %</label>
                                                                <input type="text" name="CgstPer[]" id="CgstPer<?php echo $i;?>" class="form-control" placeholder="" value="9" autocomplete="off" 
                                                                oninput="TotAmount(document.getElementById('Price<?php echo $i;?>').value,
                                                                document.getElementById('Qty<?php echo $i;?>').value,
                                                                document.getElementById('SgstPer<?php echo $i;?>').value,
                                                                document.getElementById('CgstPer<?php echo $i;?>').value,
                                                                document.getElementById('IgstPer<?php echo $i;?>').value,
                                                                document.getElementById('srno<?php echo $i;?>').value)">
                                                            </div>

                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">SGST %</label>
                                                                <input type="text" name="SgstPer[]" id="SgstPer<?php echo $i;?>" class="form-control" placeholder="" value="9" autocomplete="off" 
                                                                oninput="TotAmount(document.getElementById('Price<?php echo $i;?>').value,
                                                                document.getElementById('Qty<?php echo $i;?>').value,
                                                                document.getElementById('SgstPer<?php echo $i;?>').value,
                                                                document.getElementById('CgstPer<?php echo $i;?>').value,
                                                                document.getElementById('IgstPer<?php echo $i;?>').value,
                                                                document.getElementById('srno<?php echo $i;?>').value)">
                                                            </div>

                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">IGST %</label>
                                                                <input type="text" name="IgstPer[]" id="IgstPer<?php echo $i;?>" class="form-control" placeholder="" value="9" autocomplete="off" 
                                                                oninput="TotAmount(document.getElementById('Price<?php echo $i;?>').value,
                                                                document.getElementById('Qty<?php echo $i;?>').value,
                                                                document.getElementById('SgstPer<?php echo $i;?>').value,
                                                                document.getElementById('CgstPer<?php echo $i;?>').value,
                                                                document.getElementById('IgstPer<?php echo $i;?>').value,
                                                                document.getElementById('srno<?php echo $i;?>').value)">
                                                            </div>


                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">GST Amt</label>
                                                                <input type="text" name="GstAmt[]" id="GstAmt<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                                            </div> 

                                                            <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i;?>" value="<?php echo $i;?>">
                                                            <input type="hidden" class="form-control" name="rncnt" id="rncnt" value="1">
                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" name="Total[]" id="Total<?php echo $i;?>" class="form-control txt" placeholder="e.g.,5000" value="" autocomplete="off" required readonly>
                                                                    <div class="clearfix"></div>
                                                                    <span class="input-group-append">
                                                                       <button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="feather icon-x"></i></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
<?php } 


if($_POST['action'] == 'getPriceCust'){
    $id = $_POST['id'];
    $sql = "SELECT * FROM tbl_cust_products WHERE id='$id'";
    $row = getRecord($sql);
    echo json_encode(array('ProdPrice'=>$row['ProdPrice'],'CgstPer'=>$row['CgstPer'],'SgstPer'=>$row['SgstPer'],'IgstPer'=>$row['IgstPer'],'OrgPrice'=>$row['MinPrice']));
}

if($_POST['action'] == 'getMoreCust'){
    $i = $_POST['id']; ?>
     <div class="form-row" id="row<?php echo $i;?>">
                                                            <div class="form-group col-md-3">
                                                                <label class="form-label">Product </label>
                                                                <select class="form-control" style="width: 100%" data-allow-clear="true" name="ProdId[]" id="ProdId<?php echo $i;?>" onchange="getPrice(this.value,document.getElementById('srno<?php echo $i;?>').value)">
                                                                    <option selected value="" disabled>...</option>
                                                                    <?php
                                                                    $sql4 = "SELECT * FROM tbl_cust_products WHERE Status=1 AND CreatedBy IN ($user_id,0)";
                                                                    $row4 = getList($sql4);
                                                                    foreach ($row4 as $result) {
                                                                        
                                                                    ?>
                                                                        <option <?php if ($row7["TravelId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
                                                                    <?php }  ?>
                                                                </select>
                                                            </div>

                                                           

                                                          
                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">Price </label>
                                                                <input type="text" name="Price[]" id="Price<?php echo $i;?>" class="form-control" placeholder="e.g.,S12" value="" autocomplete="off"
                                                               readonly>
                                                            </div>
                                                            <input type="hidden" id="OrgPrice1">
                                                          <!--  <div class="form-group col-md-1">
                                                                <label class="form-label">Avail Stock </label>
                                                                <input type="text" name="AvailStock[]" id="AvailStock<?php echo $i;?>" class="form-control" placeholder="e.g.,S12" value="" autocomplete="off"
                                                                readonly>
                                                            </div>-->

                                                             <div class="form-group col-md-1">
                                                                <label class="form-label">Qty </label>
                                                                <input type="text" name="Qty[]" id="Qty<?php echo $i;?>" class="form-control" placeholder="e.g.,S12" value="1" autocomplete="off"
                                                                oninput="TotAmount(document.getElementById('Price<?php echo $i;?>').value,
                                                                document.getElementById('Qty<?php echo $i;?>').value,
                                                                document.getElementById('SgstPer<?php echo $i;?>').value,
                                                                document.getElementById('CgstPer<?php echo $i;?>').value,
                                                                document.getElementById('IgstPer<?php echo $i;?>').value,
                                                                document.getElementById('srno<?php echo $i;?>').value)">
                                                            </div>

                                                          
                                                             <div class="form-group col-md-1">
                                                                <label class="form-label">CGST %</label>
                                                                <input type="text" name="CgstPer[]" id="CgstPer<?php echo $i;?>" class="form-control" placeholder="" value="9" autocomplete="off" 
                                                                oninput="TotAmount(document.getElementById('Price<?php echo $i;?>').value,
                                                                document.getElementById('Qty<?php echo $i;?>').value,
                                                                document.getElementById('SgstPer<?php echo $i;?>').value,
                                                                document.getElementById('CgstPer<?php echo $i;?>').value,
                                                                document.getElementById('IgstPer<?php echo $i;?>').value,
                                                                document.getElementById('srno<?php echo $i;?>').value)">
                                                            </div>

                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">SGST %</label>
                                                                <input type="text" name="SgstPer[]" id="SgstPer<?php echo $i;?>" class="form-control" placeholder="" value="9" autocomplete="off" 
                                                                oninput="TotAmount(document.getElementById('Price<?php echo $i;?>').value,
                                                                document.getElementById('Qty<?php echo $i;?>').value,
                                                                document.getElementById('SgstPer<?php echo $i;?>').value,
                                                                document.getElementById('CgstPer<?php echo $i;?>').value,
                                                                document.getElementById('IgstPer<?php echo $i;?>').value,
                                                                document.getElementById('srno<?php echo $i;?>').value)">
                                                            </div>

                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">IGST %</label>
                                                                <input type="text" name="IgstPer[]" id="IgstPer<?php echo $i;?>" class="form-control" placeholder="" value="9" autocomplete="off" 
                                                                oninput="TotAmount(document.getElementById('Price<?php echo $i;?>').value,
                                                                document.getElementById('Qty<?php echo $i;?>').value,
                                                                document.getElementById('SgstPer<?php echo $i;?>').value,
                                                                document.getElementById('CgstPer<?php echo $i;?>').value,
                                                                document.getElementById('IgstPer<?php echo $i;?>').value,
                                                                document.getElementById('srno<?php echo $i;?>').value)">
                                                            </div>


 <input type="hidden" name="CgstAmt[]" id="CgstAmt<?php echo $i; ?>" value="">
 <input type="hidden" name="SgstAmt[]" id="SgstAmt<?php echo $i; ?>" value="">
 <input type="hidden" name="IgstAmt[]" id="IgstAmt<?php echo $i; ?>" value="">
 
                                                            <div class="form-group col-md-1">
                                                                <label class="form-label">GST Amt</label>
                                                                <input type="text" name="GstAmt[]" id="GstAmt<?php echo $i;?>" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                                            </div> 

                                                            <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i;?>" value="<?php echo $i;?>">
                                                            <input type="hidden" class="form-control" name="rncnt" id="rncnt" value="1">
                                                            <div class="form-group col-md-2">
                                                                <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" name="Total[]" id="Total<?php echo $i;?>" class="form-control txt" placeholder="e.g.,5000" value="" autocomplete="off" required readonly>
                                                                    <div class="clearfix"></div>
                                                                    <span class="input-group-append">
                                                                       <button class="btn btn-danger btn_remove" type="button" id="<?php echo $i;?>"><i class="feather icon-x"></i></button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
<?php } 



if($_POST['action'] == 'printSetting'){
    $title = str_replace(" ","#",$_POST['title']);
    $address = str_replace(" ","#",$_POST['address']);
    $mobile = str_replace(" ","#",$_POST['mobile']);
    $gstin = str_replace(" ","#",$_POST['gstin']);
    $terms_condition = str_replace(" ","#",$_POST['terms_condition']);
    $bottom_title = str_replace(" ","#",$_POST['bottom_title']);
    
    $sql = "SELECT * FROM tbl_customer_invoice WHERE id='".$_POST['id']."'";
    $row = getRecord($sql);
    $CustName = str_replace(" ","#",$row['CustName']);
        $emparray = array();
            $sql12 = "SELECT * FROM tbl_customer_invoice_details WHERE InvId='".$row['id']."'";
            $row12 = getList($sql12);
            foreach($row12 as $result12){
                $sql13 = "SELECT ProductName FROM  tbl_cust_products WHERE id='".$result12['ProdId']."'";
                $row13 = getRecord($sql13);
                $TotQty+=$result12['Qty'];
                $TotPrice+=$result12['Price'];
                $TotGst+=$result12['GstAmt'];
                $Product_name = str_replace(" ","#",$row13['ProductName']);
                 $emparray[] = array('item'=>$Product_name,'rate'=>$result12['Price'],'quantity'=>$result12['Qty'],'amount'=>$result12['Total']);
            }
    $dados2 =  json_encode(array('productList' => $emparray));
    $date = date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate'])));
    $dados1 = json_encode(array('title'=>$title,'address'=>$address,'mobile'=>$mobile,'gstin'=>$gstin,'customer_name'=>$CustName,'customer_phone_no'=>$row['CellNo'],'receipt_title'=>'Retail#Receipt','invoice_no'=>$row['InvoiceNo'],'date'=>$date,'sub_total'=>$row['SubTotal'],'discount'=>$row['Discount'],'service_charge'=>0,'gst'=>$TotGst,'total_bill'=>$row['NetAmount'],'total_payable'=>$row['NetAmount'],'terms_condition'=>$terms_condition,'bottom_title'=>$bottom_title)); 
    $invoice_data =  json_encode(array_merge(json_decode($dados1, true),json_decode($dados2, true)));
     
    echo $invoice_data;
}



if($_POST['action'] == 'getCashAmount'){
    $FromDate = $_POST['FromDate'];
    $ToDate = $_POST['ToDate'];
    $FrId = $_POST['FrId'];
    $sql = "SELECT SUM(NetAmount) AS TotalCashAmount FROM `tbl_customer_invoice` WHERE InvoiceDate>='$FromDate' AND InvoiceDate<='$ToDate' AND PayType='Cash' AND FrId=$FrId";
    $row = getRecord($sql);
    
    $sql2 = "SELECT SUM(Amount) AS TranferAmt FROM tbl_cash_book WHERE FrId='$FrId' AND ToDate>='$FromDate' AND FromDate<='$ToDate'";
    $row2 = getRecord($sql2);
    //$TranferAmt = $row2['TranferAmt'];
    echo $row['TotalCashAmount'] - $row2['TranferAmt'];
}
?>