<?php
session_start();
include("includes/db.php");

// Define getRealIpUser if not already defined
if (!function_exists('getRealIpUser')) {
    function getRealIpUser() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}

// Sanitize form input
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$address = $_POST['address'] ?? '';
$country = $_POST['country'] ?? '';
$payment = $_POST['payment'] ?? '';

// Get the logged-in user's email
$user_email = $_SESSION['customer_email'] ?? ''; // Assuming user is logged in
if (!$user_email) {
    die("<h3>Please login to place an order.</h3>");
}

// Fetch the customer_id based on email
$result = mysqli_query($con, "SELECT customer_id FROM customers WHERE customer_email='$user_email'");
$customer = mysqli_fetch_array($result);
$customer_id = $customer['customer_id'] ?? 0;

if (!$customer_id) {
    die("<h3>Customer not found. Please try again.</h3>");
}

$ip_add = getRealIpUser();
$total = 0;

// Get cart items
$cart_items = [];
$result = mysqli_query($con, "SELECT * FROM cart WHERE ip_add='$ip_add'");
while ($row = mysqli_fetch_array($result)) {
    $pro_id = $row['p_id'];
    $qty = $row['qty'];
    $run_price = mysqli_query($con, "SELECT product_price FROM products WHERE product_id='$pro_id'");
    $row_price = mysqli_fetch_array($run_price);
    $product_price = $row_price['product_price'] ?? 0;
    $sub_total = $product_price * $qty;
    $total += $sub_total;
    $cart_items[] = ['product_id' => $pro_id, 'qty' => $qty, 'price' => $product_price];
}

if (empty($cart_items)) {
    die("<h3>Your cart is empty. Please add products to continue.</h3>");
}

// Insert order
$insert_order = "INSERT INTO orders (customer_id, customer_name, email, address, country, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($con, $insert_order);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "isssssd", $customer_id, $name, $email, $address, $country, $payment, $total);
    mysqli_stmt_execute($stmt);
    $order_id = mysqli_insert_id($con);
} else {
    die("<h3>Error in placing the order. Please try again later.</h3>");
}

// Insert order items
foreach ($cart_items as $item) {
    $pid = $item['product_id'];
    $qty = $item['qty'];
    $price = $item['price'];
    $insert_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$pid', '$qty', '$price')";
    if (!mysqli_query($con, $insert_item)) {
        die("<h3>Error inserting order items. Please try again later.</h3>");
    }
}

// Clear the cart
mysqli_query($con, "DELETE FROM cart WHERE ip_add='$ip_add'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
        .confirmation-box {
            background: #fff; padding: 40px; max-width: 600px; margin: auto;
            border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .confirmation-box h1 { color: #007185; }
        .confirmation-box p { font-size: 18px; margin: 10px 0; }
        .back-link { margin-top: 20px; display: inline-block; text-decoration: none; color: #fff; background: #007185; padding: 10px 20px; border-radius: 5px; }
        .back-link:hover { background: #005f63; }
    </style>
</head>
<body>
    <div class="confirmation-box">
        <h1>Thank You for Your Order!</h1>
        <p>Your order has been successfully placed.</p>
        <p>Order ID: <strong>#<?= $order_id ?></strong></p>
        <p>Total Amount: <strong>Rs <?= number_format($total, 2) ?></strong></p>
        <a href="index.php" class="back-link">Continue Shopping</a>
    </div>
</body>
</html>
