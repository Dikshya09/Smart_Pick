<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_email'])) {
    echo "<script> window.open('login.php?not_admin=You are not an admin!','_self') </script>";
    exit();
}

include("includes/db.php");

$error = "";
$brand_title = "";

if (isset($_GET['edit_brand'])) {
    $brand_id = $_GET['edit_brand'];
    $get_brand = "SELECT * FROM brands WHERE brand_id='$brand_id'";
    $run_brand = mysqli_query($con, $get_brand);
    $row_brand = mysqli_fetch_array($run_brand);
    $brand_title = $row_brand['brand_title'];
}

if (isset($_POST['update_brand'])) {
    $new_brand = trim($_POST['new_brand']);

    if (empty($new_brand)) {
        $error = "Brand name cannot be empty.";
    } else {
        $update_brand = "UPDATE brands SET brand_title='$new_brand' WHERE brand_id='$brand_id'";
        $run_brand = mysqli_query($con, $update_brand);

        if ($run_brand) {
            echo "<script>alert('Brand updated successfully..!')</script>";
            echo "<script>window.open('index.php?view_brands','_self')</script>";
        } else {
            $error = "Failed to update brand.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Brand</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .form-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .form-box h2 {
            color: #232f3e;
            margin-bottom: 20px;
        }
        .error {
            color: #b91c1c;
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            margin-bottom: 20px;
        }
        input[type="submit"] {
            background-color: #232f3e;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
        }
        input[type="submit"]:hover {
            background-color: #37475a;
        }
    </style>
</head>
<body>
<div class="form-box">
    <h2>Update Brand</h2>
    <?php if (!empty($error)): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <form action="" method="post">
        <input type="text" name="new_brand" value="<?= htmlspecialchars($brand_title) ?>">
        <input type="submit" name="update_brand" value="Update Brand">
    </form>
</div>
</body>
</html>