<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once __DIR__ . '/../../flutter_api/fradmin/manage_stocks/batch_helper.php';

$user_id = $_SESSION['Admin']['id'] ?? 0;
$MainPage = 'Customer-Products-2025';
$Page = 'Batch-Master';
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
<title><?php echo $Proj_Title; ?> | Batch Master</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-1 layout-without-sidenav">
<div class="layout-inner">
<?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>
<div class="layout-container">
<?php
ensureBatchMasterTable($conn);
$batches = getBatchListByFrId($conn, $BillSoftFrId, false);
?>
<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Batch Master</h4>

<div class="card mb-4">
<div class="card-body">
<input type="hidden" id="editId" value="0">
<div class="form-row align-items-end">
<div class="form-group col-md-4">
<label class="form-label">Batch No <span class="text-danger">*</span></label>
<input type="text" id="BatchNo" class="form-control" placeholder="Enter batch number">
</div>
<div class="form-group col-md-3">
<label class="form-label">Default Expiry</label>
<input type="date" id="ExpDate" class="form-control">
</div>
<div class="form-group col-md-2">
<label class="form-label">Status</label>
<select id="Status" class="form-control">
<option value="1">Active</option>
<option value="0">Inactive</option>
</select>
</div>
<div class="form-group col-md-3">
<button type="button" class="btn btn-primary" id="saveBatch">Save</button>
<button type="button" class="btn btn-secondary" id="resetForm">Reset</button>
</div>
</div>
</div>
</div>

<div class="card" style="padding:10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
<thead>
<tr>
<th>Sr No</th>
<th>Batch No</th>
<th>Default Expiry</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php if (empty($batches)) { ?>
<tr><td colspan="5" class="text-center text-muted">No batch records found.</td></tr>
<?php } else { $sr = 1; foreach ($batches as $b) { ?>
<tr data-id="<?php echo (int)$b['id']; ?>">
<td><?php echo $sr++; ?></td>
<td class="batch-no"><?php echo htmlspecialchars($b['BatchNo']); ?></td>
<td class="exp-date"><?php echo !empty($b['ExpDate']) ? htmlspecialchars($b['ExpDate']) : '-'; ?></td>
<td class="status-text"><?php echo ((int)$b['Status'] === 1) ? 'Active' : 'Inactive'; ?></td>
<td>
<button type="button" class="btn btn-sm btn-warning edit-batch"><i class="lnr lnr-pencil"></i></button>
<button type="button" class="btn btn-sm btn-danger delete-batch"><i class="lnr lnr-trash"></i></button>
</td>
</tr>
<?php } } ?>
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
function resetBatchForm() {
    $('#editId').val(0);
    $('#BatchNo').val('');
    $('#ExpDate').val('');
    $('#Status').val('1');
}

$('#resetForm').on('click', resetBatchForm);

$('#saveBatch').on('click', function () {
    var batchNo = $('#BatchNo').val().trim();
    if (!batchNo) {
        alert('Please enter Batch No');
        return;
    }
    $.post('ajax_files/ajax_batch_master.php', {
        action: 'save',
        id: $('#editId').val(),
        BatchNo: batchNo,
        ExpDate: $('#ExpDate').val(),
        Status: $('#Status').val()
    }, function (res) {
        if (res.status === 'success') {
            alert(res.message);
            location.reload();
        } else {
            alert(res.message || 'Failed to save batch');
        }
    }, 'json').fail(function () {
        alert('Failed to save batch');
    });
});

$(document).on('click', '.edit-batch', function () {
    var row = $(this).closest('tr');
    $('#editId').val(row.data('id'));
    $('#BatchNo').val(row.find('.batch-no').text().trim());
    var exp = row.find('.exp-date').text().trim();
    $('#ExpDate').val(exp === '-' ? '' : exp);
    $('#Status').val(row.find('.status-text').text().trim() === 'Active' ? '1' : '0');
    $('html, body').animate({ scrollTop: 0 }, 200);
});

$(document).on('click', '.delete-batch', function () {
    if (!confirm('Delete this batch?')) return;
    var id = $(this).closest('tr').data('id');
    $.post('ajax_files/ajax_batch_master.php', {
        action: 'delete',
        id: id
    }, function (res) {
        if (res.status === 'success') {
            alert(res.message);
            location.reload();
        } else {
            alert(res.message || 'Failed to delete batch');
        }
    }, 'json');
});

$(document).ready(function () {
    if ($('#example tbody tr').length && !$('#example tbody tr td[colspan]').length) {
        $('#example').DataTable({ scrollX: true, order: [[1, 'asc']] });
    }
});
</script>
</body>
</html>
