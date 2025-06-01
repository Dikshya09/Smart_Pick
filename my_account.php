<?php
session_start();
include("includes/db.php");
include("header.php");

if (!isset($_SESSION['customer_email'])) {
    echo "<script>window.open('checkout.php','_self')</script>";
    exit();
}

$user = $_SESSION['customer_email']; // Get the email from the session
$get_customer = "SELECT * FROM customers WHERE customer_email='$user'";
$run_customer = mysqli_query($con, $get_customer);
$row_customer = mysqli_fetch_array($run_customer);

$c_id = $row_customer['customer_id'];  // customer_id from the customers table
$c_name = $row_customer['customer_name'];
$image = $row_customer['customer_image'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - SmartPick</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            margin-top:200px;
            max-width: 1000px;
            margin: 100px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
            color: black;
        }
        .sidebar {
            width: 300px;
            background: #000;
            color: #fff;
            padding: 20px;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar img {
            display: block;
            margin: 0 auto 10px;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px;
            border-bottom: 1px solid #444;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
            transition: background 0.3s;
        }
        .sidebar ul li a:hover {
            background: #222;
        }
        .content {
            flex-grow: 1;
            padding: 30px;
        }
        .content h2 {
            margin-top: 0;
        }
        .order-box {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .no-order {
            color: #555;
            text-align: center;
            padding: 50px;
            font-size: 18px;
        }
        .order {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .order:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="sidebar">
            <h2>Welcome, <?php echo $c_name; ?></h2>
            <img src="customer_images/<?php echo $image; ?>" alt="Profile Picture">
            <ul>
                <li><a href="my_account.php?my_orders"><i class="fa fa-box"></i> My Orders</a></li>
                <li><a href="my_account.php?edit_account"><i class="fa fa-user-edit"></i> Edit Account</a></li>
                <li><a href="my_account.php?change_pass"><i class="fa fa-lock"></i> Change Password</a></li>
                <li><a href="my_account.php?delete_account"><i class="fa fa-trash"></i> Delete Account</a></li>
                <li><a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <div class="content">
            <?php
            if (isset($_GET['my_orders'])) {
                // Fetch orders along with order items, filtering by customer_id
                $get_orders = "
                SELECT o.id AS order_id, o.created_at, o.payment_method, o.total_amount, p.product_title AS product_name
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.product_id
                WHERE o.customer_id = '$c_id'";  // No need for customer_id in order_items table
                $run_orders = mysqli_query($con, $get_orders);

                if (mysqli_num_rows($run_orders) > 0) {
                    echo '<div class="order-box">';
                    while ($row_order = mysqli_fetch_array($run_orders)) {
                        // Display order details
                        echo '<div class="order">';
                        echo "<p><strong>Order ID:</strong> {$row_order['order_id']}</p>";
                        echo "<p><strong>Product Name:</strong> {$row_order['product_name']}</p>";
                        echo "<p><strong>Order Date:</strong> {$row_order['created_at']}</p>";
                        echo "<p><strong>Payment Method:</strong> {$row_order['payment_method']}</p>";
                        echo "<p><strong>Total Amount:</strong> {$row_order['total_amount']}</p>";
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="no-order">You have not placed any orders yet.</div>';
                }
            } elseif (isset($_GET['edit_account'])) {
                include("edit_account.php");
            } elseif (isset($_GET['change_pass'])) {
                include("change_pass.php");
            } elseif (isset($_GET['delete_account'])) {
                include("delete_account.php");
            } else {
                echo "<h2>Hello, $c_name</h2><p>Welcome to your account. Use the sidebar to navigate.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
