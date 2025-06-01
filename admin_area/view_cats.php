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
    <title>View Categories - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            padding: 40px;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #111827;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: center;
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background-color: #232f3e;
            color: #ffffff;
            text-transform: uppercase;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f9fafb;
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
    <div class="container">
        <h2>View All Categories</h2>
        <table>
            <tr>
                <th>S.NO</th>
                <th>Category ID</th>
                <th>Category Title</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php
            include("includes/db.php");
            $get_cat = "SELECT * FROM categories";
            $run_cat = mysqli_query($con, $get_cat);
            $i = 0;
            while ($row_cat = mysqli_fetch_array($run_cat)) {
                $cat_id = $row_cat['cat_id'];
                $cat_title = $row_cat['cat_title'];
                $i++;
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $cat_id; ?></td>
                <td><?php echo $cat_title; ?></td>
                <td><a href="index.php?edit_cat=<?php echo $cat_id; ?>">Edit</a></td>
                <td><a href="delete_cat.php?delete_cat=<?php echo $cat_id; ?>">Delete</a></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
<?php } ?>
