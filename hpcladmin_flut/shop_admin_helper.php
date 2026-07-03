<?php

define('SHOP_ADMIN_ROLL', 167);

function isShopAdmin($roll = null) {
    if ($roll === null) {
        $roll = $_SESSION['Admin']['Roll'] ?? 0;
    }
    return (int)$roll === SHOP_ADMIN_ROLL;
}

function shopAdminDealerIds($adminRow = null) {
    if ($adminRow === null) {
        $adminRow = $_SESSION['Admin'] ?? [];
    }
    $raw = trim((string)($adminRow['CocoFranchiseAccess'] ?? ''));
    if ($raw === '' || $raw === '0') {
        return [];
    }
    return array_values(array_filter(array_map('intval', explode(',', $raw))));
}

function shopAdminFilterFrIds($frids, $adminRow = null) {
    $allowed = shopAdminDealerIds($adminRow);
    if (empty($allowed)) {
        return $frids;
    }
    if ($frids === '' || $frids === null) {
        return '';
    }
    $allowedMap = array_flip($allowed);
    $filtered = array_filter(array_map('intval', explode(',', $frids)), function ($id) use ($allowedMap) {
        return isset($allowedMap[$id]);
    });
    return implode(',', $filtered);
}

function shopAdminAllowedScripts() {
    return [
        'dashboard.php',
        'sub-zone.php',
        'zone-home.php',
        'expense-sale-dashboard.php',
        'franchise-wise-top-sell-dashboard-new.php',
        'zone-subzone-wise-top-sell-dashboard.php',
        'top-sell-sub-zone.php',
        'shop-admin-dealers.php',
        'logout.php',
        'change-password.php',
    ];
}

function shopAdminZoneIds($adminRow = null) {
    if ($adminRow === null) {
        $adminRow = $_SESSION['Admin'] ?? [];
    }
    $raw = trim((string)($adminRow['zone'] ?? ''));
    if ($raw === '' || $raw === '0') {
        return [];
    }
    return array_values(array_filter(array_map('intval', explode(',', $raw))));
}

function shopAdminSubZoneIds($adminRow = null) {
    if ($adminRow === null) {
        $adminRow = $_SESSION['Admin'] ?? [];
    }
    $raw = trim((string)($adminRow['subzone'] ?? ''));
    if ($raw === '' || $raw === '0') {
        return [];
    }
    return array_values(array_filter(array_map('intval', explode(',', $raw))));
}

function shopAdminCanAccessZone($zoneId, $adminRow = null) {
    if ($adminRow === null) {
        $adminRow = $_SESSION['Admin'] ?? [];
    }
    if (!isShopAdmin($adminRow['Roll'] ?? null)) {
        return true;
    }
    $zoneId = (int)$zoneId;
    $allowed = shopAdminZoneIds($adminRow);
    return $zoneId > 0 && in_array($zoneId, $allowed, true);
}

function shopAdminCanAccessSubZone($subZoneId, $adminRow = null) {
    if ($adminRow === null) {
        $adminRow = $_SESSION['Admin'] ?? [];
    }
    if (!isShopAdmin($adminRow['Roll'] ?? null)) {
        return true;
    }
    $subZoneId = (int)$subZoneId;
    $allowed = shopAdminSubZoneIds($adminRow);
    return $subZoneId > 0 && in_array($subZoneId, $allowed, true);
}

function shopAdminEnforceZoneAccess($zoneId, $adminRow = null) {
    if (!shopAdminCanAccessZone($zoneId, $adminRow)) {
        header('Location: dashboard.php');
        exit;
    }
}

function shopAdminEnforceSubZoneAccess($subZoneId, $adminRow = null) {
    if (!shopAdminCanAccessSubZone($subZoneId, $adminRow)) {
        header('Location: dashboard.php');
        exit;
    }
}

function shopAdminEnforcePageAccess() {
    if (!isShopAdmin()) {
        return;
    }

    $script = basename($_SERVER['PHP_SELF'] ?? '');
    if ($script === '' || $script === 'index.php') {
        return;
    }

    $path = str_replace('\\', '/', $_SERVER['PHP_SELF'] ?? '');
    if (strpos($path, '/ajax_files/') !== false || strpos($path, '/fr_acc/') !== false) {
        return;
    }

    if (in_array($script, shopAdminAllowedScripts(), true)) {
        return;
    }

    header('Location: dashboard.php');
    exit;
}

function shopAdminCanAccessDealer($dealerId, $adminRow = null) {
    $dealerId = (int)$dealerId;
    if ($dealerId <= 0) {
        return false;
    }
    $allowed = shopAdminDealerIds($adminRow);
    if (empty($allowed)) {
        return false;
    }
    return in_array($dealerId, $allowed, true);
}

function shopAdminDealerListDetailed($adminRow = null) {
    global $conn;
    $ids = shopAdminDealerIds($adminRow);
    if (empty($ids)) {
        return [];
    }
    $idList = implode(',', $ids);
    $sql = "SELECT tu.id, tu.CustomerId, tu.Fname, tu.Lname, tu.ShopName, tu.Phone, tu.Phone2, tu.Address, tu.Status,
                   tu.ZoneId, tu.SubZoneId, tz.Name AS ZoneName, tsz.Name AS SubZoneName
            FROM tbl_users_bill tu
            LEFT JOIN tbl_zone tz ON tz.id = tu.ZoneId
            LEFT JOIN tbl_sub_zone tsz ON tsz.id = tu.SubZoneId
            WHERE tu.id IN ($idList) AND tu.Roll = 5 AND tu.Status = 1
            ORDER BY tu.ShopName, tu.Fname";
    $res = $conn->query($sql);
    if (!$res) {
        return [];
    }
    $rows = [];
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

function shopAdminDealerList($adminRow = null) {
    global $conn;
    $ids = shopAdminDealerIds($adminRow);
    if (empty($ids)) {
        return [];
    }
    $idList = implode(',', $ids);
    $res = $conn->query("SELECT id, ShopName, Fname, Lname FROM tbl_users_bill WHERE id IN ($idList) AND Roll=5 AND Status=1 ORDER BY ShopName, Fname");
    if (!$res) {
        return [];
    }
    $rows = [];
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}
