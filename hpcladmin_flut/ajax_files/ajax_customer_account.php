<?php
session_start();
$sessionid = session_id();
include_once '../config.php';
//include('../../libs/phpqrcode/qrlib.php');
$user_id = $_SESSION['Admin']['id'] ?? 0;

// make mysqli throw exceptions so try/catch works with DB errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/**
 * Helper to handle uploads (returns filename or fallback)
 * $field -> $_FILES field name
 * $old  -> old filename value from POST to use if upload fails
 * $uploadDir -> directory where files should be saved (relative to this script)
 */
function handleUpload($field, $old = '', $uploadDir = '../../uploads/') {
    if (!isset($_FILES[$field]) || !is_uploaded_file($_FILES[$field]['tmp_name'])) {
        return $old;
    }
    $randno = rand(1, 100);
    $src = $_FILES[$field]['tmp_name'];
    $origName = $_FILES[$field]['name'];
    $fnm = substr($origName, 0, strrpos($origName, '.'));
    $fnm = str_replace(' ', '_', $fnm);
    $ext = substr($origName, strpos($origName, '.'));
    $imagepath = $randno . '_' . $fnm . $ext;
    $dest = rtrim($uploadDir, '/') . '/' . $imagepath;
    if (move_uploaded_file($src, $dest)) {
        return $imagepath;
    }
    return $old;
}

/**
 * Safe helper for POST read + escaping
 */
$__requestData = null;

function getRequestData() {
    global $__requestData;
    if ($__requestData !== null) {
        return $__requestData;
    }

    $__requestData = $_POST;
    if (!empty($__requestData)) {
        return $__requestData;
    }

    $raw = file_get_contents('php://input');
    if ($raw !== '') {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $__requestData = $decoded;
                return $__requestData;
            }
        }
        $parsed = [];
        parse_str($raw, $parsed);
        if (!empty($parsed)) {
            $__requestData = $parsed;
            return $__requestData;
        }
    }

    // Production fallback: POST body not received; form sent via GET query string
    if (!empty($_GET) && (req('action', '') === 'Save' || isset($_GET['CustomerId']) || isset($_GET['ShopName']))) {
        $__requestData = $_GET;
        return $__requestData;
    }

    $__requestData = [];
    return $__requestData;
}

function post($key) {
    $data = getRequestData();
    return isset($data[$key]) ? $data[$key] : '';
}

function postArray($key) {
    $data = getRequestData();
    // Support PHP array query keys: submenuid[] or submenuid
    if (isset($data[$key])) {
        return is_array($data[$key]) ? $data[$key] : [$data[$key]];
    }
    $bracketKey = $key . '[]';
    if (isset($data[$bracketKey])) {
        return is_array($data[$bracketKey]) ? $data[$bracketKey] : [$data[$bracketKey]];
    }
    return [];
}
function esc($conn, $value) {
    return mysqli_real_escape_string($conn, $value);
}

function normalizePhone($phone) {
    $digits = preg_replace('/\D/', '', (string) $phone);
    if (strlen($digits) > 10) {
        $digits = substr($digits, -10);
    }
    return $digits;
}

function dealerPhoneExists($conn, $phone, $excludeId = 0) {
    $normalized = normalizePhone($phone);
    if (strlen($normalized) < 10) {
        return false;
    }

    $normalized = esc($conn, $normalized);
    $excludeId = intval($excludeId);
    $excludeSql = $excludeId > 0 ? " AND id != '$excludeId'" : '';

    $sql = "SELECT id FROM tbl_users WHERE Roll=5 $excludeSql AND (
        Phone = '$normalized'
        OR Phone2 = '$normalized'
        OR RIGHT(REPLACE(REPLACE(REPLACE(REPLACE(Phone, ' ', ''), '-', ''), '+', ''), '.', ''), 10) = '$normalized'
        OR RIGHT(REPLACE(REPLACE(REPLACE(REPLACE(Phone2, ' ', ''), '-', ''), '+', ''), '.', ''), 10) = '$normalized'
    ) LIMIT 1";

    return getRow($sql) > 0;
}

function dealerCustomerIdExists($conn, $customerId, $excludeId = 0) {
    $customerId = trim((string) $customerId);
    if ($customerId === '') {
        return false;
    }

    $customerId = esc($conn, $customerId);
    $excludeId = intval($excludeId);
    $excludeSql = $excludeId > 0 ? " AND id != '$excludeId'" : '';
    $sql = "SELECT id FROM tbl_users WHERE Roll=5 AND CustomerId='$customerId' $excludeSql LIMIT 1";

    return getRow($sql) > 0;
}

function saveResponse($code, $savedId = 0) {
    header('Content-Type: text/plain; charset=UTF-8');
    if ($code === 1 && $savedId > 0) {
        echo '1:' . intval($savedId);
        return;
    }
    echo (string) $code;
}

