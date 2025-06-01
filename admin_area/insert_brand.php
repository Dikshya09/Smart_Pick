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

if (isset($_POST['add_brand'])) {
    $new_brand = trim($_POST['new_brand']);

    if (empty($new_brand)) {
        $error = "Brand name cannot be empty.";
    } else {
        $insert_brand = "INSERT INTO brands (brand_title) VALUES('$new_brand')";
        $run_brand = mysqli_query($con, $insert_brand);

        if ($run_brand) {
            echo "<script>alert('New brand inserted Successfully..!')</script>";
            echo "<script> window.open('index.php?view_brands','_self') </script>";
        } else {
            $error = "Failed to insert brand. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Brand - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            padding: 40px;
            min-height: 100vh;
        }
        .container {
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            border-radius: 12px;
            width: 100%;
            max-width: 600px;
        }
        h1 {
            margin-bottom: 24px;
            color: #111827;
            font-size: 1.5rem;
            font-weight: 600;
        }
            h2 {
            margin-bottom: 24px;
            color:rgb(247, 247, 248);
            font-size: 1.5rem;
            font-weight: 600;
        }
        .error {
            color: #b91c1c;
            
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1rem;
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
    <div class="container">
        <h1>Insert New Brand</h1>
        <form action="" method="post">
            <?php if (!empty($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            <input type="text" name="new_brand" placeholder="Enter brand name" value="<?= isset($_POST['new_brand']) ? htmlspecialchars($_POST['new_brand']) : '' ?>">
            <input type="submit" name="add_brand" value="Add Brand">
        </form>
    </div>
</body>
</html>
