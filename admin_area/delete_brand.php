<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    echo "<script>window.open('login.php?not_admin=You are not an admin!','_self')</script>";
    exit();
} else {
    include("includes/db.php");

    if (isset($_GET['delete_brand'])) {
        $delete_id = intval($_GET['delete_brand']);

        // Check if the brand is associated with any products
        $check_products = "SELECT * FROM products WHERE product_brand = $delete_id";
        $check_result = mysqli_query($con, $check_products);

        if (mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('Cannot delete brand: it is linked to existing products.')</script>";
        } else {
            $delete_brand = "DELETE FROM brands WHERE brand_id = $delete_id";
            $run_delete = mysqli_query($con, $delete_brand);

            if ($run_delete) {
                echo "<script>alert('Brand has been deleted..!')</script>";
            } else {
                echo "<script>alert('Failed to delete brand.')</script>";
            }
        }

        echo "<script>window.open('index.php?view_cats','_self')</script>";
    }
}
?>
