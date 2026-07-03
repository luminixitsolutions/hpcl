<?php

function ensureBatchMasterTable($conn)
{
    $conn->query("CREATE TABLE IF NOT EXISTS `tbl_batch_master` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `FrId` int(11) NOT NULL,
        `BatchNo` varchar(100) NOT NULL,
        `ExpDate` date DEFAULT NULL,
        `Status` tinyint(1) NOT NULL DEFAULT 1,
        `CreatedBy` int(11) NOT NULL DEFAULT 0,
        `CreatedDate` datetime DEFAULT NULL,
        `ModifiedDate` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_batch_frid` (`FrId`),
        KEY `idx_batch_no` (`BatchNo`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}

function getBatchListByFrId($conn, $frId, $activeOnly = true)
{
    ensureBatchMasterTable($conn);
    $frId = (int) $frId;
    $where = "WHERE FrId = '$frId'";
    if ($activeOnly) {
        $where .= " AND Status = 1";
    }
    $sql = "SELECT id, BatchNo, ExpDate, Status, CreatedDate
            FROM tbl_batch_master
            $where
            ORDER BY BatchNo ASC";
    $rows = [];
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function buildBatchSelectOptions($conn, $frId)
{
    $batches = getBatchListByFrId($conn, $frId, true);
    $html = "<option value=''>Select Batch</option>";
    foreach ($batches as $batch) {
        $batchNo = htmlspecialchars($batch['BatchNo'], ENT_QUOTES, 'UTF-8');
        $expDate = htmlspecialchars($batch['ExpDate'] ?? '', ENT_QUOTES, 'UTF-8');
        $html .= "<option value='$batchNo' data-expdate='$expDate'>$batchNo</option>";
    }
    return $html;
}

?>
