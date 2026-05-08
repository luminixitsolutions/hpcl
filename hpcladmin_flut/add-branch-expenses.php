<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Branch-Expenses";
$Page = "Add-Branch-Expenses";

?>
<!DOCTYPE html>
<html lang="en" class="default-style">

<head>
    <title><?php echo $Proj_Title; ?> | <?php echo $Label; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <?php include_once 'header_script.php'; ?>
</head>

<body>

     <div class="layout-wrapper layout-1 layout-without-sidenav">
        <div class="layout-inner">

            <?php include_once 'top_header.php'; include_once 'sidebar.php'; ?>


            <div class="layout-container">

                

                <?php
                $id = $_GET['id'];
                $sql7 = "SELECT * FROM tbl_branch_expenses WHERE id='$id'";
                $res7 = $conn->query($sql7);
                $row7 = $res7->fetch_assoc();
                ?>

                <?php
                if (isset($_POST['submit'])) {
                    $BranchId = addslashes(trim($_POST["BranchId"]));
                    $Status = $_POST["Status"];
                    $AccHeadId = $_POST['AccHeadId'];

                    $Narration = addslashes(trim($_POST["Narration"]));
                    $Amount = addslashes(trim($_POST["Amount"]));
                    $PaymentMode = addslashes(trim($_POST["PaymentMode"]));
                    $ExpenseDate = addslashes(trim($_POST["ExpenseDate"]));
                    $CreatedDate = date('Y-m-d');
                    $ModifiedDate = date('Y-m-d');

                   
                    if ($_GET['id'] == '') {
                        $qx = "INSERT INTO tbl_branch_expenses SET BranchId = '$BranchId',Status='$Status',AccHeadId='$AccHeadId',Narration = '$Narration',Amount='$Amount',PaymentMode = '$PaymentMode',ExpenseDate='$ExpenseDate',CreatedDate='$CreatedDate',CreatedBy='$user_id',CrDr='dr'";
                        $conn->query($qx);
                        
                        echo "<script>alert('Expenses Added Successfully!');window.location.href='view-branch-expenses.php';</script>";
                    } else {

                        $query2 = "UPDATE tbl_branch_expenses SET BranchId = '$BranchId',Status='$Status',AccHeadId='$AccHeadId',Narration = '$Narration',Amount='$Amount',PaymentMode = '$PaymentMode',ExpenseDate='$ExpenseDate',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id' WHERE id = '$id'";
                        $conn->query($query2);
                        $ExpenseId = $id;

                      
                        echo "<script>alert('Expenses Updated Successfully!');window.location.href='view-branch-expenses.php';</script>";
                    }
                    //header('Location:courses.php'); 

                }
                ?>


                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Branch Expenses</h4>


                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" enctype="multipart/form-data">
                                    <div class="form-row">

                                        <div class="form-group col-md-4">
                                            <label class="form-label"> Branch<span class="text-danger">*</span></label>
                                           <select class="select2-demo form-control" style="width: 100%" data-allow-clear="true" name="BranchId" id="BranchId">
                                                <option>Search Branch</option>
                                                <?php
                                                $sql4 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=5 AND OwnFranchise=1";
                                                $row4 = getList($sql4);
                                                foreach ($row4 as $result) {
                                                ?>
                                                    <option <?php if ($row7["BranchId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Fname'] . " - " . $result['Phone']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-4">
                                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                                            <input type="text" name="Amount" class="form-control" id="Amount" placeholder="" value="<?php echo $row7["Amount"]; ?>" required>
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-lg-4">
                                            <label class="form-label">Expense Date </label>
                                            <input type="date" name="ExpenseDate" class="form-control" id="ExpenseDate" placeholder="" value="<?php echo $row7["ExpenseDate"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>



                                        <div class="form-group col-md-3">
                                            <label class="form-label"> Payment Type<span class="text-danger">*</span></label>
                                            <select class="form-control" name="PaymentMode" id="PaymentMode" required>
                                                <option selected="" value="" disabled>Select Payment Type</option>
                                                <option value="Cash" <?php if ($row7["PaymentMode"] == 'Cash') { ?> selected <?php } ?>>
                                                    By Cash</option>
                                                <option value="Online" <?php if ($row7["PaymentMode"] == 'Online') { ?> selected <?php } ?>>
                                                    By Online</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-lg-9">
                                            <label class="form-label">Narration </label>
                                            <input type name="Narration" class="form-control" placeholder="Narration" value="<?php echo $row7["Narration"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-12">
  <label class="form-label"> Upload Bill </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldPhoto" value="<?php echo $row7['Photo'];?>" id="OldPhoto">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Photo']=='') {} else{?>
  <span id="show_photo">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/<?php echo $row7['Photo'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
</span>
<?php } ?>
</div>

                                        <input type="hidden" id="Status" name="Status" value="1">

                                    </div>
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
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
        $(document).ready(function() {
            $(document).on("change", "#CatId", function(event) {
                var val = this.value;
                var action = "getBrands";
                $.ajax({
                    url: "ajax_files/ajax_dropdown.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: val
                    },
                    success: function(data) {
                        $('#BrandId').html(data);

                    }
                });

            });


        });

        //CKEDITOR.replace( 'editor1');
    </script>
</body>

</html>