<?php
if (!isset($_SESSION['user_email'])) {
    echo "<script> window.open('login.php?not_admin=You are not an admin!','_self') </script>";
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
        }
        h1, h3 {
            text-align: center;
            margin-bottom: 10px;
        }
        h1 { color: rgb(13, 13, 14); }
        h3 { color: #333; }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            padding: 16px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background-color: #232f3e;
            color: white;
        }
        tr:hover {
            background-color: #f9fafb;
        }
        img {
            border-radius: 6px;
            object-fit: cover;
        }
        a {
            text-decoration: none;
            color: #2563eb;
            font-weight: 500;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <main>
        <h1>View All Products</h1>
        <?php
        include("includes/db.php");
        $get_pro = "SELECT * FROM products";
        $run_pro = mysqli_query($con, $get_pro);
        
        $total_products = 0;
        $total_stock = 0;
        while ($row = mysqli_fetch_assoc($run_pro)) {
            $total_products++;
            $total_stock += (int)$row['product_stock'];
        }
        ?>

        <table>
            <tr>
                <th>S.N</th>
                <th>Title</th>
                <th>Image</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php
            mysqli_data_seek($run_pro, 0); // Reset pointer to fetch again
            $i = 0;
            while ($row_pro = mysqli_fetch_array($run_pro)) {
                $pro_id = $row_pro['product_id'];
                $pro_title = $row_pro['product_title'];
                $pro_image = $row_pro['product_image'];
                $pro_stock = $row_pro['product_stock'];
                $pro_price = $row_pro['product_price'];
                $i++;
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $pro_title; ?></td>
                <td><img src="product_images/<?php echo $pro_image; ?>" width="80" height="50"></td>
                <td><?php echo $pro_stock; ?></td>
                <td>$<?php echo $pro_price; ?></td>
                <td><a href="index.php?edit_pro=<?php echo $pro_id; ?>">Edit</a></td>
                <td><a href="delete_pro.php?delete_pro=<?php echo $pro_id; ?>">Delete</a></td>
            </tr>
            <?php } ?>
        </table>
    </main>
</body>
</html>
<?php } ?>
