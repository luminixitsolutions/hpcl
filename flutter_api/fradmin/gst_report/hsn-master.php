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
        $where = "WHERE (h.FrId = 0 OR h.FrId = '$frId')";
        if ($status !== 'all') {
            $where .= " AND h.Status = '" . (int) $status . "'";
        }

        $sql = "SELECT h.id, h.HsnCode, h.Description, h.GstRateId, h.GstPercentage, h.Status, h.FrId,
                       g.GstName, g.CgstPer, g.SgstPer, g.IgstPer
                FROM tbl_hsn_master h
                LEFT JOIN tbl_gst_master g ON g.id = h.GstRateId
                $where
                ORDER BY h.HsnCode ASC";
        $rows = gstFetchRows($conn, $sql);

        $records = [];
        foreach ($rows as $row) {
            $records[] = [
                'id' => (int) $row['id'],
                'HsnCode' => $row['HsnCode'],
                'Description' => $row['Description'],
                'GstRateId' => (int) $row['GstRateId'],
                'GstPercentage' => gstFloat($row['GstPercentage']),
                'GstName' => $row['GstName'],
                'CgstPer' => gstFloat($row['CgstPer']),
                'SgstPer' => gstFloat($row['SgstPer']),
                'IgstPer' => gstFloat($row['IgstPer']),
                'Status' => (int) $row['Status'],
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

        $sql = "SELECT * FROM tbl_hsn_master WHERE id = '$id' LIMIT 1";
        $row = getRecord($sql);
        if (!$row) {
            throw new Exception('HSN record not found');
        }

        gstRespond([
            'status' => 'success',
            'record' => $row,
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
