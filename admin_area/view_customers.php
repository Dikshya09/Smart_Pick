<!DOCTYPE html>
<?php
if (!isset($_SESSION['user_email'])) {
    echo "<script> window.open('login.php?not_admin=You are not an admin!','_self') </script>";
} else {
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            padding: 40px;
            display: flex;
            justify-content: center;
        }
        .container {
            width: 100%;
            max-width: 1000px;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 24px;
            color: #111827;
        }
		h2 {
            text-align: center;
            margin-bottom: 24px;
            color:rgb(245, 246, 247);
		}
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background-color: #e2e8f0;
            color: #374151;
            font-weight: 600;
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
<div class="container">
    <h1>View All Customers</h1>
    <table>
        <tr>
            <th>S.N</th>
            <th>Name</th>
            <th>Email</th>
            <th>Image</th>
            <th>Delete</th>
        </tr>
        <?php
        include("includes/db.php");
        $get_c = "SELECT * FROM customers";
        $run_c = mysqli_query($con, $get_c);
        $i = 0;
        while ($row_c = mysqli_fetch_array($run_c)) {
            $c_id = $row_c['customer_id'];
            $c_name = $row_c['customer_name'];
            $c_email = $row_c['customer_email'];
            $c_image = $row_c['customer_image'];
            $i++;
            echo "<tr>
                <td>$i</td>
                <td>$c_name</td>
                <td>$c_email</td>
                <td><img src='../customer/customer_images/$c_image' width='60' height='50'></td>
                <td><a href='delete_c.php?delete_c=$c_id'>Delete</a></td>
            </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
<?php } ?>
