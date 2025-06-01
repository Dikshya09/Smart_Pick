<?php  
if (!isset($_SESSION['user_email'])) {
    echo "<script> window.open('login.php?not_admin=You are not an admin!','_self') </script>";
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Brands - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            padding: 40px;
        }
        .table-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 24px;
            max-width: 1000px;
            margin: 0 auto;
        }
        h2 {
            color:rgb(255, 255, 255);
            text-align: center;
            margin-bottom: 24px;
        }
		 h1 {
            color: #232f3e;
            text-align: center;
            margin-bottom: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background-color: #f9fafb;
            color: #111827;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f1f5f9;
        }
        a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h1>View All Brands</h1>
        <table>
            <thead>
                <tr>
                    <th>S.NO</th>
                    <th>Brand ID</th>
                    <th>Brand Title</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
            <?php
            include("includes/db.php");
            $get_brand = "SELECT * FROM brands";
            $run_brand = mysqli_query($con, $get_brand);
            $i = 0;
            while ($row_brand = mysqli_fetch_array($run_brand)) {
                $brand_id = $row_brand['brand_id'];
                $brand_title = $row_brand['brand_title'];
                $i++;
            ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $brand_id; ?></td>
                    <td><?php echo $brand_title; ?></td>
                    <td><a href="index.php?edit_brand=<?php echo $brand_id; ?>">Edit</a></td>
                    <td><a href="delete_brand.php?delete_brand=<?php echo $brand_id; ?>">Delete</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php } ?>
