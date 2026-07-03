<?php
require_once '../config.php';
require_once 'gst_helper.php';

gstJsonHeaders();

try {
    ensureGstTables($conn);

    $action = strtolower($_REQUEST['action'] ?? 'list');
    $frId = gstEsc($conn, $_REQUEST['user_id'] ?? $_REQUEST['FrId'] ?? '0');

    if ($action === 'list') {
        $status = $_REQUEST['status'] ?? 'all';
        $where = "WHERE (FrId = 0 OR FrId = '$frId')";
        if ($status !== 'all') {
            $where .= " AND Status = '" . (int) $status . "'";
        }

        $sql = "SELECT id, GstName, GstPercentage, CgstPer, SgstPer, IgstPer,
                       EffectiveFrom, Status, FrId, CreatedDate, ModifiedDate
                FROM tbl_gst_master
                $where
                ORDER BY GstPercentage ASC, GstName ASC";
        $rows = gstFetchRows($conn, $sql);

        $records = [];
        foreach ($rows as $row) {
            $records[] = [
                'id' => (int) $row['id'],
                'GstName' => $row['GstName'],
                'GstPercentage' => gstFloat($row['GstPercentage']),
                'CgstPer' => gstFloat($row['CgstPer']),
                'SgstPer' => gstFloat($row['SgstPer']),
                'IgstPer' => gstFloat($row['IgstPer']),
                'EffectiveFrom' => $row['EffectiveFrom'],
                'Status' => (int) $row['Status'],
                'FrId' => (int) $row['FrId'],
            ];
        }

        gstRespond([
            'status' => 'success',
            'records' => $records,
            'totalRecords' => count($records),
        ]);
    }

    if ($action === 'get') {
        $id = (int) ($_REQUEST['id'] ?? 0);
        if ($id <= 0) {
            throw new Exception('id is required');
        }

        $sql = "SELECT * FROM tbl_gst_master WHERE id = '$id' LIMIT 1";
        $row = getRecord($sql);
        if (!$row) {
            throw new Exception('GST rate not found');
        }

        gstRespond([
            'status' => 'success',
            'record' => [
                'id' => (int) $row['id'],
                'GstName' => $row['GstName'],
                'GstPercentage' => gstFloat($row['GstPercentage']),
                'CgstPer' => gstFloat($row['CgstPer']),
                'SgstPer' => gstFloat($row['SgstPer']),
                'IgstPer' => gstFloat($row['IgstPer']),
                'EffectiveFrom' => $row['EffectiveFrom'],
                'Status' => (int) $row['Status'],
            ],
        ]);
    }

    throw new Exception('Invalid action');
} catch (Exception $e) {
    gstRespond([
        'status' => 'error',
        'message' => $e->getMessage(),
    ], 400);
}

?>