$action = req('action', '');

if ($action === 'Save') {

    // collect variables (kept names same)
    $id = trim(post('id'));
    $ColgId = esc($conn, trim(post("ColgId")));
    $CourseId = esc($conn, trim(post("CourseId")));
    $Fname = esc($conn, trim(post('Fname')));
    if ($Fname === '') {
        $Fname = esc($conn, trim(post('ShopName')));
    }
    $Mname = esc($conn, trim(post('Mname')));
    $Lname = esc($conn, trim(post('Lname')));
    $Phone = esc($conn, trim(post('Phone')));
    $EmailId = esc($conn, post('EmailId'));
    $Phone2 = esc($conn, trim(post('Phone2')));
    $Password = esc($conn, trim(post('Password')));
    $CountryId = esc($conn, post('CountryId'));
    $StateId = esc($conn, post('StateId'));
    $CityId = esc($conn, post('CityId'));
    $Address = esc($conn, trim(post('Address')));
    $LoanCategory = esc($conn, trim(post('LoanCategory')));
    $SubCategory = esc($conn, trim(post('SubCategory')));
    $Campaign = esc($conn, trim(post('Campaign')));
    $Source = esc($conn, trim(post('Source')));
    $CallDate = esc($conn, trim(post('CallDate')));
    $AgentName = esc($conn, trim(post('AgentName')));
    $AgentComments = esc($conn, trim(post('AgentComments')));
    $PartId = esc($conn, trim(post('PartId')));
    $BranchId = esc($conn, trim(post('BranchId')));
    $Pincode = trim(post('Pincode'));
    $LeadId = trim(post('LeadId'));
    $Status = post('Status');
    if ($Status === '' || $Status === null) {
        $Status = '1';
    }
    $Status = esc($conn, $Status);
    $UserType = post('UserType');
    $Roll = post('Roll');

    $Address2 = esc($conn, trim(post('Address2')));
    $WorkingDetails = esc($conn, trim(post('WorkingDetails')));
    $WorkingAddress = esc($conn, trim(post('WorkingAddress')));
    $Gname = esc($conn, trim(post('Gname')));
    $Gphone = esc($conn, trim(post('Gphone')));
    $Gname2 = esc($conn, trim(post('Gname2')));
    $Gphone2 = esc($conn, trim(post('Gphone2')));
    $Dob = esc($conn, trim(post('Dob')));
    $Area = esc($conn, trim(post('Area')));
    $UnderUser = esc($conn, trim(post('UnderUser')));

    $ProjectType = esc($conn, trim(post('ProjectType')));
    $BeneficiaryId = esc($conn, trim(post('BeneficiaryId')));
    $Taluka = esc($conn, trim(post('Taluka')));
    $Village = esc($conn, trim(post('Village')));
    $District = esc($conn, trim(post('District')));
    $PumpCapacity = esc($conn, trim(post('PumpCapacity')));
    $RooftopPlantCapacity = esc($conn, trim(post('RooftopPlantCapacity')));

    $Lattitude = esc($conn, trim(post('Lattitude')));
    $Longitude = esc($conn, trim(post('Longitude')));
    $OffOnGrid = esc($conn, trim(post('OffOnGrid')));
    $SanctionLoad = esc($conn, trim(post('SanctionLoad')));
    $LoadExtension = esc($conn, trim(post('LoadExtension')));
    $WaterSource = esc($conn, trim(post('WaterSource')));
    $SummerDepth = esc($conn, trim(post('SummerDepth')));

    $WinterDepth = esc($conn, trim(post('WinterDepth')));
    $PumpHead = esc($conn, trim(post('PumpHead')));
    $BgNumber = esc($conn, trim(post('BgNumber')));
    $BgValidity = esc($conn, trim(post('BgValidity')));
    $BgClaimPeriod = esc($conn, trim(post('BgClaimPeriod')));
    $InsuranceNumber = esc($conn, trim(post('InsuranceNumber')));
    $InsuranceAgency = esc($conn, trim(post('InsuranceAgency')));
    $InsuranceValidity = esc($conn, trim(post('InsuranceValidity')));
    $InstallationVendor = esc($conn, trim(post('InstallationVendor')));
    $PumpHeadSelect = esc($conn, trim(post('PumpHeadSelect')));

    $SchemeId = esc($conn, trim(post('SchemeId')));
    $AcDc = esc($conn, trim(post('AcDc')));
    $Surface = esc($conn, trim(post('Surface')));
    $AadharNo = esc($conn, trim(post('AadharNo')));
    $PanNo = esc($conn, trim(post('PanNo')));

    $AccountName = esc($conn, trim(post('AccountName')));
    $BankName = esc($conn, trim(post('BankName')));
    $AccountNo = esc($conn, trim(post('AccountNo')));
    $IfscCode = esc($conn, trim(post('IfscCode')));
    $Branch = esc($conn, trim(post('Branch')));
    $UpiNo = esc($conn, trim(post('UpiNo')));

    $GumastaNo = esc($conn, trim(post('GumastaNo')));
    $MsmeNo = esc($conn, trim(post('MsmeNo')));
    $InspectionDate = esc($conn, trim(post('InspectionDate')));
    $CommissioningDate = esc($conn, trim(post('CommissioningDate')));
    $CustType = esc($conn, trim(post('CustType')));
    $BoreDia = esc($conn, trim(post('BoreDia')));

    $CompName = esc($conn, trim(post('CompName')));
    $CompAddress = esc($conn, trim(post('CompAddress')));
    $CompPhone = esc($conn, trim(post('CompPhone')));
    $AuthorName = esc($conn, trim(post('AuthorName')));
    $CompId = esc($conn, trim(post('CompId')));
    $ExeId = esc($conn, trim(post('ExeId')));
    $SellAmt = esc($conn, trim(post('SellAmt')));
    $SellDate = esc($conn, trim(post('SellDate')));

    $ShopName = esc($conn, trim(post('ShopName')));
    $OwnFranchise = esc($conn, trim(post('OwnFranchise')));
    $Location = esc($conn, trim(post('Location')));
    $ZoneId = esc($conn, trim(post('ZoneId')));
    $CreatedDate = date('Y-m-d');

    $FrDevCost = esc($conn, trim(post('FrDevCost')));
    $MonthlyRent = esc($conn, trim(post('MonthlyRent')));
    $PumpName = esc($conn, trim(post('PumpName')));
    $SpacePartner = esc($conn, trim(post('SpacePartner')));

    $SubZoneId = esc($conn, trim(post('SubZoneId')));
    $AlianceName = esc($conn, trim(post('AlianceName')));
    $AliancePhone = esc($conn, trim(post('AliancePhone')));
    $AlianceEmailId = esc($conn, trim(post('AlianceEmailId')));
    $AliancePer = esc($conn, trim(post('AliancePer')));
    $FssaiNo = esc($conn, trim(post('FssaiNo')));
    $OperationalFr = esc($conn, trim(post('OperationalFr')));
    $UnderByBdm = esc($conn, trim(post('UnderByBdm')));

    $OpenTime = esc($conn, trim(post('OpenTime')));
    $CloseTime = esc($conn, trim(post('CloseTime')));

    $OpenTime24 = !empty($OpenTime) ? date('H:i', strtotime($OpenTime)) : '';
    $CloseTime24 = !empty($CloseTime) ? date('H:i', strtotime($CloseTime)) : '';

    $ModelType = esc($conn, trim(post('ModelType')));

    $Options = '10,11,14,48,49,50,56,57,59,60,69,71,73,74,77,78,79,80,81,82,84,85,86,92,93,94,96,97,98,99';

    $GstNo = esc($conn, trim(post('GstNo')));

    // multi-select fields
    $zomatoValues = postArray('ZomatoSwiggy');
    if (!empty($zomatoValues)) {
        $ZomatoSwiggy = esc($conn, implode(",", $zomatoValues));
    } else {
        $ZomatoSwiggy = 0;
    }

    $modified_time = gmdate('Y-m-d H:i:s.') . gettimeofday()['usec'];

    $PrintCompName = esc($conn, trim(post('PrintCompName')));
    $PrintMobNo = esc($conn, trim(post('PrintMobNo')));
    $terms_condition = esc($conn, trim(post('terms_condition')));
    $bottom_title = esc($conn, trim(post('bottom_title')));
    $MenuId = esc($conn, trim(post('MenuId')));
    $NewFr = esc($conn, trim(post('NewFr')));
    $CustomerId = esc($conn, trim(post('CustomerId')));
    $Gumasta = esc($conn, trim(post('Gumasta')));
    $Msme = esc($conn, trim(post('Msme')));

    $menuValues = postArray('menu_ids');
    if (!empty($menuValues)) {
        $menu_ids = esc($conn, implode(",", $menuValues));
    } else {
        $menu_ids = '1,2,3,5,6,8,9,10,11,12,13,14,17,18,19,20,21,22,26';
    }

    $submenuValues = postArray('submenuid');
    if (!empty($submenuValues)) {
        $submenuid = esc($conn, implode(",", $submenuValues));
    } else {
        $submenuid ='5,6,13,14,8,10,11,12,15,22,37,38,1,2,21,3,4,19,20,29,32,33,23,24,25,26,27,28,34,35,31';
    }

    if (trim(post('CustomerId')) === '' && trim(post('ShopName')) === '') {
        error_log('ajax_customer_account Save rejected: form data not received. Content-Type=' . ($_SERVER['CONTENT_TYPE'] ?? 'none'));
        saveResponse(-2);
        exit;
    }

    if (trim(post('CustomerId')) === '' || trim(post('ShopName')) === '') {
        saveResponse(-1);
        exit;
    }

    // handle uploads via helper (keeps existing fallback POST values)
    $Photo = handleUpload('Photo', post('OldPhoto'));
    $AadharCard = handleUpload('AadharCard', post('AadharCardOld'));
    $AadharCard2 = handleUpload('AadharCard2', post('AadharCardOld2'));
    $PanCard = handleUpload('PanCard', post('PanCardOld'));
    $PanCard2 = handleUpload('PanCard2', post('PanCardOld2'));
    $GstCertificate = handleUpload('GstCertificate', post('OldGstCertificate'));
    $FoodLicence = handleUpload('FoodLicence', post('OldFoodLicence'));
    $FoodLicenceReceipt = handleUpload('FoodLicenceReceipt', post('OldFoodLicenceReceipt'));
    $AgreementCopy = handleUpload('AgreementCopy', post('OldAgreementCopy'));
    

    try {
        // begin transaction
        $conn->begin_transaction();

        if ($id === '' || $id === '0') {
            if (dealerCustomerIdExists($conn, $CustomerId)) {
                saveResponse(2);
                $conn->rollback();
                exit;
            }

            if (dealerPhoneExists($conn, $Phone)) {
                saveResponse(0);
                $conn->rollback();
                exit;
            }

            if ($Phone2 !== '' && dealerPhoneExists($conn, $Phone2)) {
                saveResponse(0);
                $conn->rollback();
                exit;
            }

            // Build insert query (kept same fields and order)
            $sql = "INSERT INTO tbl_users SET
                CustomerId='$CustomerId',menu_ids='$menu_ids',submenuid='$submenuid',ModelType='$ModelType',
                OpenTime='$OpenTime',CloseTime='$CloseTime',OpenTime24='$OpenTime24',CloseTime24='$CloseTime24',
                UnderByBdm='$UnderByBdm',ZomatoSwiggy='$ZomatoSwiggy',OperationalFr='$OperationalFr',FssaiNo='$FssaiNo',
                AlianceName='$AlianceName',AliancePhone='$AliancePhone',AlianceEmailId='$AlianceEmailId',AliancePer='$AliancePer',
                NewFr='$NewFr',MenuId='$MenuId',SubZoneId='$SubZoneId',FrDevCost='$FrDevCost',MonthlyRent='$MonthlyRent',
                PumpName='$PumpName',SpacePartner='$SpacePartner',ZoneId='$ZoneId',OwnFranchise='$OwnFranchise',ShopName='$ShopName',
                ExeId='$ExeId',SellAmt='$SellAmt',SellDate='$SellDate',SchemeId='$SchemeId',ColgId='$ColgId',Fname='$Fname',
                Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Phone2='$Phone2',
                Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',
                Status='$Status',BranchId='$BranchId',CreatedDate='$CreatedDate',CreatedBy='$user_id',Dob='$Dob',Area='$Area',
                UserType='$UserType',UnderUser='$UnderUser',ProjectType='$ProjectType',BeneficiaryId='$BeneficiaryId',Taluka='$Taluka',
                Village='$Village',District='$District',PumpCapacity='$PumpCapacity',RooftopPlantCapacity='$RooftopPlantCapacity',
                Lattitude='$Lattitude',Longitude='$Longitude',OffOnGrid='$OffOnGrid',SanctionLoad='$SanctionLoad',LoadExtension='$LoadExtension',
                WaterSource='$WaterSource',SummerDepth='$SummerDepth',WinterDepth='$WinterDepth',PumpHead='$PumpHead',BgNumber='$BgNumber',
                BgValidity='$BgValidity',BgClaimPeriod='$BgClaimPeriod',InsuranceNumber='$InsuranceNumber',InsuranceAgency='$InsuranceAgency',
                InsuranceValidity='$InsuranceValidity',InstallationVendor='$InstallationVendor',PumpHeadSelect='$PumpHeadSelect',
                AcDc='$AcDc',Surface='$Surface',AadharCard='$AadharCard',AadharCard2='$AadharCard2',PanCard='$PanCard',PanCard2='$PanCard2',
                AadharNo='$AadharNo',PanNo='$PanNo',GstCertificate='$GstCertificate',GstNo='$GstNo',AccountName='$AccountName',
                BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',GumastaNo='$GumastaNo',
                Gumasta='$Gumasta',MsmeNo='$MsmeNo',Msme='$Msme',
                InspectionDate='$InspectionDate',CommissioningDate='$CommissioningDate',CustType='$CustType',BoreDia='$BoreDia',
                CompName='$CompName',CompAddress='$CompAddress',CompPhone='$CompPhone',AuthorName='$AuthorName',Roll=5,CompId='$CompId',
                Options='$Options',terms_condition='$terms_condition',bottom_title='$bottom_title',PrintCompName='$PrintCompName',
                PrintMobNo='$PrintMobNo',FoodLicence='$FoodLicence',FoodLicenceReceipt='$FoodLicenceReceipt',AgreementCopy='$AgreementCopy',
                modified_time='$modified_time',Location='$Location',Photo='$Photo'";

            $conn->query($sql);
            $EmpId = intval(mysqli_insert_id($conn));
            if ($EmpId <= 0) {
                saveResponse(-1);
                $conn->rollback();
                exit;
            }

            // insert customer address
            $sql3 = "INSERT INTO customer_address SET UserId='$EmpId',Fname='$Fname',Lname='$Lname',Phone='$Phone',
                EmailId='$EmailId',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',AreaId='" . esc($conn, post('AreaId')) . "',
                Address='$Address',Pincode='$Pincode',Status='1',CreatedDate='$CreatedDate'";
            $conn->query($sql3);

            // create CustomerId and update user record
            /*$CustomerId = "HP" . $EmpId;
            $sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'";
            $conn->query($sql3);*/
            
             $config_json = '{
    "section_visibility": {
        "show_printer_configuration": true,
        "show_receipt_configuration": true,
        "show_billing_options": true,
        "show_multi_kitchen_printing": true,
        "show_logo_configuration": true,
        "show_tax_configuration": true
    },
    "printer_config": {
        "printer_type": "wired",
        "paper_size": "3-inch",
        "selected_bluetooth_printer": "",
        "enabled_by_admin": true
    },
    "receipt_config": {
        "receipt_type": "bazaar",
        "show_logo": true,
        "show_qr_code": true,
        "show_scan_to_pay": false,
        "show_feedback_qr": true,
        "enabled_by_admin": true
    },
    "table_billing_config": {
        "enable_table_billing": false,
        "table_count": 10,
        "enabled_by_admin": true
    },
    "payment_options": {
        "show_cash_calculator": true,
        "show_open_calculator": false,
        "show_redeem_points": false,
        "enable_split_payment": false,
        "enabled_by_admin": true
    },
    "product_settings": {
        "show_product_photos": false,
        "show_product_stock": true,
        "show_barcode_scanner_icon": false,
        "block_out_of_stock_cart": true,
        "restrict_discount_on_mrp": true,
        "enabled_by_admin": true
    },
    "multi_kitchen_config": {
        "enable_multi_kitchen_printing": false,
        "south_indian_printer_name": "",
        "chinese_printer_name": "",
        "receipt_printer_name": "",
        "south_indian_kitchen_header_name": "SOUTH INDIAN KITCHEN",
        "chinese_kitchen_header_name": "CHINESE KITCHEN",
        "enabled_by_admin": true
    },
    "advanced_features": {
        "show_kot": true,
        "show_kot_buttons": false,
        "show_kot_summary": false,
        "show_email_field": true,
        "show_order_instruction": true,
        "show_ac_charge": false,
        "show_coupon_code": false,
        "show_export_data_button": false,
        "show_diagnostic_button": false,
        "show_exchange_button": false,
        "show_online_orders_button": false,
        "enable_crosssell_dialog": false,
        "require_customer_details_for_credit": false,
        "require_customer_details_for_discount": false,
        "enabled_by_admin": true
    },
    "tax_config": {
        "enable_gst": true,
        "enabled_by_admin": true
    },
    "logo_config": {
        "logo_url": "",
        "enabled_by_admin": true
    },
    "display_options": {
        "show_price_range": false,
        "show_top_sellers_option": false,
        "show_top_sellers_badge": true,
        "compact_mode": false,
        "enabled_by_admin": true
    }
}';
                $sql3 = "INSERT INTO setup_configurations SET userid='$EmpId',config_json='" . esc($conn, $config_json) . "',created_at='" . date('Y-m-d H:i:s') . "'";
                try {
                    $conn->query($sql3);
                } catch (Exception $setupEx) {
                    error_log('setup_configurations insert skipped: ' . $setupEx->getMessage());
                }
                

            // Insert ledger record
            $sql = "INSERT INTO tbl_general_ledger SET UserId='$EmpId',AccountName='$Fname',Amount='$SellAmt',
                PaymentDate='$SellDate',CrDr='Cr',Type='OB',Narration='Total Sell Amount',CreatedDate='$CreatedDate'";
            $conn->query($sql);

            // copy to tbl_users_bill (mirror)
            try {
                $sql = "INSERT INTO tbl_users_bill SELECT * FROM `tbl_users` WHERE id='$EmpId'";
                $conn->query($sql);
                if ($conn->affected_rows <= 0) {
                    throw new Exception('tbl_users_bill insert returned 0 rows');
                }
            } catch (Exception $billEx) {
                error_log('tbl_users_bill insert failed for id ' . $EmpId . ': ' . $billEx->getMessage());
                saveResponse(-3);
                $conn->rollback();
                exit;
            }

            // commit transaction
            $conn->commit();

            saveResponse(1, $EmpId);
        } else {
            // UPDATE flow
            $existing = getRecord("SELECT id FROM tbl_users WHERE id='" . esc($conn, $id) . "' AND Roll=5 LIMIT 1");
            if (!$existing) {
                saveResponse(-1);
                $conn->rollback();
                exit;
            }

            if (dealerCustomerIdExists($conn, $CustomerId, $id)) {
                saveResponse(2);
                $conn->rollback();
                exit;
            }

            if (dealerPhoneExists($conn, $Phone, $id)) {
                saveResponse(0);
                $conn->rollback();
                exit;
            }

            if ($Phone2 !== '' && dealerPhoneExists($conn, $Phone2, $id)) {
                saveResponse(0);
                $conn->rollback();
                exit;
            }

            $sql = "UPDATE tbl_users SET CustomerId='$CustomerId',menu_ids='$menu_ids',submenuid='$submenuid',ModelType='$ModelType',
                OpenTime='$OpenTime',CloseTime='$CloseTime',OpenTime24='$OpenTime24',CloseTime24='$CloseTime24',
                UnderByBdm='$UnderByBdm',ZomatoSwiggy='$ZomatoSwiggy',OperationalFr='$OperationalFr',FssaiNo='$FssaiNo',
                AlianceName='$AlianceName',AliancePhone='$AliancePhone',AlianceEmailId='$AlianceEmailId',AliancePer='$AliancePer',
                Phone='$Phone',NewFr='$NewFr',MenuId='$MenuId',PrintCompName='$PrintCompName',PrintMobNo='$PrintMobNo',
                terms_condition='$terms_condition',bottom_title='$bottom_title',SubZoneId='$SubZoneId',FrDevCost='$FrDevCost',
                MonthlyRent='$MonthlyRent',PumpName='$PumpName',SpacePartner='$SpacePartner',ZoneId='$ZoneId',OwnFranchise='$OwnFranchise',
                ShopName='$ShopName',ExeId='$ExeId',SellAmt='$SellAmt',SellDate='$SellDate',Barcode='',
                Roll=5,SchemeId='$SchemeId',ColgId='$ColgId',Fname='$Fname',Mname='$Mname',Lname='$Lname',EmailId='$EmailId',
                Phone2='$Phone2',Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',
                Pincode='$Pincode',Status='$Status',BranchId='$BranchId',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',Dob='$Dob',
                Area='$Area',UserType='$UserType',UnderUser='$UnderUser',ProjectType='$ProjectType',BeneficiaryId='$BeneficiaryId',
                Taluka='$Taluka',Village='$Village',District='$District',PumpCapacity='$PumpCapacity',RooftopPlantCapacity='$RooftopPlantCapacity',
                Lattitude='$Lattitude',Longitude='$Longitude',OffOnGrid='$OffOnGrid',SanctionLoad='$SanctionLoad',LoadExtension='$LoadExtension',
                WaterSource='$WaterSource',SummerDepth='$SummerDepth',WinterDepth='$WinterDepth',PumpHead='$PumpHead',BgNumber='$BgNumber',
                BgValidity='$BgValidity',BgClaimPeriod='$BgClaimPeriod',InsuranceNumber='$InsuranceNumber',InsuranceAgency='$InsuranceAgency',
                InsuranceValidity='$InsuranceValidity',InstallationVendor='$InstallationVendor',PumpHeadSelect='$PumpHeadSelect',
                AcDc='$AcDc',Surface='$Surface',AadharCard='$AadharCard',AadharCard2='$AadharCard2',PanCard='$PanCard',PanCard2='$PanCard2',
                AadharNo='$AadharNo',PanNo='$PanNo',GstCertificate='$GstCertificate',GstNo='$GstNo',AccountName='$AccountName',
                BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',GumastaNo='$GumastaNo',
                Gumasta='$Gumasta',MsmeNo='$MsmeNo',Msme='$Msme',
                InspectionDate='$InspectionDate',CommissioningDate='$CommissioningDate',CustType='$CustType',BoreDia='$BoreDia',
                CompName='$CompName',CompAddress='$CompAddress',CompPhone='$CompPhone',AuthorName='$AuthorName',CompId='$CompId',
                Options='$Options',FoodLicence='$FoodLicence',FoodLicenceReceipt='$FoodLicenceReceipt',AgreementCopy='$AgreementCopy',
                modified_time='$modified_time',Location='$Location',Photo='$Photo' WHERE id='$id'";
            $conn->query($sql);

            // update tbl_users_bill mirror
            $conn->query("DELETE FROM tbl_users_bill WHERE id='$id'");
            $sql = "INSERT INTO tbl_users_bill SELECT * FROM `tbl_users` WHERE id='$id'";
            $conn->query($sql);
            if ($conn->affected_rows <= 0) {
                saveResponse(-1);
                $conn->rollback();
                exit;
            }

            // update customer address
            $sql3 = "UPDATE customer_address SET Fname='$Fname',Lname='$Lname',Phone='$Phone',
                EmailId='$EmailId',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',AreaId='" . esc($conn, post('AreaId')) . "',
                Address='$Address',Pincode='$Pincode',Status='1',CreatedDate='$CreatedDate' WHERE UserId='$id'";
            $conn->query($sql3);

            // update ledger - delete previous OB and re-insert
            $sql = "DELETE FROM tbl_general_ledger WHERE UserId='$id' AND Type='OB'";
            $conn->query($sql);

            $sql = "INSERT INTO tbl_general_ledger SET UserId='$id',AccountName='$Fname',Amount='$SellAmt',
                PaymentDate='$SellDate',CrDr='Cr',Type='OB',Narration='Total Sell Amount',CreatedDate='$CreatedDate'";
            $conn->query($sql);

            $conn->commit();
            saveResponse(1, intval($id));
        }
    } catch (Exception $e) {
        // rollback and return error (log as needed)
        if ($conn->in_transaction) {
            $conn->rollback();
        }
        error_log('ajax_customer_account Save error: ' . $e->getMessage());
        saveResponse(-1);
        exit;
    }
    exit;
}

