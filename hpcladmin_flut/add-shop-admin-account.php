<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'User-Accounts';
$Page = 'Shop-Admin-Account';
$id = $_GET['id'] ?? '';

if ($id !== '') {
    $sql7 = "SELECT * FROM tbl_users_bill WHERE id='$id' AND Roll=167";
    $row7 = getRecord($sql7);
    if (!$row7) {
        $row7 = [];
    }
} else {
    $row7 = [];
}

$row7Defaults = [
    'Fname' => '', 'Phone' => '', 'Phone2' => '', 'EmailId' => '', 'Password' => '',
    'Address' => '', 'Status' => '1', 'AccountName' => '', 'BankName' => '',
    'AccountNo' => '', 'Branch' => '', 'IfscCode' => '', 'UpiNo' => '',
    'zone' => '', 'subzone' => '', 'CocoFranchiseAccess' => '', 'Options' => '',
];
$row7 = array_merge($row7Defaults, is_array($row7) ? $row7 : []);
$row7['zone'] = !empty($row7['zone']) ? explode(',', $row7['zone']) : [];
$row7['subzone'] = !empty($row7['subzone']) ? explode(',', $row7['subzone']) : [];
$row7['CocoFranchiseAccess'] = !empty($row7['CocoFranchiseAccess']) ? explode(',', $row7['CocoFranchiseAccess']) : [];
$selectedFrPanelOptions = array_filter(array_map('intval', explode(',', (string)($row7['Options'] ?? ''))));

function renderFrPanelOptionsSection(string $title, array $ids, array $selected, ?int $roll = null) {
    if (empty($ids)) {
        return;
    }
    $idsList = implode(',', array_map('intval', $ids));
    $rollWhere = is_null($roll) ? '' : ' AND Roll=' . (int)$roll;
    $sql = "SELECT id, Name FROM tbl_option_billsoft WHERE id IN ($idsList) $rollWhere ORDER BY FIELD(id, $idsList)";
    $rows = getList($sql);
    echo '<div class="form-group col-md-12"><label class="form-label font-weight-bold">' . htmlspecialchars($title) . '</label></div>';
    foreach ($rows as $r) {
        $optId = (int)$r['id'];
        $isChecked = in_array($optId, $selected, true) ? 'checked' : '';
        echo '<div class="form-group col-md-4 fr-panel-option-item">';
        echo '<label class="custom-control custom-checkbox">';
        echo '<input type="checkbox" class="custom-control-input fr-panel-option-checkbox" name="FrPanelOptions[]" value="' . $optId . '" ' . $isChecked . '>';
        echo '<span class="custom-control-label">' . htmlspecialchars($r['Name']) . '</span>';
        echo '</label></div>';
    }
}

