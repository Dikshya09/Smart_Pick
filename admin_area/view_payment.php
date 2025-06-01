<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_email'])) {
    echo "<script>window.open('login.php?not_admin=You are not an admin!','_self')</script>";
    exit();
}

include("includes/db.php");

$query = "SELECT id, customer_name, email, address, country, payment_method, total_amount, created_at
          FROM orders
          ORDER BY created_at DESC";

$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments Overview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .payment-container {
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .payment-header {
            font-size: 1.2em;
            font-weight: bold;
            color: #232f3e;
            margin-bottom: 10px;
        }
        .payment-info {
            color: #555;
            margin-bottom: 10px;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9em;
            font-weight: bold;
            color: white;
        }
        .paid { background-color: #28a745; }
        .pending { background-color: #ffc107; color: black; }
        .card { background-color: #007bff; }
    </style>
</head>
<body>
    <h1 style="color: #232f3e;">Customer Payments</h1>

    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="payment-container">
            <div class="payment-header">Order #<?= $row['id'] ?> — <?= $row['customer_name'] ?> (<?= $row['email'] ?>)</div>
            <div class="payment-info">
                <strong>Address:</strong> <?= $row['address'] ?>, <?= $row['country'] ?><br>
                <strong>Payment Method:</strong> <?= $row['payment_method'] ?> <br>
                <strong>Total:</strong> Rs <?= number_format($row['total_amount'], 2) ?> <br>
                <strong>Date:</strong> <?= $row['created_at'] ?> <br>
                <strong>Status:</strong> 
                <?php
                    $method = strtolower($row['payment_method']);
                    if ($method === 'cod') {
                        echo '<span class="badge pending">Pending (COD)</span>';
                    } elseif (strpos($method, 'card') !== false || strpos($method, 'debit') !== false || strpos($method, 'credit') !== false) {
                        echo '<span class="badge card">Paid by Card</span>';
                    } else {
                        echo '<span class="badge paid">Paid</span>';
                    }
                ?>
            </div>
        </div>
    <?php endwhile; ?>
</body>
</html>
