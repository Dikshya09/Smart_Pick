<?php
session_start();
include("includes/db.php");
include("functions/functions.php");
include("header.php");

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

$ip = getRealIpUser();

function getCartDetails($con, $ip) {
    $cart_items = [];
    $query = "SELECT c.p_id, c.qty, p.product_price, p.product_stock FROM cart c JOIN products p ON c.p_id = p.product_id WHERE c.ip_add='$ip'";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $cart_items[] = $row;
    }
    return $cart_items;
}

$errors = [];
$total_amount = 0;
$cart_items = getCartDetails($con, $ip);
foreach ($cart_items as $item) {
    $total_amount += $item['product_price'] * $item['qty'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = trim($_POST['payment_method']);
    $shipping_address = trim($_POST['shipping_address']);

    $card_number = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';
    $exp_date = isset($_POST['exp_date']) ? trim($_POST['exp_date']) : '';
    $cvv = isset($_POST['cvv']) ? trim($_POST['cvv']) : '';
    $cardholder_name = isset($_POST['cardholder_name']) ? trim($_POST['cardholder_name']) : '';

    if (empty($shipping_address)) $errors['shipping_address'] = "Shipping address is required.";
    if (empty($payment_method)) $errors['payment_method'] = "Please select a payment method.";

    if ($payment_method !== 'COD') {
        if (empty($card_number)) $errors['card_number'] = "Card number is required.";
        if (empty($exp_date)) $errors['exp_date'] = "Expiration date is required.";
        if (empty($cvv)) $errors['cvv'] = "CVV is required.";
        if (empty($cardholder_name)) $errors['cardholder_name'] = "Cardholder name is required.";
    }

    if (empty($errors)) {
        foreach ($cart_items as $item) {
            if ($item['qty'] > $item['product_stock']) {
                echo "<script>alert('Not enough stock for one or more products.'); window.location.href='cart.php';</script>";
                exit();
            }
        }

        $customer_email = $_SESSION['customer_email'];
        $customer_query = mysqli_query($con, "SELECT * FROM customers WHERE customer_email='$customer_email'");
        $customer = mysqli_fetch_assoc($customer_query);

        $customer_id = $customer['customer_id'];
        $customer_name = $customer['customer_name'];
        $email = $customer['customer_email'];
        $country = $customer['customer_country'];

        mysqli_query($con, "INSERT INTO orders (customer_id, customer_name, email, address, country, payment_method, total_amount) VALUES ('$customer_id', '$customer_name', '$email', '$shipping_address', '$country', '$payment_method', '$total_amount')");

        $order_id = mysqli_insert_id($con);

        foreach ($cart_items as $item) {
            $p_id = $item['p_id'];
            $qty = $item['qty'];
            $price = $item['product_price'];

            mysqli_query($con, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$p_id', '$qty', '$price')");
            mysqli_query($con, "UPDATE products SET product_stock = product_stock - $qty WHERE product_id = '$p_id'");
        }

        mysqli_query($con, "DELETE FROM cart WHERE ip_add = '$ip'");

       
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f9f9f9;
        margin: 0;
        padding: 0;
        color:black;
    }
    .container {
        max-width: 600px;
        margin: 50px auto;
        margin-top: 100px;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #333;
    }
    form {
        display: flex;
        flex-direction: column;
    }
    label {
        margin-top: 10px;
    }
    input[type="text"], select, textarea {
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }
    .error {
        color: red;
        font-size: 13px;
        margin-bottom: 5px;
    }
    .checkout-summary {
        margin: 10px 0;
        font-weight: bold;
        text-align: right;
    }
    .submit-container {
        display: flex;
        justify-content: center;
        margin-top: 10px;
    }
    input[type="submit"] {
        padding: 6px 14px;
        background: #000;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        width: auto;
    }
    input[type="submit"]:hover {
        background: #333;
    }
    #card-details {
        display: none;
        flex-direction: column;
    }
</style>
<script>
    function toggleCardDetails() {
        const paymentMethod = document.querySelector('select[name="payment_method"]').value;
        const cardDetails = document.getElementById('card-details');
        if (paymentMethod === 'Debit Card' || paymentMethod === 'Credit Card') {
            cardDetails.style.display = 'flex';
        } else {
            cardDetails.style.display = 'none';
        }
    }
</script>
</head>
<body>
<div class="container">
    <h2>Shipping and Payment</h2>
   <form action="placeorder.php" method="POST">
        <label for="shipping_address">Shipping Address:</label>
        <?php if (!empty($errors['shipping_address'])) echo '<div class="error">' . $errors['shipping_address'] . '</div>'; ?>
        <textarea name="shipping_address" placeholder="Enter your shipping address"><?php echo isset($_POST['shipping_address']) ? htmlspecialchars($_POST['shipping_address']) : ''; ?></textarea>

        <label>Payment Method:</label>
        <?php if (!empty($errors['payment_method'])) echo '<div class="error">' . $errors['payment_method'] . '</div>'; ?>
        <select name="payment_method" onchange="toggleCardDetails()">
            <option value="">--Select Payment Method--</option>
            <option value="COD" <?php if(isset($_POST['payment_method']) && $_POST['payment_method'] == 'COD') echo 'selected'; ?>>Cash on Delivery (COD)</option>
            <option value="Debit Card" <?php if(isset($_POST['payment_method']) && $_POST['payment_method'] == 'Debit Card') echo 'selected'; ?>>Debit Card</option>
            <option value="Credit Card" <?php if(isset($_POST['payment_method']) && $_POST['payment_method'] == 'Credit Card') echo 'selected'; ?>>Credit Card</option>
        </select>

        <div id="card-details">
            <label>Card Number:</label>
            <?php if (!empty($errors['card_number'])) echo '<div class="error">' . $errors['card_number'] . '</div>'; ?>
            <input type="text" name="card_number" value="<?php echo isset($_POST['card_number']) ? htmlspecialchars($_POST['card_number']) : ''; ?>">

            <label>Expiration Date (MM/YY):</label>
            <?php if (!empty($errors['exp_date'])) echo '<div class="error">' . $errors['exp_date'] . '</div>'; ?>
            <input type="text" name="exp_date" value="<?php echo isset($_POST['exp_date']) ? htmlspecialchars($_POST['exp_date']) : ''; ?>">

            <label>CVV:</label>
            <?php if (!empty($errors['cvv'])) echo '<div class="error">' . $errors['cvv'] . '</div>'; ?>
            <input type="text" name="cvv" value="<?php echo isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : ''; ?>">

            <label>Card Holder's Name:</label>
            <?php if (!empty($errors['cardholder_name'])) echo '<div class="error">' . $errors['cardholder_name'] . '</div>'; ?>
            <input type="text" name="cardholder_name" value="<?php echo isset($_POST['cardholder_name']) ? htmlspecialchars($_POST['cardholder_name']) : ''; ?>">
        </div>

        <div class="checkout-summary">
            Total Amount: Rs <?= number_format($total_amount, 2) ?>
        </div>

        <div class="submit-container">
            <input type="submit" value="Pay Now">
        </div>
    </form>
</div>
<script>
    toggleCardDetails();
</script>
</body>
</html>