<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

if (!isShopAdmin()) {
    header('Location: dashboard.php');
    exit;
}

$MainPage = 'Shop-Admin-Dealers';
$Page = 'Shop-Admin-Dealers';
$adminRow = $_SESSION['Admin'] ?? [];
$dealers = shopAdminDealerListDetailed($adminRow);
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | My Dealers</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>
<style>
.text-truncate-2 {
    max-width: 220px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
</head>
<body>
<div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">
<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>
<div class="layout-container">

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">My Assigned Dealers</h4>

<div class="card" style="padding:10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
<thead>
<tr>
<th>Action</th>
<th>Dealer ID</th>
<th>Zone</th>
<th>Sub Zone</th>
<th>Dealer Name</th>
<th>Shop Name</th>
<th>Contact No</th>
<th>Address</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php foreach ($dealers as $dealer) {
        $dealerName = trim(($dealer['Fname'] ?? '') . ' ' . ($dealer['Lname'] ?? ''));
        $phones = array_filter([trim($dealer['Phone'] ?? ''), trim($dealer['Phone2'] ?? '')]);
        $contact = !empty($phones) ? implode(' / ', $phones) : '-';
        $panelUrl = appPageUrl('fr_acc/dashboard.php?id=' . (int)$dealer['id']);
?>
<tr>
<td class="nowrap">
<a href="<?php echo htmlspecialchars($panelUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-sm btn-primary">Open Panel</a>
</td>
<td><?php echo htmlspecialchars($dealer['CustomerId'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($dealer['ZoneName'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($dealer['SubZoneName'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($dealerName, ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($dealer['ShopName'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
<td class="nowrap"><?php echo htmlspecialchars($contact, ENT_QUOTES, 'UTF-8'); ?></td>
<td><div class="text-truncate-2" title="<?php echo htmlspecialchars($dealer['Address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($dealer['Address'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></div></td>
<td><?php echo ($dealer['Status'] ?? '') == '1' ? 'Active' : 'Inactive'; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
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
$(document).ready(function() {
    $('#example').DataTable({
        scrollX: true,
        dom: 'Bfrtip',
        buttons: ['excelHtml5'],
        order: [[5, 'asc']]
    });
});
</script>
</body>
</html>
