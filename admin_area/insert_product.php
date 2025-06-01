

<!DOCTYPE html>

<?php
include("includes/db.php");

if (!isset($_SESSION['user_email'])) {
    echo "<script> window.open('login.php?not_admin=You are not an admin!','_self') </script>";
} else {
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Product - Admin Panel</title>
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
        form {
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
        }
        h2 {
            text-align: center;
            margin-bottom: 24px;
            color:rgb(251, 251, 251);
        }
        h1 {
            text-align: center;
            margin-bottom: 24px;
            color: #111827;
        }
        table {
            width: 100%;
        }
        td {
            padding: 12px;
            vertical-align: top;
        }
        input[type="text"],
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
        }
        textarea {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 10px;
        }
        input[type="submit"] {
            background-color: #232f3e;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 16px;
        }
        input[type="submit"]:hover {
            background-color: #37475a;
        }
        .error {
            color: red;
            font-size: 0.9em;
            margin-bottom: 4px;
            display: block;
        }
    </style>
</head>
<body>
    <form id="productForm" action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <h1>Insert Product</h1>
        <table>
            <tr>
                <td><span class="error" id="titleError"></span>Product Title:</td>
                <td><input type="text" name="product_title" id="product_title"></td>
            </tr>
            <tr>
                <td><span class="error" id="catError"></span>Product Category:</td>
                <td>
                    <select name="product_cat" id="product_cat">
                        <option value="">Select a Category</option>
                        <?php
                        $get_cats = "select * from categories";
                        $run_cats = mysqli_query($con, $get_cats);
                        while ($row_cats = mysqli_fetch_array($run_cats)) {
                            $cat_id = $row_cats['cat_id'];
                            $cat_title = $row_cats['cat_title'];
                            echo "<option value='$cat_id'>$cat_title</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><span class="error" id="brandError"></span>Product Brand:</td>
                <td>
                    <select name="product_brand" id="product_brand">
                        <option value="">Select a Brand</option>
                        <?php
                        $get_brands = "select * from brands";
                        $run_brands = mysqli_query($con, $get_brands);
                        while ($row_brands = mysqli_fetch_array($run_brands)) {
                            $brand_id = $row_brands['brand_id'];
                            $brand_title = $row_brands['brand_title'];
                            echo "<option value='$brand_id'>$brand_title</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><span class="error" id="imageError"></span>Product Image:</td>
                <td><input type="file" name="product_image" id="product_image"></td>
            </tr>
            <tr>
                <td><span class="error" id="priceError"></span>Product Price:</td>
                <td><input type="text" name="product_price" id="product_price"></td>
            </tr>
            <tr>
                <td><span class="error" id="stockError"></span>Product Stock:</td>
                <td><input type="text" name="product_stock" id="product_stock"></td>
            </tr>
            <tr>
                <td>Product Description:</td>
                <td><textarea name="product_desc" cols="80" rows="10"></textarea></td>
            </tr>
            <tr>
                <td><span class="error" id="keywordsError"></span>Product Keywords:</td>
                <td><input type="text" name="product_keywords" id="product_keywords"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;">
                    <input type="submit" name="insert_post" value="Insert">
                </td>
            </tr>
        </table>
    </form>
    <script>
        function validateForm() {
            let valid = true;
            document.querySelectorAll(".error").forEach(e => e.textContent = "");

            if (document.getElementById("product_title").value.trim() === "") {
                document.getElementById("titleError").textContent = "Product title is required.";
                valid = false;
            }
            if (document.getElementById("product_cat").value.trim() === "") {
                document.getElementById("catError").textContent = "Please select a category.";
                valid = false;
            }
            if (document.getElementById("product_brand").value.trim() === "") {
                document.getElementById("brandError").textContent = "Please select a brand.";
                valid = false;
            }
            if (document.getElementById("product_image").value.trim() === "") {
                document.getElementById("imageError").textContent = "Please upload a product image.";
                valid = false;
            }
            if (document.getElementById("product_price").value.trim() === "") {
                document.getElementById("priceError").textContent = "Product price is required.";
                valid = false;
            }
            if (document.getElementById("product_keywords").value.trim() === "") {
                document.getElementById("keywordsError").textContent = "Please enter product keywords.";
                valid = false;
            }
            return valid;
        }
    </script>
</body>
</html>
<?php } ?>
<?php
include("includes/db.php");

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user_email'])) {
    echo "<script> window.open('login.php?not_admin=You are not an admin!','_self') </script>";
    exit();
}

if (isset($_POST['insert_post'])) {
    $product_title = mysqli_real_escape_string($con, $_POST['product_title']);
    $product_cat = mysqli_real_escape_string($con, $_POST['product_cat']);
    $product_brand = mysqli_real_escape_string($con, $_POST['product_brand']);
    $product_price = mysqli_real_escape_string($con, $_POST['product_price']);
    $product_desc = mysqli_real_escape_string($con, $_POST['product_desc']);
    $product_keywords = mysqli_real_escape_string($con, $_POST['product_keywords']);
    $product_stock = mysqli_real_escape_string($con, $_POST['product_stock']);

    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp = $_FILES['product_image']['tmp_name'];

    // Check for required fields
    if (!$product_title || !$product_cat || !$product_brand || !$product_price || !$product_keywords || !$product_stock || !$product_image) {
        echo "<script>alert('Please fill in all required fields.');</script>";
    } else {
        move_uploaded_file($product_image_tmp, "product_images/$product_image");

        $insert_product = "INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords, product_stock)
            VALUES ('$product_cat', '$product_brand', '$product_title', '$product_price', '$product_desc', '$product_image', '$product_keywords', '$product_stock')";

        if (mysqli_query($con, $insert_product)) {
            echo "<script>alert('Product has been inserted successfully!'); window.open('index.php?view_products', '_self');</script>";
        } else {
            echo "<script>alert('Database Error: Failed to insert product.');</script>";
        }
    }
}
?>
