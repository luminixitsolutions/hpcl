<?php
include_once 'db.php';   // DB connection

header('Content-Type: application/json'); // send headers first

// Collect request data safely
$code     = isset($_REQUEST['coupon_code']) ? trim($_REQUEST['coupon_code']) : '';
$userId   = isset($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
$cartJson = isset($_REQUEST['cart']) ? $_REQUEST['cart'] : '[]';
/*$cartJson = '[ 
  {"product_id":3615, "category_id":5, "qty":2, "price":10.00},
  {"product_id":3898, "category_id":53, "qty":1, "price":25.00},
  {"product_id":262, "category_id":17, "qty":3, "price":20.00}
]';*/
// Decode cart
$cartItems = json_decode($cartJson, true);
if (!is_array($cartItems)) {
    $cartItems = [];
}

$response = ["status" => false, "message" => "Invalid coupon"];

// Exit early if no cart
if (empty($cartItems) || $code == '') {
    echo json_encode(["status" => false, "message" => "Cart or coupon code missing"]);
    exit;
}

// 1. Fetch coupon details
$sql = "SELECT * FROM tbl_coupons 
        WHERE Code='$code' 
          AND Status=1 
          AND CURDATE() BETWEEN StartDate AND EndDate
        LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $coupon = $result->fetch_assoc();
    $eligibleSubtotal = 0;

    // 2. Get product/category restrictions
    $prodRes = [];
    $catRes  = [];

    $resProd = $conn->query("SELECT ProductId FROM tbl_coupon_products WHERE CouponId=" . $coupon['CouponId']);
    while ($row = $resProd->fetch_assoc()) {
        $prodRes[] = $row['ProductId'];
    }

    $resCat = $conn->query("SELECT CategoryId FROM tbl_coupon_categories WHERE CouponId=" . $coupon['CouponId']);
    while ($row = $resCat->fetch_assoc()) {
        $catRes[] = $row['CategoryId'];
    }

    // 3. Check cart items
    foreach ($cartItems as $item) {
        if (
            (empty($prodRes) || in_array($item['product_id'], $prodRes)) ||
            (empty($catRes)  || in_array($item['category_id'], $catRes))
        ) {
            $eligibleSubtotal += ($item['price'] * $item['qty']);
        }
    }

    // 4. Discount logic
    if ($eligibleSubtotal >= $coupon['MinOrderAmount']) {
        $discount = ($coupon['DiscountType'] == 'percent') 
            ? ($eligibleSubtotal * $coupon['DiscountValue'] / 100) 
            : $coupon['DiscountValue'];

        $cartTotal = 0;
        foreach ($cartItems as $i) {
            $cartTotal += $i['price'] * $i['qty'];
        }

        $finalTotal = $cartTotal - $discount;
        if ($finalTotal < 0) $finalTotal = 0;

        $response = [
            "status" => true,
            "message" => "Coupon applied successfully",
            "discount" => round($discount, 2),
            "final_total" => round($finalTotal, 2)
        ];
    } else {
        $response['message'] = "Cart amount not eligible for coupon";
    }
}

echo json_encode($response);
?>
