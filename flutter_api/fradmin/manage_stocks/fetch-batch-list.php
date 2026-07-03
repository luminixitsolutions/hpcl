<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json; charset=utf-8');

include '../config.php';
include 'batch_helper.php';

try {
    $frId = (int) ($_REQUEST['user_id'] ?? $_REQUEST['FrId'] ?? 0);
    if ($frId <= 0) {
        throw new Exception('user_id (FrId) is required');
    }

    $batches = getBatchListByFrId($conn, $frId, true);
    $records = [];
    $sr = 1;
    foreach ($batches as $batch) {
        $records[] = [
            'SrNo' => $sr++,
            'id' => (int) $batch['id'],
            'BatchNo' => $batch['BatchNo'],
            'ExpDate' => $batch['ExpDate'],
            'Status' => (int) $batch['Status'],
        ];
    }

    echo json_encode([
        'status' => 'success',
        'records' => $records,
        'totalRecords' => count($records),
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
    ]);
}

?>
