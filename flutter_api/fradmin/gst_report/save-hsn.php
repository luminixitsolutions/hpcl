<?php
require_once '../config.php';
require_once 'gst_helper.php';

gstJsonHeaders();

try {
    ensureGstTables($conn);

    $id = (int) ($_POST['id'] ?? $_REQUEST['id'] ?? 0);
    $hsnCode = gstEsc($conn, $_POST['HsnCode'] ?? $_REQUEST['HsnCode'] ?? '');
    $description = gstEsc($conn, $_POST['Description'] ?? $_REQUEST['Description'] ?? '');
    $gstRateId = (int) ($_POST['GstRateId'] ?? $_REQUEST['GstRateId'] ?? 0);
    $gstPercentage = gstFloat($_POST['GstPercentage'] ?? $_REQUEST['GstPercentage'] ?? 0);
    $status = (int) ($_POST['Status'] ?? $_REQUEST['Status'] ?? 1);
    $frId = gstEsc($conn, $_POST['user_id'] ?? $_REQUEST['user_id'] ?? $_REQUEST['FrId'] ?? '0');
    $userId = gstEsc($conn, $_POST['CreatedBy'] ?? $_REQUEST['CreatedBy'] ?? $frId);
    $now = date('Y-m-d H:i:s');

    if ($hsnCode === '') {
        throw new Exception('HsnCode is required');
    }

    if ($gstRateId > 0) {
        $gstRow = getRecord("SELECT GstPercentage FROM tbl_gst_master WHERE id = '$gstRateId' LIMIT 1");
        if ($gstRow) {
            $gstPercentage = gstFloat($gstRow['GstPercentage']);
        }
    }

    if ($id > 0) {
        $sql = "UPDATE tbl_hsn_master SET
                    HsnCode='$hsnCode',
                    Description='$description',
                    GstRateId='$gstRateId',
                    GstPercentage='$gstPercentage',
                    Status='$status',
                    ModifiedBy='$userId',
                    ModifiedDate='$now'
                WHERE id='$id'";
        if (!$conn->query($sql)) {
            throw new Exception('Failed to update HSN: ' . $conn->error);
        }
        $message = 'HSN updated successfully';
    } else {
        $sql = "INSERT INTO tbl_hsn_master SET
                    HsnCode='$hsnCode',
                    Description='$description',
                    GstRateId='$gstRateId',
                    GstPercentage='$gstPercentage',
                    Status='$status',
                    FrId='$frId',
                    CreatedBy='$userId',
                    CreatedDate='$now'";
        if (!$conn->query($sql)) {
            throw new Exception('Failed to save HSN: ' . $conn->error);
        }
        $id = (int) $conn->insert_id;
        $message = 'HSN saved successfully';
    }

    gstRespond([
        'status' => 'success',
        'message' => $message,
        'id' => $id,
    ]);
} catch (Exception $e) {
    gstRespond([
        'status' => 'error',
        'message' => $e->getMessage(),
    ], 400);
}

?>
