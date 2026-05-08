<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Selling-Products";
$Page = "Category";
?>
<!DOCTYPE html>
<html lang="en" class="default-style">
<head>
    <title><?php echo $Proj_Title; ?> | Category</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>

    <!-- Bootstrap Icons (for consistent icon style like Discount page) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">

    <style>
        /* =================== GLOBAL UI THEME (Same as Discount Percentage page) =================== */
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

        /* Inputs / Selects */
        .form-control,
        .form-select,
        .custom-file-input,
        .custom-file-label {
            border: 1px solid #d8d1ff;
            border-radius: 10px;
            font-size: 14px;
            padding: 10px 12px;
            box-shadow: none;
        }

        .form-control:focus,
        .form-select:focus,
        .custom-file-input:focus {
            border-color: #7a5fff !important;
            box-shadow: 0 0 0 0.2rem rgba(122, 95, 255, 0.2) !important;
        }

        /* Buttons */
        .btn-primary,
        .btn-purple {
            background-color: #6a4fe0 !important;
            border-color: #6a4fe0 !important;
            border-radius: 10px;
            font-size: 14px;
            padding: 8px 18px;
            font-weight: 600;
        }

        .btn-primary:hover,
        .btn-purple:hover {
            background-color: #5a41c6 !important;
            border-color: #5a41c6 !important;
        }

        .btn-outline-purple {
            border-radius: 10px;
            border-color: #6a4fe0;
            color: #6a4fe0;
            font-weight: 600;
            font-size: 14px;
            padding: 8px 18px;
            background-color: #f8f6ff;
        }

        .btn-outline-purple:hover {
            background-color: #6a4fe0;
            color: #fff;
        }

        /* Table Header */
        .card-datatable table thead {
            background-color: #f5f2ff;
            color: #5b3cc4;
            font-weight: 700;
        }

        /* ================= EDIT / DELETE BUTTONS THEME (Same Pattern as Discount page) ================= */
        .table-action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin: 2px;
            transition: 0.2s ease;
            font-size: 16px;
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
            gap: 6px;
        }

        /* Modal styling to match theme */
        .modal-content {
            border-radius: 18px;
            border: none;
            box-shadow: 0 4px 18px rgba(0,0,0,0.15);
        }

        .modal-header {
            border-bottom: none;
            background-color: #f5f2ff;
            border-radius: 18px 18px 0 0;
        }

        .modal-title {
            font-weight: 700;
            color: #4a3aa0;
        }

        .modal-footer {
            border-top: none;
        }

        footer {
            margin-top: 30px;
            color: #999;
        }
    </style>
</head>
<body>

<div class="layout-wrapper layout-1 layout-without-sidenav">
    <div class="layout-inner">

        <?php include_once 'top_header.php'; ?>
        <?php include_once 'sidebar.php'; ?>

        <div class="layout-container">

            <div class="layout-content">
                <div class="container-fluid flex-grow-1 container-p-y">

                    <!-- ==================== PAGE TITLE + ADD BUTTON ==================== -->
                    <div class="card mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="section-title mb-0">
                                <i class="bi bi-card-list"></i> Category Management
                            </div>
                       
                                <button type="button"
                                        class="btn btn-purple"
                                        data-toggle="modal"
                                        data-target="#modals-default"
                                        id="add_button">
                                    <i class="bi bi-plus-lg"></i> Add Category
                                </button>
                           
                        </div>

                        <p class="text-muted mb-0" style="font-size: 13px;">
                            Manage your selling product categories, images, sort order and status from here.
                        </p>
                    </div>

                    <!-- ==================== CATEGORY LIST CARD ==================== -->
                    <div class="card">
                        <div class="section-title">
                            <i class="bi bi-table"></i> List of Categories
                        </div>
                        <div class="card-datatable table-responsive" id="custresult">
                            <div class="text-center text-muted py-4">Loading categories...</div>
                        </div>
                    </div>

                </div>

                <?php include_once 'footer.php'; ?>

            </div>

        </div>

    </div>

    <div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<!-- ==================== ADD / EDIT CATEGORY MODAL ==================== -->
