

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
$success = "";

if (isset($_GET['edit_pro'])) {
    $get_id = $_GET['edit_pro'];
    $get_pro = "SELECT * FROM products WHERE product_id='$get_id'";
    $run_pro = mysqli_query($con, $get_pro);
    $row_pro = mysqli_fetch_array($run_pro);

    $pro_id = $row_pro['product_id'];
    $pro_title = $row_pro['product_title'];
    $pro_image = $row_pro['product_image'];
    $pro_price = $row_pro['product_price'];
    $pro_stock = $row_pro['product_stock'];

    $pro_desc = $row_pro['product_desc'];
    $pro_keywords = $row_pro['product_keywords'];
    $pro_cat = $row_pro['product_cat'];
    $pro_brand = $row_pro['product_brand'];

    $category_title = mysqli_fetch_assoc(mysqli_query($con, "SELECT cat_title FROM categories WHERE cat_id='$pro_cat'"))['cat_title'];
    $brand_title = mysqli_fetch_assoc(mysqli_query($con, "SELECT brand_title FROM brands WHERE brand_id='$pro_brand'"))['brand_title'];

    if (isset($_POST['update_product'])) {
        $product_title = mysqli_real_escape_string($con, trim($_POST['product_title']));
        $product_cat = mysqli_real_escape_string($con, $_POST['product_cat']);
        $product_brand = mysqli_real_escape_string($con, $_POST['product_brand']);
        $product_price = mysqli_real_escape_string($con, trim($_POST['product_price']));
        $product_stock = mysqli_real_escape_string($con, trim($_POST['product_stock']));
        $product_desc = mysqli_real_escape_string($con, trim($_POST['product_desc']));
        $product_keywords = mysqli_real_escape_string($con, trim($_POST['product_keywords']));

        $product_image = $_FILES['product_image']['name'];
        $product_image_tmp = $_FILES['product_image']['tmp_name'];

        if (!$product_title || !$product_price || !$product_stock || !$product_desc || !$product_keywords) {
            $error = "All fields must be filled except image.";
        } else {
            if ($product_image) {
                move_uploaded_file($product_image_tmp, "product_images/$product_image");
            } else {
                $product_image = $pro_image;
            }

            $query = "UPDATE products SET 
                        product_cat='$product_cat', 
                        product_brand='$product_brand', 
                        product_title='$product_title', 
                        product_price='$product_price', 
                        product_stock='$product_stock', 
                        product_desc='$product_desc', 
                        product_image='$product_image', 
                        product_keywords='$product_keywords' 
                      WHERE product_id='$pro_id'";

            $run_query = mysqli_query($con, $query);
            if ($run_query) {
                $success = "Product has been updated!";
                $pro_image = $product_image;
                $pro_price = $product_price;
                $pro_stock = $product_stock;
                $pro_title = $product_title;
                $pro_desc = $product_desc;
                $pro_keywords = $product_keywords;
            } else {
                $error = "Failed to update product.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
            padding: 40px;
        }
        .form-container {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        h2 {
            margin-bottom: 20px;
            color:rgb(246, 248, 249);
        }
        h1 {
            margin-bottom: 20px;
            color: #232f3e;
        }
        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
            color: #374151;
        }
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
        }
        input[type="file"] {
            margin-top: 5px;
        }
        .message {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        .error {  color: #b91c1c;  }
        .success {  color: #065f46;  }
        button {
            margin-top: 20px;
            background-color: #232f3e;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        button:hover {
            background-color: #37475a;
        }
        img.preview {
            margin-top: 10px;
            max-height: 60px;
            display: block;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h1>Edit Product</h1>
    <?php if (!empty($error)): ?><div class="message error"><?= $error ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="message success"><?= $success ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label>Product Title</label>
        <input type="text" name="product_title" value="<?= htmlspecialchars($pro_title) ?>">

        <label>Product Category</label>
        <select name="product_cat">
            <option value="<?= $pro_cat ?>"><?= $category_title ?></option>
            <?php
            $cats = mysqli_query($con, "SELECT * FROM categories");
            while ($cat = mysqli_fetch_assoc($cats)) {
                echo "<option value='{$cat['cat_id']}'>{$cat['cat_title']}</option>";
            }
            ?>
        </select>

        <label>Product Brand</label>
        <select name="product_brand">
            <option value="<?= $pro_brand ?>"><?= $brand_title ?></option>
            <?php
            $brands = mysqli_query($con, "SELECT * FROM brands");
            while ($brand = mysqli_fetch_assoc($brands)) {
                echo "<option value='{$brand['brand_id']}'>{$brand['brand_title']}</option>";
            }
            ?>
        </select>

        <label>Product Image</label>
        <input type="file" name="product_image">
        <img class="preview" src="product_images/<?= $pro_image ?>" alt="Current Image">

        <label>Product Price</label>
        <input type="text" name="product_price" value="<?= htmlspecialchars($pro_price) ?>">

        <label>Product Stock</label>
        <input type="text" name="product_stock" value="<?= htmlspecialchars($pro_stock) ?>">

        <label>Product Description</label>
        <textarea name="product_desc" rows="6"><?= htmlspecialchars($pro_desc) ?></textarea>

        <label>Product Keywords</label>
        <input type="text" name="product_keywords" value="<?= htmlspecialchars($pro_keywords) ?>">

        <button type="submit" name="update_product">Update Product</button>
    </form>
</div>
</body>
</html>