$franchisePanelSections = [
    ['title' => 'Other Access', 'ids' => [71, 72, 73, 112, 113, 127], 'roll' => 2],
    ['title' => 'Orders', 'ids' => [74, 75, 76, 77, 78, 79], 'roll' => 2],
    ['title' => 'Inventory Reports', 'ids' => [80, 81, 82, 83, 84, 85], 'roll' => 2],
    ['title' => 'Reports', 'ids' => [86, 87, 88, 89, 90, 91, 92, 93, 94], 'roll' => 2],
    ['title' => 'Selling Products', 'ids' => [95, 96, 97, 98, 99, 100, 101], 'roll' => 2],
    ['title' => 'Raw Products', 'ids' => [102, 103, 104, 105, 106], 'roll' => 2],
    ['title' => 'Receive/Transfer Stocks', 'ids' => [109, 110, 111, 128], 'roll' => 2],
    ['title' => 'Action', 'ids' => [114, 115, 116, 117], 'roll' => 2],
];
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> - <?php echo $id ? 'Edit' : 'Add'; ?> Shop Admin Account</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>
<style>
fieldset { background:#fff; border:1px solid #4FAFB8; border-radius:5px; margin:20px 0 1px; padding:20px; position:relative; }
fieldset legend { background:inherit; color:#650812; font-size:15px; left:10px; padding:0 10px; position:absolute; top:-12px; width:auto!important; border:none!important; }
</style>
</head>
<body>
<div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">
<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>
<div class="layout-container">
<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0"><?php echo $id ? 'Edit' : 'Add'; ?> Shop Admin Account</h4>

<div class="card mb-4">
<div class="card-body">
<form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
<input type="hidden" name="action" value="Save">

<fieldset>
<legend>Basic Details</legend>
<div class="form-row">
<div class="form-group col-md-6">
<label class="form-label">Name <span class="text-danger">*</span></label>
<input type="text" name="Fname" class="form-control" value="<?php echo htmlspecialchars($row7['Fname'], ENT_QUOTES, 'UTF-8'); ?>" required>
</div>
<div class="form-group col-md-3">
<label class="form-label">Phone No <span class="text-danger">*</span></label>
<input type="text" name="Phone" class="form-control" value="<?php echo htmlspecialchars($row7['Phone'], ENT_QUOTES, 'UTF-8'); ?>" required maxlength="10">
</div>
<div class="form-group col-md-3">
<label class="form-label">Alternate Phone</label>
<input type="text" name="Phone2" class="form-control" value="<?php echo htmlspecialchars($row7['Phone2'], ENT_QUOTES, 'UTF-8'); ?>">
</div>
<div class="form-group col-md-6">
<label class="form-label">Email Id</label>
<input type="email" name="EmailId" class="form-control" value="<?php echo htmlspecialchars($row7['EmailId'], ENT_QUOTES, 'UTF-8'); ?>">
</div>
<div class="form-group col-md-3">
<label class="form-label">Password <span class="text-danger">*</span></label>
<input type="text" name="Password" class="form-control" value="<?php echo htmlspecialchars($row7['Password'], ENT_QUOTES, 'UTF-8'); ?>" required>
</div>
<div class="form-group col-md-3">
<label class="form-label">Status <span class="text-danger">*</span></label>
<select class="form-control" name="Status" required>
<option value="1" <?php if ($row7['Status'] == '1') { ?>selected<?php } ?>>Active</option>
<option value="0" <?php if ($row7['Status'] == '0') { ?>selected<?php } ?>>Inactive</option>
</select>
</div>
<div class="form-group col-md-12">
<label class="form-label">Address</label>
<textarea name="Address" class="form-control" rows="2"><?php echo htmlspecialchars($row7['Address'], ENT_QUOTES, 'UTF-8'); ?></textarea>
</div>
</div>
</fieldset>

<fieldset>
<legend>Zone Access</legend>
<div class="form-group">
<label class="form-label">Zone <span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="zone[]" id="zoneSelect" multiple required>
<?php
$zones = getList("SELECT * FROM tbl_zone WHERE Status=1 ORDER BY Name");
foreach ($zones as $z) {
    $sel = in_array((string)$z['id'], $row7['zone'], true) || in_array($z['id'], $row7['zone'], true) ? 'selected' : '';
    echo '<option value="'.$z['id'].'" '.$sel.'>'.htmlspecialchars($z['Name']).'</option>';
}
?>
</select>
</div>
</fieldset>

<fieldset>
<legend>Sub Zone Access</legend>
<div class="form-group">
<label class="form-label">Sub Zone <span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="subzone[]" id="subzoneSelect" multiple required>
<?php
$subzones = getList("SELECT * FROM tbl_sub_zone WHERE Status=1 ORDER BY Name");
foreach ($subzones as $sz) {
    $sel = in_array((string)$sz['id'], $row7['subzone'], true) || in_array($sz['id'], $row7['subzone'], true) ? 'selected' : '';
    echo '<option value="'.$sz['id'].'" data-zone="'.$sz['CatId'].'" '.$sel.'>'.htmlspecialchars($sz['Name']).'</option>';
}
?>
</select>
</div>
</fieldset>

<fieldset>
<legend><input type="checkbox" id="selectAllDealers" style="margin-left:10px;"> Dealer Access</legend>
<div class="form-row" id="dealerList">
<?php
$dealers = getList("SELECT id, ShopName, ZoneId, SubZoneId FROM tbl_users_bill WHERE Roll=5 AND Status=1 ORDER BY ShopName");
foreach ($dealers as $d) {
    $checked = in_array((string)$d['id'], $row7['CocoFranchiseAccess'], true) || in_array($d['id'], $row7['CocoFranchiseAccess'], true) ? 'checked' : '';
?>
<div class="form-group col-md-4 dealer-item" data-zone="<?php echo $d['ZoneId']; ?>" data-subzone="<?php echo $d['SubZoneId']; ?>">
<label class="custom-control custom-checkbox">
<input type="checkbox" class="custom-control-input dealer-checkbox" name="CocoFranchiseAccess[]" value="<?php echo $d['id']; ?>" <?php echo $checked; ?>>
<span class="custom-control-label"><?php echo htmlspecialchars($d['ShopName']); ?></span>
</label>
</div>
<?php } ?>
</div>
</fieldset>

<fieldset>
<legend><input type="checkbox" id="selectAllFrPanel" style="margin-left:10px;"> Franchise Panel Access</legend>
<p class="text-muted small mb-3">Select which franchise panel menus this shop admin can use when opening a dealer panel.</p>
<div class="form-row" id="frPanelOptionsList">
<?php
foreach ($franchisePanelSections as $sec) {
    renderFrPanelOptionsSection($sec['title'], $sec['ids'], $selectedFrPanelOptions, $sec['roll']);
}
?>
</div>
</fieldset>

<fieldset>
<legend>Bank Account Details</legend>
<div class="form-row">
<div class="form-group col-md-6">
<label class="form-label">Account Holder Name</label>
<input type="text" name="AccountName" class="form-control" value="<?php echo htmlspecialchars($row7['AccountName'], ENT_QUOTES, 'UTF-8'); ?>">
</div>
<div class="form-group col-md-6">
<label class="form-label">Bank Name</label>
<input type="text" name="BankName" class="form-control" value="<?php echo htmlspecialchars($row7['BankName'], ENT_QUOTES, 'UTF-8'); ?>">
</div>
<div class="form-group col-md-4">
<label class="form-label">Account No</label>
<input type="text" name="AccountNo" class="form-control" value="<?php echo htmlspecialchars($row7['AccountNo'], ENT_QUOTES, 'UTF-8'); ?>">
</div>
<div class="form-group col-md-4">
<label class="form-label">Branch</label>
<input type="text" name="Branch" class="form-control" value="<?php echo htmlspecialchars($row7['Branch'], ENT_QUOTES, 'UTF-8'); ?>">
</div>
<div class="form-group col-md-4">
<label class="form-label">IFSC Code</label>
<input type="text" name="IfscCode" class="form-control" value="<?php echo htmlspecialchars($row7['IfscCode'], ENT_QUOTES, 'UTF-8'); ?>">
</div>
<div class="form-group col-md-6">
<label class="form-label">UPI ID</label>
<input type="text" name="UpiNo" class="form-control" value="<?php echo htmlspecialchars($row7['UpiNo'], ENT_QUOTES, 'UTF-8'); ?>">
</div>
</div>
</fieldset>

<button type="submit" class="btn btn-primary" id="submit">Save</button>
<a href="view-shop-admin-accounts.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</div>
</div>
<?php include_once 'footer.php'; ?>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>
<?php include_once 'footer_script.php'; ?>
<script>

document.getElementById('selectAllDealers').addEventListener('change', function() {
    document.querySelectorAll('.dealer-checkbox').forEach(function(cb) {
        var item = cb.closest('.dealer-item');
        if (item && item.style.display !== 'none') {
            cb.checked = this.checked;
        }
    }, this);
});

document.getElementById('selectAllFrPanel').addEventListener('change', function() {
    document.querySelectorAll('.fr-panel-option-checkbox').forEach(function(cb) {
        cb.checked = this.checked;
    }, this);
});

document.querySelectorAll('.fr-panel-option-checkbox').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var all = document.querySelectorAll('.fr-panel-option-checkbox');
        var allChecked = all.length && Array.from(all).every(function(c) { return c.checked; });
        document.getElementById('selectAllFrPanel').checked = allChecked;
    });
});

