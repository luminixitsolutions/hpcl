<?php

function gstJsonHeaders()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Content-Type: application/json; charset=utf-8");
}

function gstRespond($data, $code = 200)
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function gstEsc($conn, $value)
{
    return $conn->real_escape_string(trim((string) $value));
}

function gstFloat($value)
{
    return round((float) $value, 2);
}

function gstGetAllParams()
{
    $params = $_GET;
    if (!empty($_POST)) {
        $params = array_merge($params, $_POST);
    }

    $raw = file_get_contents('php://input');
    if ($raw) {
        $json = json_decode($raw, true);
        if (is_array($json)) {
            $params = array_merge($params, $json);
        }
    }

    return $params;
}

function gstGetParam($params, $keys, $default = '')
{
    foreach ((array) $keys as $key) {
        if (isset($params[$key]) && $params[$key] !== '' && $params[$key] !== null) {
            return trim((string) $params[$key]);
        }
    }
    return $default;
}

function gstNormalizeDate($value)
{
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        return $value;
    }

    if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $value, $m)) {
        return $m[1];
    }

    if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $value, $m)) {
        return sprintf('%04d-%02d-%02d', (int) $m[3], (int) $m[2], (int) $m[1]);
    }

    $ts = strtotime(str_replace('/', '-', $value));
    if ($ts) {
        return date('Y-m-d', $ts);
    }

    return $value;
}

