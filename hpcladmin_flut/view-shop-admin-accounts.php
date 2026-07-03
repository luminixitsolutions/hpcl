<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'User-Accounts';
$Page = 'Shop-Admin-Account';

function accessNamesFromIds($table, $ids, $nameCol = 'Name') {
    if ($ids === '' || $ids === '0') {
        return [];
    }
    $ids = preg_replace('/[^0-9,]/', '', $ids);
    if ($ids === '') {
        return [];
    }
    $rows = getList("SELECT $nameCol FROM $table WHERE id IN ($ids) ORDER BY $nameCol");
    return array_column($rows, $nameCol);
}

function dealerAccessNames($ids) {
    if ($ids === '' || $ids === '0') {
        return [];
    }
    $ids = preg_replace('/[^0-9,]/', '', $ids);
    if ($ids === '') {
        return [];
    }
    $rows = getList("SELECT ShopName FROM tbl_users_bill WHERE id IN ($ids) ORDER BY ShopName");
    return array_column($rows, 'ShopName');
}

function franchisePanelAccessNames($ids) {
    if ($ids === '' || $ids === '0') {
        return [];
    }
    $ids = preg_replace('/[^0-9,]/', '', $ids);
    if ($ids === '') {
        return [];
    }
    $rows = getList("SELECT Name FROM tbl_option_billsoft WHERE id IN ($ids) ORDER BY Name");
    return array_column($rows, 'Name');
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | Shop Admin Account List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>
<style>
.access-list { max-height: 220px; overflow-y: auto; margin: 0; padding-left: 18px; }
.access-list li { margin-bottom: 4px; }
.access-empty { color: #888; font-style: italic; }
</style>
</head>
<body>
<div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">
<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>
<div class="layout-container">

<?php
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') {
    $id = $_REQUEST['id'] ?? '';
    if ($id !== '') {
        $conn->query("DELETE FROM tbl_users WHERE id='$id' AND Roll=167");
        $conn->query("DELETE FROM tbl_users_bill WHERE id='$id' AND Roll=167");
    }
?>
<script>alert('Deleted Successfully!');window.location.href='view-shop-admin-accounts.php';</script>
<?php } ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Shop Admin Account List
<span style="float:right;">
<a href="add-shop-admin-account.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a>
</span>
</h4>

<div class="card" style="padding:10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
<thead>
<tr>
<th>Action</th>
<th>Name</th>
<th>Phone</th>
<th>Email</th>
<th>Address</th>
<th>Bank</th>
<th>Status</th>
<th>Register Date</th>
<th>Access</th>
</tr>
</thead>
<tbody>
<?php
$accessMap = [];
$sql = "SELECT * FROM tbl_users_bill WHERE Roll=167 ORDER BY CreatedDate DESC, id DESC";
$res = $conn->query($sql);
if ($res) {
while ($row = $res->fetch_assoc()) {
    $bank = trim(($row['BankName'] ?? '') . ' / ' . ($row['AccountNo'] ?? ''), ' /');
    if ($bank === '') $bank = '-';

    $accessMap[$row['id']] = [
        'name' => $row['Fname'] ?? '',
        'zones' => accessNamesFromIds('tbl_zone', $row['zone'] ?? ''),
        'subzones' => accessNamesFromIds('tbl_sub_zone', $row['subzone'] ?? ''),
        'dealers' => dealerAccessNames($row['CocoFranchiseAccess'] ?? ''),
        'franchisePanel' => franchisePanelAccessNames($row['Options'] ?? ''),
    ];
?>
<tr>
<td>
<a href="add-shop-admin-account.php?id=<?php echo $row['id']; ?>"><i class="lnr lnr-pencil mr-2"></i></a>
<a onclick="return confirm('Delete this shop admin account?');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete"><i class="lnr lnr-trash text-danger"></i></a>
</td>
<td><?php echo htmlspecialchars($row['Fname'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($row['Phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($row['EmailId'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($row['Address'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($bank, ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo ($row['Status'] ?? '') == '1' ? 'Active' : 'Inactive'; ?></td>
<td><?php echo htmlspecialchars($row['CreatedDate'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td>
<button type="button" class="btn btn-sm btn-info btn-view-access" data-id="<?php echo (int)$row['id']; ?>">View Access</button>
</td>
</tr>
<?php } } ?>
</tbody>
</table>
</div>
</div>
</div>

<div class="modal fade" id="accessModal" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Access Details — <span id="accessAdminName"></span></h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
<div class="row">
<div class="col-md-4">
<h6 class="font-weight-bold">Zone Access</h6>
<ul id="accessZones" class="access-list"></ul>
</div>
<div class="col-md-4">
<h6 class="font-weight-bold">Sub Zone Access</h6>
<ul id="accessSubzones" class="access-list"></ul>
</div>
<div class="col-md-4">
<h6 class="font-weight-bold">Dealer Access</h6>
<ul id="accessDealers" class="access-list"></ul>
</div>
</div>
<div class="row mt-3">
<div class="col-md-12">
<h6 class="font-weight-bold">Franchise Panel Access</h6>
<ul id="accessFranchisePanel" class="access-list"></ul>
</div>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
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
var accessMap = <?php echo json_encode($accessMap, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

function renderAccessList(el, items) {
    el.innerHTML = '';
    if (!items || !items.length) {
        el.innerHTML = '<li class="access-empty">None assigned</li>';
        return;
    }
    items.forEach(function(name) {
        var li = document.createElement('li');
        li.textContent = name;
        el.appendChild(li);
    });
}

$(document).ready(function() {
    $('#example').DataTable({ scrollX: true, dom: 'Bfrtip', buttons: ['excelHtml5'] });

    $(document).on('click', '.btn-view-access', function() {
        var id = $(this).data('id');
        var data = accessMap[id] || { name: '', zones: [], subzones: [], dealers: [], franchisePanel: [] };
        document.getElementById('accessAdminName').textContent = data.name || '-';
        renderAccessList(document.getElementById('accessZones'), data.zones);
        renderAccessList(document.getElementById('accessSubzones'), data.subzones);
        renderAccessList(document.getElementById('accessDealers'), data.dealers);
        renderAccessList(document.getElementById('accessFranchisePanel'), data.franchisePanel);
        $('#accessModal').modal('show');
    });
});
</script>
</body>
</html>