<div class="modal fade insert_frm" id="modals-default" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Add <span class="font-weight-light">Category</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="action" id="action" value="Add">
                <input type="hidden" name="id" id="id" />
                <input type="hidden" name="OldPhoto" id="OldPhoto">
                <input type="hidden" name="OldPhoto2" id="OldPhoto2">

                <!-- Category Name -->
                <div class="form-row">
                    <div class="form-group col">
                        <label class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="Name" class="form-control" id="Name" placeholder="Category Name" required>
                    </div>
                </div>

                <!-- Category Image -->
                <div class="form-row">
                    <div class="form-group col">
                        <label class="form-label">Category Image</label>
                        <label class="custom-file">
                            <input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;">
                            <span class="custom-file-label">Choose image...</span>
                        </label>
                        <br>
                        <span id="show_photo"></span>
                    </div>
                </div>

                <!-- Sr No -->
                <div class="form-row">
                    <div class="form-group col">
                        <label class="form-label">Sr No <span class="text-danger">*</span></label>
                        <input type="text" name="srno" class="form-control" id="srno" placeholder="Enter sequence number" required>
                    </div>
                </div>

                <!-- Status -->
                <div class="form-row">
                    <div class="form-group col">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-control form-select" id="Status" name="Status" required>
                            <option selected disabled value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Optional containers for second image / icon previews -->
                <span id="show_photo2" style="display:none;"></span>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="submit" name="submit">
                    <i class="bi bi-save"></i> Submit
                </button>
            </div>
        </form>
    </div>
</div>

<?php include_once 'footer_script.php'; ?>

