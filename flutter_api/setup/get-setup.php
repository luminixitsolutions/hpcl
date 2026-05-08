<?php
ob_clean();
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: https://hpclpos.com");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");


require_once 'config.php'; // uses $conn

$userid = isset($_GET['userid']) ? trim($_GET['userid']) : '';

if ($userid == "") {
    echo json_encode([
        "status" => "error",
        "message" => "userid missing"
    ]);
    exit;
}

// Escape user id
$userid = mysqli_real_escape_string($conn, $userid);

// Run query using $conn (NOT $con)
$sql = "SELECT config_json FROM setup_configurations WHERE userid = '$userid' LIMIT 1";
$res = mysqli_query($conn, $sql);

// If nothing found → return default config
if (!$res || mysqli_num_rows($res) == 0) {
    echo json_encode([
        "status" => "success",
        "message" => "No saved configuration. Sending default.",
        "data" => getDefaultConfig()
    ]);
    exit;
}

$row = mysqli_fetch_assoc($res);
$json = trim($row['config_json']); // Remove unwanted whitespace

$config = json_decode($json, true);

// If invalid JSON → send error
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON stored in database",
        "json_error" => json_last_error_msg(),
        "raw_json" => $json
    ]);
    exit;
}

// Success
echo json_encode([
    "status" => "success",
    "message" => "Configuration loaded",
    "data" => $config
]);

exit;


// Default Config
function getDefaultConfig() {
    return [
        "printer_config" => [
            "printer_type" => "bluetooth",
            "selected_bluetooth_printer" => "",
            "paper_size" => "3-inch",
            "enabled_by_admin" => true
        ],
        "receipt_config" => [
            "receipt_type" => "retail",
            "show_qr_code" => true,
            "show_logo" => true,
            "show_scan_to_pay" => false,
            "show_feedback_qr" => true,
            "enabled_by_admin" => true
        ],
        "display_options" => [
            "show_price_range" => false,
            "show_top_sellers_option" => true,
            "show_top_sellers_badge" => true,
            "compact_mode" => false,
            "enabled_by_admin" => true
        ],
        "table_billing_config" => [
            "enable_table_billing" => true,
            "table_count" => 10,
            "enabled_by_admin" => true
        ],
        "payment_options" => [
            "show_cash_calculator" => true,
            "show_open_calculator" => false,
            "show_redeem_points" => true,
            "enable_split_payment" => false,
            "enabled_by_admin" => true
        ],
        "multi_kitchen_config" => [
            "enable_multi_kitchen_printing" => false,
            "south_indian_printer_name" => "",
            "chinese_printer_name" => "",
            "receipt_printer_name" => "",
            "south_indian_kitchen_header_name" => "SOUTH INDIAN KITCHEN",
            "chinese_kitchen_header_name" => "CHINESE KITCHEN",
            "enabled_by_admin" => true
        ],
        "advanced_features" => [
            "show_kot" => true,
            "show_online_orders_button" => true,
            "enable_crosssell_dialog" => true,
            "enabled_by_admin" => true
        ],
        "tax_config" => [
            "enable_gst" => true,
            "enabled_by_admin" => true
        ],
        "logo_config" => [
            "logo_url" => "",
            "enabled_by_admin" => true
        ],
        "section_visibility" => [
            "show_printer_configuration" => true,
            "show_receipt_configuration" => true,
            "show_logo_configuration" => true,
            "show_billing_options" => true,
            "show_multi_kitchen_printing" => true,
            "show_tax_configuration" => true
        ]
    ];
}
?>