// other AJAX actions - keep behavior same but no change in logic except small safety improvements
if (req('action', '') == 'deletePhoto') {
    $id = intval($_POST['id']);
    $Photo = esc($conn, $_POST['Photo']);
    $q = "UPDATE tbl_users SET Photo='' WHERE id=$id";
    $conn->query($q);
    echo "File Deleted Successfully";
}

if ($_POST['action'] == 'getCustDetails') {
    $id = intval($_POST['id']);
    $sql = "SELECT * FROM tbl_users WHERE id=$id";
    $row = getRecord($sql);
    echo json_encode($row);
}

if ($_POST['action'] == 'chageSurveyDetails') {
    $id = intval($_POST['id']);
    $val = esc($conn, $_POST['val']);
    $sql = "UPDATE tbl_users SET SurveyDetails='$val' WHERE id=$id";
    $conn->query($sql);
    echo 1;
}

if ($_POST['action'] == 'getTotalCashAmt') {
    $FromDate = esc($conn, $_POST['FromDate']);
    $ToDate = esc($conn, $_POST['ToDate']);
    $FrId = esc($conn, $_POST['FrId']);

    $sql22 = "SELECT SUM(TotalCashAmt) AS TotalCashAmt FROM (
        SELECT SUM(NetAmount) AS TotalCashAmt FROM tbl_customer_invoice WHERE FrId='$FrId' AND PayType='Cash'
        UNION ALL
        SELECT SUM(NetAmount) AS TotalCashAmt FROM tbl_customer_invoice_2025 WHERE FrId='$FrId' AND PayType='Cash'
    ) as a";
    $row22 = getRecord($sql22);

    $sql221 = "SELECT SUM(Amount) AS TotalTransferAmt FROM tbl_cash_book WHERE FrId='$FrId' AND ApproveStatus=1";
    $row221 = getRecord($sql221);
    $TotalAmount = $row22["TotalCashAmt"] - $row221['TotalTransferAmt'];
    echo $TotalAmount;
}

