<?php
/**
 * Keeps dashboard / sub-zone / zone-home date report filter in sync via $_SESSION.
 * Include after session_start (and auth). Merges saved filter into $_REQUEST when absent.
 */
if (!isset($_SESSION['report_filter']) || !is_array($_SESSION['report_filter'])) {
    $_SESSION['report_filter'] = [];
}

if (!empty($_GET['clear_report_filter'])) {
    unset($_SESSION['report_filter']);
    $params = $_GET;
    unset($params['clear_report_filter']);
    $url = $_SERVER['PHP_SELF'];
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

$incomingCal = (isset($_REQUEST['calendar']) && $_REQUEST['calendar'] !== '') ? $_REQUEST['calendar'] : '';

if ($incomingCal !== '') {
    $_SESSION['report_filter'] = [
        'calendar' => $incomingCal,
        'FromDate' => isset($_REQUEST['FromDate']) ? $_REQUEST['FromDate'] : '',
        'ToDate' => isset($_REQUEST['ToDate']) ? $_REQUEST['ToDate'] : '',
    ];
} elseif (!empty($_SESSION['report_filter']['calendar'])) {
    $_REQUEST['calendar'] = $_SESSION['report_filter']['calendar'];
    if (!empty($_SESSION['report_filter']['FromDate'])) {
        $_REQUEST['FromDate'] = $_SESSION['report_filter']['FromDate'];
    }
    if (!empty($_SESSION['report_filter']['ToDate'])) {
        $_REQUEST['ToDate'] = $_SESSION['report_filter']['ToDate'];
    }
}

$report_filter_clear_params = $_GET;
$report_filter_clear_params['clear_report_filter'] = '1';
$report_filter_clear_url = htmlspecialchars(
    $_SERVER['PHP_SELF'] . '?' . http_build_query($report_filter_clear_params),
    ENT_QUOTES,
    'UTF-8'
);
