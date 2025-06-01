<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_email'])) {
    echo "<script>window.open('login.php?not_admin=You are not an admin!','_self')</script>";
    exit();
}

include("includes/db.php");

$query = "SELECT o.id, o.customer_name, o.email, o.address, o.country, o.payment_method, o.total_amount, o.created_at,
                 p.product_title, oi.quantity, oi.price
          FROM orders o
          JOIN order_items oi ON o.id = oi.order_id
          JOIN products p ON oi.product_id = p.product_id
          ORDER BY o.created_at DESC";

$result = mysqli_query($con, $query);

$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[$row['id']]['info'] = [
        'customer_name' => $row['customer_name'],
        'email' => $row['email'],
        'address' => $row['address'],
        'country' => $row['country'],
        'payment_method' => $row['payment_method'],
        'total_amount' => $row['total_amount'],
        'created_at' => $row['created_at'],
    ];
    $orders[$row['id']]['items'][] = [
        'product_title' => $row['product_title'],
        'quantity' => $row['quantity'],
        'price' => $row['price']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .order-container {
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-header {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .order-info {
            margin-bottom: 10px;
            color: #555;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .product-table th, .product-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        .product-table th {
            background: #eee;
        }
    </style>
</head>
<body>
    <h1 style="color: #232f3e;">All Customer Orders</h1>

    <?php foreach ($orders as $order_id => $order): ?>
        <div class="order-container">
            <div class="order-header">Order #<?= $order_id ?> — <?= $order['info']['customer_name'] ?> (<?= $order['info']['email'] ?>)</div>
            <div class="order-info">
                <strong>Address:</strong> <?= $order['info']['address'] ?>, <?= $order['info']['country'] ?> <br>
                <strong>Payment:</strong> <?= $order['info']['payment_method'] ?> <br>
                <strong>Total:</strong> Rs <?= number_format($order['info']['total_amount'], 2) ?> <br>
                <strong>Ordered at:</strong> <?= $order['info']['created_at'] ?>
            </div>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?= $item['product_title'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>Rs <?= number_format($item['price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
</body>
</html>
