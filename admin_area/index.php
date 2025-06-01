<?php 
session_start();
if(!isset($_SESSION['user_email'])) {
    echo "<script> window.open('login.php?not_admin=You are not a admin !','_self') </script>";
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: #f3f4f6;
            color: #111827;
        }
        .main_wrapper {
            display: flex;
            min-height: 100vh;
        }
        #header {
            width: 100%;
            padding: 20px;
            background-color:rgb(0, 0, 0);
            color: white;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
        }
        #right {
            width: 250px;
            background-color:rgb(5, 5, 5);
            padding: 20px;
            color: white;
        }
        #right h2 {
            margin-bottom: 20px;
        }
        #right a {
            display: block;
            margin: 10px 0;
            padding: 10px 15px;
            background-color: #4b5563;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.2s;
        }
        #right a:hover {
            background-color: #6b7280;
        }
        #left {
            flex: 1;
            padding: 30px;
        }
        #left h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="main_wrapper">
        <div id="right">
            <h2>Manage Content</h2>
            <a href="index.php?insert_product">Insert Product</a>
            <a href="index.php?view_products">View Product</a>
            <a href="index.php?insert_cat">Insert New Category</a>
            <a href="index.php?view_cats">View All Categories</a>
            <a href="index.php?insert_brand">Insert New Brand</a>
            <a href="index.php?view_brands">View All Brands</a>
            <a href="index.php?view_customers">View Customers</a>
            <a href="index.php?view_orders">View Orders</a>
            <a href="index.php?view_payment">View Payments</a>
            <a href="logout.php">Admin Logout</a>
        </div>
        <div id="left">
            <h2><?php echo @$_GET['logged_in']; ?></h2>
            <?php
                if(isset($_GET['insert_product'])) {
                    include("insert_product.php");
                }
                if(isset($_GET['view_products'])) {
                    include("view_products.php");
                }
                if(isset($_GET['edit_pro'])) {
                    include("edit_pro.php");
                }
                if(isset($_GET['insert_cat'])) {
                    include("insert_cat.php");
                }
                if(isset($_GET['view_cats'])) {
                    include("view_cats.php");
                }
                if(isset($_GET['edit_cat'])) {
                    include("edit_cat.php");
                }
                if(isset($_GET['insert_brand'])) {
                    include("insert_brand.php");
                }
                if(isset($_GET['view_brands'])) {
                    include("view_brands.php");
                }
                if(isset($_GET['edit_brand'])) {
                    include("edit_brand.php");
                }
                if(isset($_GET['view_customers'])) {
                    include("view_customers.php");
                }
                 if(isset($_GET['view_orders'])) {
                    include("view_orders.php");
                }
                 if(isset($_GET['view_payment'])) {
                    include("view_payment.php");
                }
                
            ?>
        </div>
    </div>

</body>
</html>
<?php } ?>
