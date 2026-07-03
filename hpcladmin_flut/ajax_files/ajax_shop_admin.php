<?php
session_start();
include_once '../config.php';

$user_id = (int)($_SESSION['Admin']['id'] ?? 0);
if ($user_id <= 0) {
    $user_id = 1;
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

define('SHOP_ADMIN_ROLL', 167);
define('SHOP_ADMIN_DEFAULT_OPTIONS', '4,5,6,130,7,131,132,157,158,44,45,46,47,48,49,50,51,52,53,129');

function esc($conn, $val) { return mysqli_real_escape_string($conn, trim((string)$val)); }
function post($key, $default = '') { return $_POST[$key] ?? $default; }

function shopAdminUserDefaultsSql($createdDate) {
    return "
        CountryId=0, StateId=0, CityId=0, Pincode='', UserType=0, UnderUser=0, SurveyDetails=0,
        SchemeId=0, CustType=0, BoreDia='', CatId='0', CompId=0, KycStatus=0, ExeId=0, UnderFr=0,
        ReportingMgr=0, ResignStatus=0, BillSoftFrId=0, PkgId=0, PkgDate='$createdDate', Prime=0,
        OwnFranchise=0, SalaryType=0, CreditSalaryStatus=0, IdStatus=0, push_flag=0, delete_flag=0,
        modified_time=NOW(), UnderFrId=0, MainBrEmp=0, ExpApproval=0, UnderByUser=0, DeliveryPerson=0,
        TypeOfVendor=0, ZoneId=0, MonthlySalary=0, SubZoneId=0, NsoVedPay=0, UnderByBdm=0, logincnt=0,
        IncrementPer=0, ReferId=0, MarkAttendance=0, VendorExpSecOpt=0, EmpStatus=0, EmpScheme=0, EsicNo='',
        BdmCheckpoint=0, EmpAppDashboard=0, InternshipEmp=0, PayPeriod=0, CashHandover=0, ModelType=0,
        WorkingHrs=0, VedId=0, Mname='', Lname='', GstNo='', Details='', PanNo='', Photo='', Photo2='', Photo3=''
    ";
}

function insertCustomerAddress($conn, $userId, $Fname, $Phone, $EmailId, $Address, $CreatedDate) {
    $conn->query("INSERT INTO customer_address SET UserId='$userId', Fname='$Fname', Lname='', Phone='$Phone',
        EmailId='$EmailId', CountryId=0, StateId=0, CityId=0, AreaId=0, Address='$Address', Pincode='',
        Status='1', CreatedDate='$CreatedDate', ModifiedDate=0");
}

header('Content-Type: text/plain; charset=utf-8');

try {
    if (($_POST['action'] ?? '') !== 'Save') {
        echo 'invalid';
        exit;
    }

    $id = post('id');
    $CreatedDate = date('Y-m-d');

    $Fname = esc($conn, post('Fname'));
    $Phone = esc($conn, post('Phone'));
    $EmailId = esc($conn, post('EmailId'));
    $Phone2 = esc($conn, post('Phone2'));
    $Password = esc($conn, post('Password'));
    $Address = esc($conn, post('Address'));
    $Status = esc($conn, post('Status', '1'));
    $AccountName = esc($conn, post('AccountName'));
    $BankName = esc($conn, post('BankName'));
    $AccountNo = esc($conn, post('AccountNo'));
    $IfscCode = esc($conn, post('IfscCode'));
    $Branch = esc($conn, post('Branch'));
    $UpiNo = esc($conn, post('UpiNo'));
    $Designation = 'Shop Admin';
    $Roll = SHOP_ADMIN_ROLL;

    if ($Fname === '' || $Phone === '' || $Password === '') {
        echo 'required';
        exit;
    }
    if (empty($_POST['zone']) || empty($_POST['subzone'])) {
        echo 'access';
        exit;
    }

    $zone = esc($conn, implode(',', (array)$_POST['zone']));
    $subzone = esc($conn, implode(',', (array)$_POST['subzone']));
    $CocoFranchiseAccess = !empty($_POST['CocoFranchiseAccess']) ? esc($conn, implode(',', (array)$_POST['CocoFranchiseAccess'])) : '';
    $FrPanelOptions = !empty($_POST['FrPanelOptions']) ? esc($conn, implode(',', array_map('intval', (array)$_POST['FrPanelOptions']))) : '';
    $Options2 = SHOP_ADMIN_DEFAULT_OPTIONS;

    $dupSql = "SELECT id, Roll FROM tbl_users WHERE Phone='$Phone'";
    if ($id !== '') {
        $dupSql .= " AND id!='$id'";
    }
    $dup = $conn->query($dupSql);
    if ($dup && $dup->num_rows > 0) {
        echo 'duplicate';
        exit;
    }

    $conn->begin_transaction();

    if ($id === '') {
        $defaults = shopAdminUserDefaultsSql($CreatedDate);
        $sql = "INSERT INTO tbl_users SET $defaults,
            Options='$FrPanelOptions', Options2='$Options2', zone='$zone', subzone='$subzone', CocoFranchiseAccess='$CocoFranchiseAccess',
            Fname='$Fname', Phone='$Phone', EmailId='$EmailId', Password='$Password', Phone2='$Phone2',
            Address='$Address', Status='$Status', Roll='$Roll', Designation='$Designation',
            CreatedDate='$CreatedDate', CreatedBy='$user_id',
            AccountName='$AccountName', BankName='$BankName', AccountNo='$AccountNo',
            IfscCode='$IfscCode', Branch='$Branch', UpiNo='$UpiNo'";
        $conn->query($sql);
        $newId = $conn->insert_id;

        $conn->query("UPDATE tbl_users SET CustomerId='SA$newId' WHERE id='$newId'");
        $conn->query("INSERT INTO tbl_users_bill SELECT * FROM tbl_users WHERE id='$newId'");
        insertCustomerAddress($conn, $newId, $Fname, $Phone, $EmailId, $Address, $CreatedDate);

        $conn->commit();
        echo '1';
    } else {
        $sql = "UPDATE tbl_users SET
            Options='$FrPanelOptions', Options2='$Options2', zone='$zone', subzone='$subzone', CocoFranchiseAccess='$CocoFranchiseAccess',
            Fname='$Fname', Phone='$Phone', EmailId='$EmailId', Password='$Password', Phone2='$Phone2',
            Address='$Address', Status='$Status', Designation='$Designation',
            ModifiedDate='$CreatedDate', ModifiedBy='$user_id',
            AccountName='$AccountName', BankName='$BankName', AccountNo='$AccountNo',
            IfscCode='$IfscCode', Branch='$Branch', UpiNo='$UpiNo'
            WHERE id='$id' AND Roll='$Roll'";
        $conn->query($sql);

        $conn->query("DELETE FROM tbl_users_bill WHERE id='$id'");
        $conn->query("INSERT INTO tbl_users_bill SELECT * FROM tbl_users WHERE id='$id'");

        $addrCheck = getRow("SELECT id FROM customer_address WHERE UserId='$id'");
        if ($addrCheck > 0) {
            $conn->query("UPDATE customer_address SET Fname='$Fname', Lname='', Phone='$Phone', EmailId='$EmailId',
                Address='$Address', CountryId=0, StateId=0, CityId=0, AreaId=0, Pincode='', Status='1'
                WHERE UserId='$id'");
        } else {
            insertCustomerAddress($conn, $id, $Fname, $Phone, $EmailId, $Address, $CreatedDate);
        }

        $conn->commit();
        echo '1';
    }
} catch (Throwable $e) {
    try { $conn->rollback(); } catch (Throwable $ignored) {}
    error_log('Shop Admin Save Error: ' . $e->getMessage());
    echo 'error';
}
