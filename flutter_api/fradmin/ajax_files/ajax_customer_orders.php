<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users_bill WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
if($Roll == 5){
    $BillSoftFrId = $_SESSION['Admin']['id'];
}
else{
    $BillSoftFrId = $row77['BillSoftFrId'];
}
	$Options = explode(',',$row77['Options']);
if($_POST['action'] == 'allOrders'){?>
<table id="example" class="table mb-0 dataTable no-footer" role="grid" aria-describedby="report-table_info">
        <thead class="thead-light">
            <tr>
           
               
               <th>Action</th>
               <th>Order No</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Customer Name</th>
                <th>Total Amount</th>
                <th>Payment Mode</th>
                
                
   
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql55 = "SELECT * FROM tbl_users WHERE id=5";
            $row55 = getRecord($sql55);
            $title = str_replace(" ","#",$row55['ShopName']);
            $Address = str_replace(" ","#",$row55['Address']);
            $Phone = str_replace(" ","#",$row55['Phone']);
            $GstNo = str_replace(" ","#",$row55['GstNo']);
            $terms_condition = str_replace(" ","#",$row55['terms_condition']);
            $bottom_title = str_replace(" ","#",$row55['bottom_title']);
            $i=1;
            
        
            $sql = "SELECT * FROM tbl_customer_invoice WHERE Roll=2 AND Status=1 AND FrId=0";
           /* if($Roll == 1){
                $sql.=" AND FrId=0";
            }
            else{
                $sql.=" AND CreatedBy='$user_id'";
            }*/
            if($_POST['ReportType'] !=''){
                $ReportType = $_POST['ReportType'];
                if($ReportType == 'Today'){
                    $sql.=" AND InvoiceDate='".date('Y-m-d')."'";
                }
                if($ReportType == 'Yesterday'){
                    $Yesterday = date('Y-m-d',strtotime("-1 days"));
                    $sql.=" AND InvoiceDate='$Yesterday'";
                }
                if($ReportType == 'Week'){
                    $Week = date('Y-m-d',strtotime("-7 days"));
                    $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
                }
                if($ReportType == 'Month'){
                    $Week = date('Y-m-d',strtotime("-30 days"));
                    $sql.=" AND InvoiceDate>='$Week' AND InvoiceDate<='".date('Y-m-d')."'";
                }
                if($ReportType == 'Custom'){
                    $sql.=" ";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
            if($_REQUEST['PayType']){
                $PayType = $_REQUEST['PayType'];
                if($PayType == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND PayType='$PayType'";
                }
            }
            $sql.=" ORDER BY InvoiceDate DESC";
           // echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $TotAmt+=$row['NetAmount'];
                $CustName = str_replace(" ","#",$row['CustName']);
            $emparray = array();
            $sql12 = "SELECT * FROM tbl_customer_invoice_details WHERE InvId='".$row['id']."'";
            $row12 = getList($sql12);
            foreach($row12 as $result12){
                $sql13 = "SELECT ProductName FROM  tbl_cust_products WHERE id='".$result12['ProdId']."'";
                $row13 = getRecord($sql13);
                $TotQty+=$result12['Qty'];
                $TotPrice+=$result12['Price'];
                $TotGst+=number_format((float)$result12['GstAmt'], 2, '.', '');
                //$TotGst+=round($result12['GstAmt'],2);
                $Product_name2 = str_replace(" ","#",$row13['ProductName']);
                $Product_name = substr($Product_name2,0,16);

                 $emparray[] = array('item'=>$Product_name,'rate'=>$result12['Price'],'quantity'=>$result12['Qty'],'amount'=>$result12['Total']);
            }
            $dados2 =  json_encode(array('productList' => $emparray));
$date = date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate'])));

$dados1 = json_encode(array('title'=>$title,'address'=>$Address,'mobile'=>$Phone,'gstin'=>$GstNo,'customer_name'=>$CustName,'customer_phone_no'=>$row['CellNo'],'receipt_title'=>'Retail#Receipt','invoice_no'=>$row['InvoiceNo'],'date'=>$date,'sub_total'=>$row['SubTotal'],'discount'=>$row['Discount'],'service_charge'=>0,'gst'=>$TotGst,'total_bill'=>$row['NetAmount'],'total_payable'=>$row['NetAmount'],'terms_condition'=>$terms_condition,'bottom_title'=>$bottom_title)); 

           
     $invoice_data =  json_encode(array_merge(json_decode($dados1, true),json_decode($dados2, true)));
             ?>
            <tr>
             
            <td class="table-action">
            <a href="javascript:void(0)" onClick=printReceipt('<?php echo $invoice_data;?>') class="btn btn-icon btn-outline-primary"><i class="feather icon-printer"></i></a>
                <?php if(in_array("10", $Options)){?>
            <!--<a href="edit-order.php?id=<?php echo $row['id']; ?>" class="btn btn-icon btn-outline-success"><i class="feather icon-edit"></i></a>-->
            <?php } if(in_array("11", $Options)){?>
            <a onClick="return confirm('Are you sure you want delete this record. delete all record related this invoice.');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" class="btn btn-icon btn-outline-danger"><i class="feather icon-trash-2"></i></a>
            <?php } ?>
            <a href="javascript:void(0)" onclick="sendSms(<?php echo $row['id']; ?>)" class="btn btn-icon btn-outline-primary"><i class="feather icon-message-square"></i></a>
            </td>
            <td class="align-middle"><?php echo $row['OrderNo']; ?></td>
            <td class="align-middle"><?php echo $row['InvoiceNo']; ?></td>
            <td class="align-middle"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
            <td class="align-middle"><?php echo $row['CustName']; ?></td>
            <td class="align-middle">&#8377; <?php echo number_format($row['NetAmount'],2); ?></td>
            <td class="align-middle"><?php echo $row['PayType']; ?></td>
        </tr>
           <?php $i++;} ?>
           
           <tr>
               <th></th>
               <th></th>
               <th></th>
                <th></th>
               <th>Total</th>
               <th>&#8377;<?php echo number_format($TotAmt,2);?></th>
               <th></th>
           </tr>
        </tbody>
    </table>
    <script type="text/javascript">
      $(document).ready(function() {
         $('#example').DataTable({
        order: [[1, 'desc']],
      "scrollX": true
         });
      });
    </script>
<?php } 

if($_POST['action'] == 'todayOrders'){?>
<?php 
    function calAmt($type,$BillSoftFrId){
        global $conn;
         $sql = "SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE Roll=2 AND Status=1 AND InvoiceDate='".date('Y-m-d')."' AND FrId='$BillSoftFrId' AND PayType='$type'";
        $res = $conn->query($sql);
	    $row = $res->fetch_assoc();
	    if($row['NetAmount']==''){
	        $NetAmount = 0;
	    }
	    else{
	        $NetAmount = $row['NetAmount'];
	    }
	    return $NetAmount;
    }

 ?>
<div class="form-row" style="padding-left: 10px;padding-right: 10px;">
            <div class="form-group col-md-2">
<label class="form-label">Cash</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('Cash',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-2">
<label class="form-label">Phone Pay</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('Phone Pay',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-2">
<label class="form-label">Google Pay</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('UPI',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-2">
<label class="form-label">Paytm</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('Paytm',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-2">
<label class="form-label">Other UPI</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('Other UPI',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-1">
<label class="form-label">Credit</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('Borrowing',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-1">
<label class="form-label">Zomato</label>
<input type="text" class="form-control" value="&#8377;<?php echo calAmt('Zomato',$BillSoftFrId); ?>" autocomplete="off" readonly>
</div>

</div>
<table id="" class="table mb-0 dataTable no-footer" role="grid" aria-describedby="report-table_info">
        <thead class="thead-light">
            <tr>
              <!-- <th>#</th>-->
               
               <th>Action</th>
             
                <!-- <th>QR Code</th> -->
               <!--  <th>Status</th> -->
               <th>Order No</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Customer Name</th>
               
                <!--<th>Phone No</th>-->
                <th>Total Amount</th>
                <!--<th>Paid Amount</th>
                <th>Balance Amount</th>-->
                <th>Payment Mode</th>
                
                
   
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql55 = "SELECT * FROM tbl_users_bill WHERE id='$BillSoftFrId'";
            $row55 = getRecord($sql55);
            $title = str_replace(" ","#",$row55['PrintCompName']);
            $Address = str_replace(" ","#",$row55['Address']);
            $Phone = str_replace(" ","#",$row55['PrintMobNo']);
            $GstNo = str_replace(" ","#",$row55['GstNo']);
            //$terms_condition = str_replace(" ","#",$row55['terms_condition']);
            $bottom_title = str_replace(" ","#",$row55['bottom_title']);
            //$bottom_title.="#".date('h:i a');
            $i=1;
            
           
             $sql = "SELECT * FROM tbl_customer_invoice WHERE Roll=2 AND Status=1 AND InvoiceDate='".date('Y-m-d')."' AND FrId='$BillSoftFrId'  AND NetAmount>0";
            /* if($Roll == 1){
                $sql.=" AND FrId=0";
            }
            else{
                $sql.=" AND CreatedBy='$user_id'";
            }   */
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
            if($_POST['PayType']){
                $PayType = $_POST['PayType'];
                if($PayType == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND PayType='$PayType'";
                }
            }
            $sql.=" ORDER BY id DESC";
           // echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $TotAmt+=$row['NetAmount'];
                $CustName = str_replace(" ","#",$row['CustName']);
            $emparray = array();
            $sql12 = "SELECT * FROM tbl_customer_invoice_details WHERE InvId='".$row['id']."'";
            $row12 = getList($sql12);
            foreach($row12 as $result12){
                $sql13 = "SELECT ProductName FROM  tbl_cust_products WHERE id='".$result12['ProdId']."'";
                $row13 = getRecord($sql13);
                $TotQty+=$result12['Qty'];
                $TotPrice+=$result12['Price'];
                $TotGst+=number_format((float)$result12['GstAmt'], 2, '.', '');
                //$TotGst+=round($result12['GstAmt'],2);
                $Product_name2 = str_replace(" ","#",$row13['ProductName']);
                $Product_name = substr($Product_name2,0,16);

                 $emparray[] = array('item'=>$Product_name,'rate'=>$result12['Price'],'quantity'=>$result12['Qty'],'amount'=>$result12['Total']);
            }
            $dados2 =  json_encode(array('productList' => $emparray));
$date = date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate'])));
$terms_condition = "Time:".$row['CreatedTime']."#|Pay#Mode:#".$row['PayType'];
$dados1 = json_encode(array('title'=>$title,'address'=>$Address,'mobile'=>$Phone,'gstin'=>$GstNo,'customer_name'=>$CustName,'customer_phone_no'=>$row['CellNo'],'receipt_title'=>'Retail#Receipt','invoice_no'=>$row['InvoiceNo'],'date'=>$date,'sub_total'=>$row['SubTotal'],'discount'=>$row['Discount'],'service_charge'=>0,'gst'=>$TotGst,'total_bill'=>$row['NetAmount'],'total_payable'=>$row['NetAmount'],'terms_condition'=>$terms_condition,'bottom_title'=>$bottom_title)); 

           
     $invoice_data =  json_encode(array_merge(json_decode($dados1, true),json_decode($dados2, true)));
             ?>
            <tr>
               <!--<td><?php echo $i;?></td>-->
            
             <td class="table-action">
                                                           <a href="javascript:void(0)" onClick=printReceipt('<?php echo $invoice_data;?>') class="btn btn-icon btn-outline-primary"><i class="feather icon-printer"></i></a>
                                                            <?php if(in_array("10", $Options)){?>
                                                           <!--<a href="edit-order.php?id=<?php echo $row['id']; ?>" class="btn btn-icon btn-outline-success"><i class="feather icon-edit"></i></a>-->
                                                            <?php } if(in_array("11", $Options)){?>
                                                          <!--  <a onClick="return confirm('Are you sure you want delete this record. delete all record related this invoice.');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" class="btn btn-icon btn-outline-danger"><i class="feather icon-trash-2"></i></a>-->
                                                            <?php } ?>
                                                           <!-- <a href="javascript:void(0)" onclick="sendSms(<?php echo $row['id']; ?>)" class="btn btn-icon btn-outline-primary"><i class="feather icon-message-square"></i></a>-->
                                                        </td>
             
           <!--  <td> <?php if($row["Barcode"] == '') {?>
                  -
                 <?php } else if(file_exists('../barcodes/'.$row["Barcode"])){?>
                 <a href="../barcodes/<?php echo $row["Barcode"];?>" target="_new"><img src="../barcodes/<?php echo $row["Barcode"];?>?nocache=<?php echo time(); ?>" class="d-block ui-w-40" alt="" style="width: 40px;height: 40px;"></a>
                  <?php }  else{?>
                 -
             <?php } ?></td> -->
            <!--  <td>
             <label class="switcher switcher-success">
                                        <input type="checkbox" class="switcher-input" <?php if($row['Status']=='1'){?>checked=""<?php } ?> onclick="approve('<?php echo $row['Status'];?>','<?php echo $row['id']; ?>')">
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label"><?php if($row['Status']=='1'){echo "<span style='color:green;'>Approved</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></span>
                                    </label>
             </td> -->
             <td class="align-middle"><?php echo $row['OrderNo']; ?></td>
              <td class="align-middle"><?php echo $row['InvoiceNo']; ?></td>
              <td class="align-middle"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
           
                <td class="align-middle"><?php echo $row['CustName']; ?></td>
               <!-- <td><?php echo $row['CellNo']; ?></td>-->
                
                  <td class="align-middle">&#8377; <?php echo number_format($row['NetAmount'],2); ?></td>
                 <!-- <td>&#8377; <?php echo number_format($row['Advance'],2); ?></td>
                  <td>&#8377; <?php echo number_format($row['Balance'],2); ?></td>-->
                  <td class="align-middle"><?php echo $row['PayType']; ?></td>
                
            
         
              
            </tr>
           <?php $i++;} ?>
           
           <tr>
               <th></th>
               <th></th>
               <th></th>
                <th></th>
               <th>Total</th>
               <th>&#8377;<?php echo number_format($TotAmt,2);?></th>
               <th></th>
           </tr>
        </tbody>
    </table>
    <script type="text/javascript">
      $(document).ready(function() {
         $('#example2').DataTable({
        order: [[1, 'desc']],
      "scrollX": true
         });
      });
    </script>
<?php } 

if($_POST['action'] == 'totalIncome'){
    $sql = "SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE Roll=2 AND Status=1 AND InvoiceDate='".date('Y-m-d')."' AND FrId='$BillSoftFrId'";
$row = getRecord($sql);
echo $row['NetAmount'];
}
?>