function ensureGstTables($conn)
{
    $conn->query("CREATE TABLE IF NOT EXISTS `tbl_gst_master` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `GstName` varchar(100) NOT NULL,
        `GstPercentage` decimal(8,2) NOT NULL DEFAULT 0.00,
        `CgstPer` decimal(8,2) NOT NULL DEFAULT 0.00,
        `SgstPer` decimal(8,2) NOT NULL DEFAULT 0.00,
        `IgstPer` decimal(8,2) NOT NULL DEFAULT 0.00,
        `EffectiveFrom` date DEFAULT NULL,
        `Status` tinyint(1) NOT NULL DEFAULT 1,
        `FrId` int(11) NOT NULL DEFAULT 0,
        `CreatedBy` int(11) NOT NULL DEFAULT 0,
        `CreatedDate` datetime DEFAULT NULL,
        `ModifiedBy` int(11) NOT NULL DEFAULT 0,
        `ModifiedDate` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

    $conn->query("CREATE TABLE IF NOT EXISTS `tbl_hsn_master` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `HsnCode` varchar(20) NOT NULL,
        `Description` varchar(255) DEFAULT NULL,
        `GstRateId` int(11) NOT NULL DEFAULT 0,
        `GstPercentage` decimal(8,2) NOT NULL DEFAULT 0.00,
        `Status` tinyint(1) NOT NULL DEFAULT 1,
        `FrId` int(11) NOT NULL DEFAULT 0,
        `CreatedBy` int(11) NOT NULL DEFAULT 0,
        `CreatedDate` datetime DEFAULT NULL,
        `ModifiedBy` int(11) NOT NULL DEFAULT 0,
        `ModifiedDate` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_hsn_code` (`HsnCode`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

    $conn->query("CREATE TABLE IF NOT EXISTS `tbl_invoice_gst_details` (
        `id` bigint(20) NOT NULL AUTO_INCREMENT,
        `InvId` varchar(255) NOT NULL,
        `InvoiceNo` varchar(100) DEFAULT NULL,
        `InvoiceDate` date DEFAULT NULL,
        `InvDetailId` varchar(255) DEFAULT NULL,
        `ProdId` int(11) NOT NULL DEFAULT 0,
        `ProductName` varchar(255) DEFAULT NULL,
        `HsnCode` varchar(20) DEFAULT NULL,
        `Qty` decimal(14,2) NOT NULL DEFAULT 0.00,
        `Rate` decimal(14,2) NOT NULL DEFAULT 0.00,
        `TaxableAmount` decimal(14,2) NOT NULL DEFAULT 0.00,
        `GstPercentage` decimal(8,2) NOT NULL DEFAULT 0.00,
        `CgstPer` decimal(8,2) NOT NULL DEFAULT 0.00,
        `CgstAmount` decimal(14,2) NOT NULL DEFAULT 0.00,
        `SgstPer` decimal(8,2) NOT NULL DEFAULT 0.00,
        `SgstAmount` decimal(14,2) NOT NULL DEFAULT 0.00,
        `IgstPer` decimal(8,2) NOT NULL DEFAULT 0.00,
        `IgstAmount` decimal(14,2) NOT NULL DEFAULT 0.00,
        `TotalAmount` decimal(14,2) NOT NULL DEFAULT 0.00,
        `InvoiceType` varchar(10) DEFAULT 'B2C',
        `CustomerGstin` varchar(20) DEFAULT NULL,
        `PlaceOfSupply` varchar(100) DEFAULT NULL,
        `FrId` int(11) NOT NULL DEFAULT 0,
        `CreatedDate` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_inv_id` (`InvId`),
        KEY `idx_invoice_date` (`InvoiceDate`),
        KEY `idx_frid` (`FrId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

    gstEnsureColumn($conn, 'tbl_cust_products_2025', 'GstRateId', "int(11) NOT NULL DEFAULT 0 AFTER `IgstPer`");
    gstEnsureColumn($conn, 'tbl_cust_products_2025', 'HsnCode', "varchar(20) DEFAULT NULL AFTER `GstRateId`");
    gstEnsureColumn($conn, 'tbl_customer_invoice_2025', 'CustGstin', "varchar(20) DEFAULT NULL AFTER `CustName`");
    gstEnsureColumn($conn, 'tbl_customer_invoice_2025', 'PlaceOfSupply', "varchar(100) DEFAULT NULL AFTER `CustGstin`");
    gstEnsureColumn($conn, 'tbl_customer_invoice_details_2025', 'HsnCode', "varchar(20) DEFAULT NULL AFTER `ProdId`");
    gstEnsureColumn($conn, 'tbl_customer_invoice_details_2025', 'ProductName', "varchar(255) DEFAULT NULL AFTER `HsnCode`");
    gstEnsureColumn($conn, 'tbl_customer_invoice_details_2025', 'TaxableAmount', "decimal(14,2) DEFAULT NULL AFTER `Price`");

    seedDefaultGstRates($conn);
}

function gstEnsureColumn($conn, $table, $column, $definition)
{
    $table = gstEsc($conn, $table);
    $column = gstEsc($conn, $column);
    $result = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    if ($result && $result->num_rows === 0) {
        $conn->query("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
    }
}

function seedDefaultGstRates($conn)
{
    $check = $conn->query("SELECT COUNT(*) AS cnt FROM tbl_gst_master");
    $row = $check ? $check->fetch_assoc() : ['cnt' => 0];
    if ((int) $row['cnt'] > 0) {
        return;
    }

    $now = date('Y-m-d H:i:s');
    $defaults = [
        ['GST 0%', 0, 0, 0, 0],
        ['GST 5%', 5, 2.5, 2.5, 0],
        ['GST 12%', 12, 6, 6, 0],
        ['GST 18%', 18, 9, 9, 0],
        ['GST 28%', 28, 14, 14, 0],
        ['IGST 5%', 5, 0, 0, 5],
        ['IGST 12%', 12, 0, 0, 12],
        ['IGST 18%', 18, 0, 0, 18],
        ['IGST 28%', 28, 0, 0, 28],
    ];

    foreach ($defaults as $rate) {
        $conn->query("INSERT INTO tbl_gst_master
            (GstName, GstPercentage, CgstPer, SgstPer, IgstPer, EffectiveFrom, Status, FrId, CreatedDate)
            VALUES (
                '{$rate[0]}', '{$rate[1]}', '{$rate[2]}', '{$rate[3]}', '{$rate[4]}',
                '" . date('Y-m-d') . "', 1, 0, '$now'
            )");
    }
}

function gstGetRequestFilters($conn)
{
    $params = gstGetAllParams();

    $frId = gstEsc($conn, gstGetParam($params, ['user_id', 'FrId', 'frId']));
    if ($frId === '') {
        throw new Exception("user_id (FrId) is required");
    }

    $rawFromDate = gstGetParam($params, ['FromDate', 'fromDate', 'from_date']);
    $rawToDate = gstGetParam($params, ['ToDate', 'toDate', 'to_date']);
    $fromDate = gstEsc($conn, gstNormalizeDate($rawFromDate));
    $toDate = gstEsc($conn, gstNormalizeDate($rawToDate));
    $gstType = strtoupper(gstEsc($conn, gstGetParam($params, ['GstType', 'gst_type', 'gstType'], 'ALL')));
    $month = (int) gstGetParam($params, ['Month', 'month'], '0');
    $year = (int) gstGetParam($params, ['Year', 'year'], '0');

    if ($month > 0 && $year > 0) {
        $fromDate = sprintf('%04d-%02d-01', $year, $month);
        $toDate = date('Y-m-t', strtotime($fromDate));
    }

    if ($fromDate === '') {
        $fromDate = date('Y-m-01');
    }
    if ($toDate === '') {
        $toDate = date('Y-m-d');
    }

    if (!in_array($gstType, ['ALL', 'B2B', 'B2C'], true)) {
        $gstType = 'ALL';
    }

    return [
        'FrId' => $frId,
        'FromDate' => $fromDate,
        'ToDate' => $toDate,
        'GstType' => $gstType,
        'Month' => $month,
        'Year' => $year,
        'ReceivedFromDate' => $rawFromDate,
        'ReceivedToDate' => $rawToDate,
    ];
}

function gstInvoiceWhere($filters)
{
    $where = "i.FrId = '{$filters['FrId']}'
              AND i.Status = 1
              AND i.delete_flag = 0
              AND i.InvoiceDate BETWEEN '{$filters['FromDate']}' AND '{$filters['ToDate']}'";
    return $where;
}

function gstLineTaxableExpr()
{
    return "COALESCE(NULLIF(d.TaxableAmount, 0),
            (COALESCE(d.Total, 0) - COALESCE(d.CgstAmt, 0) - COALESCE(d.SgstAmt, 0) - COALESCE(d.IgstAmt, 0)),
            (COALESCE(d.ActPrice, 0) * COALESCE(d.Qty, 0)))";
}

function gstGstPercentageExpr()
{
    return "(COALESCE(d.CgstPer, 0) + COALESCE(d.SgstPer, 0) + COALESCE(d.IgstPer, 0))";
}

function gstInvoiceTypeExpr()
{
    return "CASE
                WHEN COALESCE(NULLIF(i.CustGstin, ''), NULLIF(c.GstNo, '')) IS NOT NULL
                     AND COALESCE(NULLIF(i.CustGstin, ''), NULLIF(c.GstNo, '')) != ''
                THEN 'B2B'
                ELSE 'B2C'
            END";
}

function gstCustomerGstinExpr()
{
    return "COALESCE(NULLIF(i.CustGstin, ''), NULLIF(c.GstNo, ''), '')";
}

function gstPlaceOfSupplyExpr()
{
    return "COALESCE(NULLIF(i.PlaceOfSupply, ''), NULLIF(cs.Name, ''), NULLIF(os.Name, ''), 'NA')";
}

function gstBaseJoins()
{
    return "
        FROM tbl_customer_invoice_2025 i
        INNER JOIN tbl_customer_invoice_details_2025 d ON d.InvId = i.id AND d.delete_flag = 0
        LEFT JOIN tbl_cust_products_2025 p ON p.id = d.ProdId
        LEFT JOIN tbl_users_bill c ON c.id = i.CustId
        LEFT JOIN tbl_state cs ON cs.id = c.StateId
        LEFT JOIN tbl_users_bill o ON o.id = i.FrId
        LEFT JOIN tbl_state os ON os.id = o.StateId
    ";
}

function gstTypeFilterClause($filters)
{
    if ($filters['GstType'] === 'B2B') {
        return " AND (" . gstCustomerGstinExpr() . ") != ''";
    }
    if ($filters['GstType'] === 'B2C') {
        return " AND (" . gstCustomerGstinExpr() . ") = ''";
    }
    return '';
}

function gstHsnCodeExpr()
{
    return "COALESCE(NULLIF(d.HsnCode, ''), NULLIF(p.HsnCode, ''), NULLIF(h.HsnCode, ''), 'NA')";
}

function gstHsnDescriptionExpr()
{
    return "COALESCE(NULLIF(h.Description, ''), NULLIF(p.ProductName, ''), 'General')";
}

function gstFetchRows($conn, $sql)
{
    $rows = [];
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function gstSumTotals($rows, $map)
{
    $totals = [];
    foreach ($map as $key => $field) {
        $totals[$key] = 0;
    }
    foreach ($rows as $row) {
        foreach ($map as $key => $field) {
            $totals[$key] += (float) ($row[$field] ?? 0);
        }
    }
    foreach ($totals as $key => $value) {
        $totals[$key] = gstFloat($value);
    }
    return $totals;
}

function gstGetOutletInfo($conn, $frId)
{
    $frId = gstEsc($conn, $frId);
    $sql = "SELECT u.id, u.Fname, u.GstNo, u.StateId, s.Name AS StateName
            FROM tbl_users_bill u
            LEFT JOIN tbl_state s ON s.id = u.StateId
            WHERE u.id = '$frId' LIMIT 1";
    return getRecord($sql) ?: [];
}

?>
