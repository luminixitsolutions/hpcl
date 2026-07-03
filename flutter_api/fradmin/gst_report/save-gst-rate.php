<?php
require_once '../config.php';
require_once 'gst_helper.php';

gstJsonHeaders();

try {
    ensureGstTables($conn);

    $id = (int) ($_POST['id'] ?? $_REQUEST['id'] ?? 0);
    $gstName = gstEsc($conn, $_POST['GstName'] ?? $_REQUEST['GstName'] ?? '');
    $gstPercentage = gstFloat($_POST['GstPercentage'] ?? $_REQUEST['GstPercentage'] ?? 0);
    $cgstPer = gstFloat($_POST['CgstPer'] ?? $_REQUEST['CgstPer'] ?? 0);
    $sgstPer = gstFloat($_POST['SgstPer'] ?? $_REQUEST['SgstPer'] ?? 0);
    $igstPer = gstFloat($_POST['IgstPer'] ?? $_REQUEST['IgstPer'] ?? 0);
    $effectiveFrom = gstEsc($conn, $_POST['EffectiveFrom'] ?? $_REQUEST['EffectiveFrom'] ?? date('Y-m-d'));
    $status = (int) ($_POST['Status'] ?? $_REQUEST['Status'] ?? 1);
    $frId = gstEsc($conn, $_POST['user_id'] ?? $_REQUEST['user_id'] ?? $_REQUEST['FrId'] ?? '0');
    $userId = gstEsc($conn, $_POST['CreatedBy'] ?? $_REQUEST['CreatedBy'] ?? $frId);
    $now = date('Y-m-d H:i:s');

    if ($gstName === '') {
        throw new Exception('GstName is required');
    }

    if ($gstPercentage <= 0 && ($cgstPer + $sgstPer + $igstPer) <= 0) {
        throw new Exception('GST percentage or tax split is required');
    }

    if ($gstPercentage <= 0) {
        $gstPercentage = $cgstPer + $sgstPer + $igstPer;
    }

    if ($id > 0) {
        $sql = "UPDATE tbl_gst_master SET
                    GstName='$gstName',
                    GstPercentage='$gstPercentage',
                    CgstPer='$cgstPer',
                    SgstPer='$sgstPer',
                    IgstPer='$igstPer',
                    EffectiveFrom='$effectiveFrom',
                    Status='$status',
                    ModifiedBy='$userId',
                    ModifiedDate='$now'
                WHERE id='$id'";
        if (!$conn->query($sql)) {
            throw new Exception('Failed to update GST rate: ' . $conn->error);
        }
        $message = 'GST rate updated successfully';
    } else {
        $sql = "INSERT INTO tbl_gst_master SET
                    GstName='$gstName',
                    GstPercentage='$gstPercentage',
                    CgstPer='$cgstPer',
                    SgstPer='$sgstPer',
                    IgstPer='$igstPer',
                    EffectiveFrom='$effectiveFrom',
                    Status='$status',
                    FrId='$frId',
                    CreatedBy='$userId',
                    CreatedDate='$now'";
        if (!$conn->query($sql)) {
            throw new Exception('Failed to save GST rate: ' . $conn->error);
        }
        $id = (int) $conn->insert_id;
        $message = 'GST rate saved successfully';
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
