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
    <title>Add Category - Admin Panel</title>
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
        .form-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #111827;
            margin-bottom: 24px;
        }
        h2 {
            color:rgb(250, 250, 252);
            margin-bottom: 24px;
        }
        .input-group {
            margin-bottom: 16px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
        }
        .error {
            color: red;
            font-size: 0.9em;
            margin-bottom: 6px;
        }
        input[type="submit"] {
            background-color: #232f3e;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        input[type="submit"]:hover {
            background-color: #37475a;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Insert New Category</h1>
        <form id="categoryForm" action="" method="post" onsubmit="return validateCategoryForm()">
            <div class="input-group">
                <span class="error" id="catError"></span>
                <label for="new_cat">Category Name:</label>
                <input type="text" name="new_cat" id="new_cat">
            </div>
            <input type="submit" name="add_cat" value="Add Category">
        </form>
    </div>

    <script>
        function validateCategoryForm() {
            let isValid = true;
            const catInput = document.getElementById('new_cat');
            const catError = document.getElementById('catError');

            catError.textContent = "";

            if (catInput.value.trim() === "") {
                catError.textContent = "Category name is required.";
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>
</html>
<?php
include("includes/db.php");

if (isset($_POST['add_cat'])) {
    $new_cat = $_POST['new_cat'];

    if (!empty($new_cat)) {
        $insert_cat = "INSERT INTO categories (cat_title) VALUES('$new_cat')";
        $run_cat = mysqli_query($con, $insert_cat);

        if ($run_cat) {
            echo "<script>alert('New Category inserted Successfully..!')</script>";
            echo "<script> window.open('index.php?view_cats','_self') </script>";
        }
    }
}
?>
<?php } ?>