function getSelectedZones() {
    return ($('#zoneSelect').val() || []).map(String);
}

function getSelectedSubzones() {
    return ($('#subzoneSelect').val() || []).map(String);
}

function applyZoneSelection(autoSelect) {
    var zones = getSelectedZones();
    var subzonesToSelect = [];

    $('#subzoneSelect option').each(function() {
        var zoneId = String($(this).data('zone'));
        var inZone = zones.indexOf(zoneId) !== -1;
        $(this).prop('disabled', zones.length > 0 && !inZone);
        if (autoSelect && zones.length > 0 && inZone) {
            subzonesToSelect.push($(this).val());
        }
    });

    if (autoSelect) {
        if (zones.length > 0) {
            $('#subzoneSelect').val(subzonesToSelect).trigger('change.select2');
        } else {
            $('#subzoneSelect').val(null).trigger('change.select2');
        }
        syncDealers(true);
    } else {
        $('#subzoneSelect').trigger('change.select2');
        syncDealers(false);
    }
}

function syncDealers(autoCheck) {
    var zones = getSelectedZones();
    var subzones = getSelectedSubzones();

    document.querySelectorAll('.dealer-item').forEach(function(el) {
        var dz = String(el.dataset.zone || '');
        var dsz = String(el.dataset.subzone || '');
        var cb = el.querySelector('.dealer-checkbox');
        var visible = true;
        var shouldCheck = false;

        if (zones.length) {
            visible = zones.indexOf(dz) !== -1;
            shouldCheck = visible;
        }
        if (subzones.length) {
            visible = visible && subzones.indexOf(dsz) !== -1;
            shouldCheck = subzones.indexOf(dsz) !== -1;
        }

        if (zones.length || subzones.length) {
            el.style.display = visible ? '' : 'none';
            if (autoCheck) {
                cb.checked = shouldCheck;
            }
        } else {
            el.style.display = '';
            if (autoCheck) {
                cb.checked = false;
            }
        }
    });

    var allVisible = document.querySelectorAll('.dealer-item:not([style*="display: none"]) .dealer-checkbox');
    var allChecked = allVisible.length && Array.from(allVisible).every(function(cb) { return cb.checked; });
    document.getElementById('selectAllDealers').checked = allChecked;
}