if ($_POST['action'] == 'getSellAmt') {
    $FrId = esc($conn, $_POST['FrId']);
    $month = esc($conn, $_POST['month']);
    $year = esc($conn, $_POST['year']);
    if ($FrId == 'all') {
        $sql = "SELECT COALESCE(SUM(NetAmount), 0) AS NetAmount FROM (
            SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE month(InvoiceDate)='$month' AND year(InvoiceDate)='$year'
            UNION ALL
            SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice_2025 WHERE month(InvoiceDate)='$month' AND year(InvoiceDate)='$year'
        ) as a";
        $row = getRecord($sql);

        $sql2 = "SELECT COALESCE(SUM(TotAmt), 0) AS TotPetty
            FROM (
                SELECT tu.Fname, SUM(w.Amount) AS TotAmt
                FROM `wallet` w
                LEFT JOIN tbl_users tu ON tu.id = w.UserId
                LEFT JOIN tbl_users_bill tub ON tub.id = tu.UnderFrId
                WHERE w.Status = 'Cr'
                    AND MONTH(w.CreatedDate) = '$month'
                    AND YEAR(w.CreatedDate) = '$year'
                    AND (w.Narration LIKE '%Pretty Cash%' OR w.Narration LIKE '%Petty Cash%')
                GROUP BY w.UserId
            ) AS a";
        $row2 = getRecord($sql2);

        $sql3 = "SELECT COALESCE(SUM(MonthlySalary), 0) AS MonthlySalary FROM (
            SELECT tu.id,tu.Fname,tu.SalaryType,tu.PerDaySalary,$month AS Month,$year AS Year,DAY(LAST_DAY(CONCAT($year, '-', $month, '-01'))) AS DaysInMonth,
            tu.PerDaySalary * DAY(LAST_DAY(CONCAT($year, '-', $month, '-01'))) AS MonthlySalary FROM tbl_users tu WHERE tu.SalaryType = 1 AND tu.Status=1
            UNION ALL
            SELECT tu.id,tu.Fname,tu.SalaryType,tu.PerDaySalary,$month AS Month,$year AS Year,DAY(LAST_DAY(CONCAT($year, '-', $month, '-01'))) AS DaysInMonth,tu.PerDaySalary AS MonthlySalary FROM tbl_users tu WHERE tu.SalaryType = 2 AND tu.Status=1
        ) as a";
        $row3 = getRecord($sql3);

        $sql4 = "SELECT COALESCE(SUM(MonthlyRent), 0) AS MonthlyRent FROM tbl_users WHERE Roll=5 AND Status=1";
        $row4 = getRecord($sql4);

    } else {
        $sql = "SELECT COALESCE(SUM(NetAmount), 0) AS NetAmount FROM (
            SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice WHERE FrId='$FrId' AND month(InvoiceDate)='$month' AND year(InvoiceDate)='$year'
            UNION ALL
            SELECT SUM(NetAmount) AS NetAmount FROM tbl_customer_invoice_2025 WHERE FrId='$FrId' AND month(InvoiceDate)='$month' AND year(InvoiceDate)='$year'
        ) as a";
        $row = getRecord($sql);

        $sql2 = "SELECT COALESCE(SUM(TotAmt), 0) AS TotPetty
            FROM (
                SELECT tu.Fname, SUM(w.Amount) AS TotAmt
                FROM `wallet` w
                LEFT JOIN tbl_users tu ON tu.id = w.UserId
                LEFT JOIN tbl_users_bill tub ON tub.id = tu.UnderFrId
                WHERE tub.id = '$FrId'
                    AND w.Status = 'Cr'
                    AND MONTH(w.CreatedDate) = '$month'
                    AND YEAR(w.CreatedDate) = '$year'
                    AND (w.Narration LIKE '%Pretty Cash%' OR w.Narration LIKE '%Petty Cash%')
                GROUP BY w.UserId
            ) AS a";
        $row2 = getRecord($sql2);

        $sql3 = "SELECT COALESCE(SUM(MonthlySalary), 0) AS MonthlySalary FROM (
            SELECT tu.id,tu.Fname,tu.SalaryType,tu.PerDaySalary,$month AS Month,$year AS Year,DAY(LAST_DAY(CONCAT($year, '-', $month, '-01'))) AS DaysInMonth,
            tu.PerDaySalary * DAY(LAST_DAY(CONCAT($year, '-', $month, '-01'))) AS MonthlySalary FROM tbl_users tu WHERE tu.UnderFrId = '$FrId' AND tu.SalaryType = 1 AND tu.Status=1
            UNION ALL
            SELECT tu.id,tu.Fname,tu.SalaryType,tu.PerDaySalary,$month AS Month,$year AS Year,DAY(LAST_DAY(CONCAT($year, '-', $month, '-01'))) AS DaysInMonth,tu.PerDaySalary AS MonthlySalary FROM tbl_users tu WHERE tu.UnderFrId = '$FrId' AND tu.SalaryType = 2 AND tu.Status=1
        ) as a";
        $row3 = getRecord($sql3);

        $sql4 = "SELECT COALESCE(SUM(MonthlyRent), 0) AS MonthlyRent FROM tbl_users WHERE id='$FrId'";
        $row4 = getRecord($sql4);
    }
    echo json_encode(array('Rent' => $row4['MonthlyRent'], 'NetAmount' => $row['NetAmount'], 'TotPetty' => $row2['TotPetty'], 'Salary' => $row3['MonthlySalary']));
}
?>
