<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include_once '../config.php';
include_once 'incuserdetails.php';
include_once __DIR__ . '/../../../flutter_api/fradmin/manage_stocks/batch_helper.php';

try {
    ensureBatchMasterTable($conn);

    $action = strtolower(trim($_POST['action'] ?? 'save'));
    $frId = (int) ($BillSoftFrId ?? 0);
    $createdBy = (int) ($_SESSION['Admin']['id'] ?? $frId);
    $id = (int) ($_POST['id'] ?? 0);
    $batchNo = addslashes(trim($_POST['BatchNo'] ?? ''));
    $expDate = addslashes(trim($_POST['ExpDate'] ?? ''));
    $status = (int) ($_POST['Status'] ?? 1);
    $now = date('Y-m-d H:i:s');

    if ($frId <= 0) {
        throw new Exception('Franchise session is required');
    }

    if ($action === 'delete') {
        if ($id <= 0) {
            throw new Exception('Batch id is required');
        }
        $sql = "UPDATE tbl_batch_master SET Status=0, ModifiedDate='$now' WHERE id='$id' AND FrId='$frId'";
        if (!$conn->query($sql)) {
            throw new Exception('Failed to delete batch: ' . $conn->error);
        }
        echo json_encode(['status' => 'success', 'message' => 'Batch deleted successfully']);
        exit;
    }

    if ($batchNo === '') {
        throw new Exception('Batch No is required');
    }

    $checkSql = "SELECT id FROM tbl_batch_master WHERE FrId='$frId' AND BatchNo='$batchNo' AND Status=1";
    if ($id > 0) {
        $checkSql .= " AND id != '$id'";
    }
    $exists = getRecord($checkSql);
    if ($exists) {
        throw new Exception('Batch No already exists for this franchise');
    }

    if ($id > 0) {
        $sql = "UPDATE tbl_batch_master SET
                    BatchNo='$batchNo',
                    ExpDate=" . ($expDate !== '' ? "'$expDate'" : "NULL") . ",
                    Status='$status',
                    ModifiedDate='$now'
                WHERE id='$id' AND FrId='$frId'";
        if (!$conn->query($sql)) {
            throw new Exception('Failed to update batch: ' . $conn->error);
        }
        $message = 'Batch updated successfully';
    } else {
        $sql = "INSERT INTO tbl_batch_master SET
                    FrId='$frId',
                    BatchNo='$batchNo',
                    ExpDate=" . ($expDate !== '' ? "'$expDate'" : "NULL") . ",
                    Status='$status',
                    CreatedBy='$createdBy',
                    CreatedDate='$now'";
        if (!$conn->query($sql)) {
            throw new Exception('Failed to save batch: ' . $conn->error);
        }
        $id = (int) $conn->insert_id;
        $message = 'Batch saved successfully';
    }

    echo json_encode([
        'status' => 'success',
        'message' => $message,
        'id' => $id,
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
    ]);
}
?>