<!-- DataTables Responsive JS -->
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<!-- SweetAlert (same as Discount Percentage page) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
    /* ===================== LOAD CATEGORY LIST ===================== */
    function category_lists() {
        var action = 'view';
        $.ajax({
            type: "POST",
            url: "../ajax_files/ajax_customer_category.php",
            data: { action: action },
            success: function(data) {
                $('#custresult').html(data);
            }
        });
    }

    /* ===================== SUCCESS & ERROR (Same pattern as Discount page) ===================== */
    function successMsg(msg) {
        swal("Success", msg, "success");
    }

    function errorMsg(msg) {
        swal("Error", msg, "error");
    }

    $(document).ready(function() {
        category_lists();

        // Reset & open modal for Add
        $('#add_button').click(function() {
            $('.modal-title').html("Add <span class='font-weight-light'>Category</span>");
            $('#action').val("Add");
            $('#id').val('');
            $('#srno').val('');
            $('#Name').val('');
            $('#Photo').val('');
            $('#OldPhoto').val('');
            $('#OldPhoto2').val('');
            $('#show_photo').hide().html('');
            $('#show_photo2').hide().html('');
            $('#Status').val('').change();
            $('#submit').html('<i class="bi bi-save"></i> Submit');
        });

        // Submit form (Add / Edit)
        $('#validation-form').on('submit', function(e) {
            e.preventDefault();
            var action = $('#action').val();

            if (!$('#Name').val().trim()) {
                errorMsg('Please enter Category Name.');
                return false;
            }
            if (!$('#srno').val().trim()) {
                errorMsg('Please enter Sr No.');
                return false;
            }
            if (!$('#Status').val()) {
                errorMsg('Please select Status.');
                return false;
            }

            $.ajax({
                url: "../ajax_files/ajax_customer_category.php",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#submit').attr('disabled', 'disabled').html('Please Wait...');
                },
                success: function(data) {
                    if (data == 1) {
                        if (action == 'Edit') {
                            successMsg('Category updated successfully!');
                        } else {
                            successMsg('New Category Added Successfully!');
                        }
                        $('.insert_frm').modal('hide');
                        $('#validation-form')[0].reset();
                        $('#action').val("Add");
                    } else {
                        errorMsg('Category Name Already Exists');
                        $('.insert_frm').modal('show');
                    }

                    category_lists();
                    $('#submit').attr('disabled', false).html('<i class="bi bi-save"></i> Submit');
                }
            });
        });

        // Edit category
        $(document).on("click", ".update", function(event) {
            event.preventDefault();
            event.stopPropagation();
            var id = $(this).attr("data-id");
            var action = "fetch_record";

            $.ajax({
                url: "../ajax_files/ajax_customer_category.php",
                method: "POST",
                data: { action: action, id: id },
                dataType: "json",
                success: function(data) {
                    $('#srno').val(data.srno);
                    $('#Name').val(data.Name);
                    $('#OldPhoto').val(data.Photo);
                    $('#Photo').val('');
                    $('#OldPhoto2').val(data.Photo2);
                    $('#Status').val(data.Status).change();
                    $('#action').val('Edit');
                    $('#id').val(id);

                    // Show image 1
                    if (data.Photo == '') {
                        $('#show_photo').hide().html('');
                    } else {
                        $('#show_photo').show().html(
                            '<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3">' +
                            '<a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a>' +
                            '<img src="../uploads/' + data.Photo + '" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;border-radius:10px;">' +
                            '</div>'
                        );
                    }

                    // Show image 2 (if used)
                    if (data.Photo2 == '') {
                        $('#show_photo2').hide().html('');
                    } else {
                        $('#show_photo2').show().html(
                            '<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3">' +
                            '<a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo2"></a>' +
                            '<img src="../uploads/' + data.Photo2 + '" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;border-radius:10px;">' +
                            '</div>'
                        );
                    }

                    $('#submit').html('<i class="bi bi-pencil-square"></i> Update');
                    $('.insert_frm').modal('show');
                    $('.modal-title').html("Update <span class='font-weight-light'>Category</span>");
                }
            });
        });

        // Delete category
        $(document).on("click", ".delete", function(event) {
            event.preventDefault();
            var id = $(this).attr("data-id");
            var action = "delete";

            swal({
                title: "Are you sure?",
                text: "Deleted all records related to this Category & you will not be able to recover this Category!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((isConfirm) => {
                if (isConfirm) {
                    $.ajax({
                        url: "../ajax_files/ajax_customer_category.php",
                        method: "POST",
                        data: { action: action, id: id },
                        success: function(data) {
                            swal("Deleted!", "Category has been deleted.", "success");
                            category_lists();
                        }
                    });
                } else {
                    swal("Cancelled", "Category is safe :)", "error");
                }
            });
        });

        // Delete main photo
        $(document).on("click", "#delete_photo", function(event) {
            event.preventDefault();
            if (confirm("Are you sure you want to delete Category Photo?")) {
                var action = "deletePhoto";
                var id = $('#id').val();
                var Photo = $('#OldPhoto').val();
                $.ajax({
                    url: "../ajax_files/ajax_customer_category.php",
                    method: "POST",
                    data: { action: action, id: id, Photo: Photo },
                    success: function(data) {
                        $('#show_photo').hide().html('');
                        $('#OldPhoto').val('');
                        category_lists();
                        successMsg(data);
                    }
                });
            }
        });

        // Delete secondary photo/icon
        $(document).on("click", "#delete_photo2", function(event) {
            event.preventDefault();
            if (confirm("Are you sure you want to delete Category Icon?")) {
                var action = "deletePhoto2";
                var id = $('#id').val();
                var Photo = $('#OldPhoto2').val();
                $.ajax({
                    url: "../ajax_files/ajax_customer_category.php",
                    method: "POST",
                    data: { action: action, id: id, Photo: Photo },
                    success: function(data) {
                        $('#show_photo2').hide().html('');
                        $('#OldPhoto2').val('');
                        category_lists();
                        successMsg(data);
                    }
                });
            }
        });

    });
</script>
</body>
</html>
