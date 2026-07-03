<?php
header('Content-Type: text/html; charset=utf-8');
include '../config.php';
include 'batch_helper.php';

$FrId = (int) ($_REQUEST['user_id'] ?? 1);
ensureBatchMasterTable($conn);
$batches = getBatchListByFrId($conn, $FrId, false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Batch Master - Distributor Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
  body { background:#fff; font-family:'Inter','Segoe UI',sans-serif; color:#2f2f2f; }
  .page-title { font-size:1.4rem; font-weight:700; color:#5b3cc4; text-align:center; margin-bottom:20px; }
  .card-box { background:#f9f8ff; border:1px solid #e0dafc; border-radius:12px; padding:16px; margin-bottom:20px; }
  .table thead th { background:#6a4fe0 !important; color:#fff !important; font-size:13px; text-align:center; }
  .table td { font-size:13px; text-align:center; vertical-align:middle; border-color:#e3dfff !important; }
  .btn-primary { background:#6a4fe0; border:none; }
  .btn-primary:hover { background:#5a41c6; }
</style>
</head>
<body>
<div class="p-3">
  <div class="page-title">Batch No Master</div>

  <input type="hidden" id="FrId" value="<?php echo $FrId; ?>">
  <input type="hidden" id="CreatedBy" value="<?php echo $FrId; ?>">
  <input type="hidden" id="editId" value="0">

  <div class="card-box">
    <div class="row g-3 align-items-end">
      <div class="col-md-4">
        <label class="form-label fw-semibold">Batch No <span class="text-danger">*</span></label>
        <input type="text" id="BatchNo" class="form-control" placeholder="Enter batch number">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold">Default Expiry</label>
        <input type="date" id="ExpDate" class="form-control">
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold">Status</label>
        <select id="Status" class="form-select">
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-primary" id="saveBatch"><i class="bi bi-save"></i> Save</button>
        <button class="btn btn-secondary" id="resetForm">Reset</button>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered align-middle">
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
        <?php if (count($batches) > 0) { $sr = 1; foreach ($batches as $b) { ?>
        <tr data-id="<?php echo $b['id']; ?>">
          <td><?php echo $sr++; ?></td>
          <td class="batch-no"><?php echo htmlspecialchars($b['BatchNo']); ?></td>
          <td class="exp-date"><?php echo $b['ExpDate'] ? htmlspecialchars($b['ExpDate']) : '-'; ?></td>
          <td><?php echo $b['Status'] == 1 ? 'Active' : 'Inactive'; ?></td>
          <td>
            <button class="btn btn-sm btn-warning edit-batch"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-danger delete-batch"><i class="bi bi-trash"></i></button>
          </td>
        </tr>
        <?php } } else { ?>
        <tr><td colspan="5" class="text-center">No batch records found.</td></tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <div class="text-center mt-3">
    <a href="add-new-stock.php?user_id=<?php echo $FrId; ?>" class="btn btn-secondary">
      <i class="bi bi-arrow-left"></i> Back to Add Stock
    </a>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
function resetBatchForm() {
  $('#editId').val(0);
  $('#BatchNo').val('');
  $('#ExpDate').val('');
  $('#Status').val('1');
}

$('#resetForm').on('click', resetBatchForm);

$('#saveBatch').on('click', function () {
  const batchNo = $('#BatchNo').val().trim();
  if (!batchNo) {
    alert('Please enter Batch No');
    return;
  }

  $.post('save-batch-master.php', {
    action: 'save',
    id: $('#editId').val(),
    FrId: $('#FrId').val(),
    CreatedBy: $('#CreatedBy').val(),
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
  const row = $(this).closest('tr');
  $('#editId').val(row.data('id'));
  $('#BatchNo').val(row.find('.batch-no').text().trim());
  const exp = row.find('.exp-date').text().trim();
  $('#ExpDate').val(exp === '-' ? '' : exp);
  $('#Status').val(row.find('td').eq(3).text().trim() === 'Active' ? '1' : '0');
});

$(document).on('click', '.delete-batch', function () {
  if (!confirm('Delete this batch?')) return;
  const id = $(this).closest('tr').data('id');
  $.post('save-batch-master.php', {
    action: 'delete',
    id: id,
    FrId: $('#FrId').val()
  }, function (res) {
    if (res.status === 'success') {
      alert(res.message);
      location.reload();
    } else {
      alert(res.message || 'Failed to delete batch');
    }
  }, 'json');
});
</script>
</body>
</html>