$(document).ready(function() {
    applyZoneSelection(false);

    var frPanelAll = document.querySelectorAll('.fr-panel-option-checkbox');
    if (frPanelAll.length) {
        document.getElementById('selectAllFrPanel').checked = Array.from(frPanelAll).every(function(cb) { return cb.checked; });
    }

    $('#zoneSelect').on('change', function() {
        applyZoneSelection(true);
    });

    $('#subzoneSelect').on('change', function() {
        syncDealers(true);
    });

    $('#validation-form').on('submit', function(e) {
        e.preventDefault();
        if (!$('#validation-form').valid()) return;
        $.ajax({
            url: 'ajax_files/ajax_shop_admin.php',
            method: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#submit').prop('disabled', true).text('Please Wait...');
            },
            success: function(data) {
                data = String(data).trim();
                var msg = 'Save failed. Please try again.';
                if (data === 'duplicate') {
                    msg = 'This phone number is already registered with another account. Use a different phone number.';
                } else if (data === 'required') {
                    msg = 'Please fill Name, Phone, and Password.';
                } else if (data === 'access') {
                    msg = 'Please select at least one Zone and Sub Zone.';
                } else if (data === '1') {
                    alert('Saved successfully!');
                    window.location.href = 'view-shop-admin-accounts.php';
                    return;
                }
                alert(msg);
                $('#submit').prop('disabled', false).text('Save');
            },
            error: function() {
                alert('Save failed. Please try again.');
                $('#submit').prop('disabled', false).text('Save');
            }
        });
    });
});
</script>
</body>
</html>
