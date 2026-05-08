<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Discount-Percentage";
$Page = "Discount-Percentage";
?>
<!DOCTYPE html>
<html lang="en">

<head>
<title><?php echo $Proj_Title; ?> | Discount Percentage</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include_once 'header_script.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
/* =================== GLOBAL UI THEME =================== */
body {
  background-color: #f6f4fa;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  padding: 12px;
}

/* Main Card */
.card {
  border: none;
  border-radius: 18px;
  background-color: #fff;
  box-shadow: 0 4px 14px rgba(90,60,200,0.08);
  padding: 20px;
}

/* Section Title */
.section-title {
  background-color: #f5f2ff;
  border-left: 5px solid #6a4fe0;
  padding: 10px 14px;
  margin-bottom: 18px;
  font-weight: 700;
  color: #4a3aa0;
  border-radius: 6px;
  font-size: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Labels */
.form-label {
  font-weight: 600;
  font-size: 14px;
  color: #4a3aa0;
}

/* Inputs */
.form-control, .form-select {
  border: 1px solid #d8d1ff;
  border-radius: 10px;
  font-size: 14px;
  padding: 10px 12px;
  box-shadow: none;
}

.form-control:focus, .form-select:focus {
  border-color: #7a5fff !important;
  box-shadow: 0 0 0 0.2rem rgba(122, 95, 255, 0.2) !important;
}

/* Buttons */
.btn-primary {
  background-color: #6a4fe0 !important;
  border-radius: 10px;
  font-size: 14px;
  padding: 10px 20px;
  font-weight: 600;
}
.btn-primary:hover {
  background-color: #5a41c6 !important;
}

.table thead {
  background-color: #f5f2ff;
  color: #5b3cc4;
  font-weight: 700;
}

footer {
  margin-top: 30px;
  color: #999;
}
/* ================= EDIT / DELETE BUTTONS THEME ================= */

.table-action-btn {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  margin: 2px;
  transition: 0.2s ease;
}

/* EDIT BUTTON (Purple) */
.btn-edit {
  background: #e9e3ff;
  color: #6a4fe0;
}
.btn-edit:hover {
  background: #d8ccff;
  color: #4c2fd1;
}

/* DELETE BUTTON (Soft Red) */
.btn-delete {
  background: #ffe5e5;
  color: #d72828;
}
.btn-delete:hover {
  background: #ffd1d1;
  color: #b51919;
}
.action-buttons {
  display: flex;
  align-items: center;
  gap: 6px;   /* space between buttons */
}

</style>
</head>

<body>

<div class="container-fluid">

  <!-- ==================== ADD / EDIT DISCOUNT ==================== -->
  <div class="card mb-4">

    <div class="section-title">
      <i class="bi bi-percent"></i> Manage Discount Percentage
    </div>

    <form id="discountForm" method="post" autocomplete="off">

      <input type="hidden" name="action" id="action" value="Add">
      <input type="hidden" name="id" id="id">
      <input type="hidden" name="FrId" id="FrId" value="<?php echo $_REQUEST['user_id'];?>">

      <div class="row align-items-end g-3">

        <div class="col-md-4">
          <label class="form-label">Discount % <span class="text-danger">*</span></label>
          <input type="number" name="Name" id="Name" class="form-control" placeholder="Enter discount %" required>
        </div>

        <div class="col-md-2">
          <button type="submit" id="submit" class="btn btn-primary w-100">
            <i class="bi bi-save"></i> Save
          </button>
        </div>

      </div>

    </form>

  </div>

  <!-- ==================== DISCOUNT TABLE ==================== -->
  <div class="card">
    <div class="section-title">
      <i class="bi bi-table"></i> Discount List
    </div>

    <div class="table-responsive">
      <div id="custresult" class="text-center text-muted py-4">Loading discounts...</div>
    </div>
  </div>

</div>

<footer class="text-center small">© <?php echo date('Y'); ?> <?php echo $Proj_Title; ?></footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert/dist/sweetalert.min.js"></script>

<script>
/* ===================== LOAD DISCOUNT LIST ===================== */
function loadDiscounts() {
    var FrId = $('#FrId').val();
  $.ajax({
    type: "POST",
    url: "../ajax_files/ajax_discount_percentage.php",
    data: { action: 'view',FrId:FrId },
    success: function(data) {
      $('#custresult').html(data);
    }
  });
}

/* ===================== SUCCESS & ERROR ===================== */
function successMsg(msg) {
  swal("Success", msg, "success");
}
function errorMsg(msg) {
  swal("Error", msg, "error");
}

/* ===================== DOCUMENT READY ===================== */
$(document).ready(function() {

  loadDiscounts(); // load table first time

  // Add / Edit Discount
  $('#discountForm').on('submit', function(e) {
    e.preventDefault();

    if ($('#Name').val().trim() === '') {
      errorMsg('Please enter discount percentage.');
      return;
    }

    $.ajax({
      url: "../ajax_files/ajax_discount_percentage.php",
      method: "POST",
      data: new FormData(this),
      contentType: false,
      processData: false,
      beforeSend: function() {
        $('#submit').attr('disabled', true).text('Saving...');
      },
      success: function(data) {

        if (data == 1) {
          let action = $('#action').val();
          successMsg(action === 'Edit' ? "Discount updated!" : "Discount added!");

          $('#discountForm')[0].reset();
          $('#action').val('Add');
          $('#id').val('');
        } 
        else {
          errorMsg("This discount already exists.");
        }

        loadDiscounts();
        $('#submit').attr('disabled', false).html('<i class="bi bi-save"></i> Save');
      }
    });
  });

  // Edit Discount
  $(document).on("click", ".update", function() {
    var id = $(this).data("id");

    $.ajax({
      url: "../ajax_files/ajax_discount_percentage.php",
      method: "POST",
      data: { action: "fetch_record", id: id },
      dataType: "json",
      success: function(data) {
        $('#Name').val(data.Percentage);
        $('#id').val(id);
        $('#action').val('Edit');
        $('#submit').html('<i class="bi bi-pencil-square"></i> Update');
      }
    });
  });

  // Delete Discount
  $(document).on("click", ".delete", function() {
    var id = $(this).data("id");

    swal({
      title: "Are you sure?",
      text: "This record will be permanently deleted.",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((yesDelete) => {
      if (yesDelete) {
        $.ajax({
          url: "../ajax_files/ajax_discount_percentage.php",
          method: "POST",
          data: { action: "delete", id: id },
          success: function() {
            successMsg("Discount deleted successfully!");
            loadDiscounts();
          }
        });
      }
    });
  });

});
</script>

</body>
</html>
