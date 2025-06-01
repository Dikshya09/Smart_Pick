<?php
session_start();

// Ensure a session is started to access session variables
if (!isset($_SESSION['user_email'])) {
    echo "<script>window.open('login.php?not_admin=You are not an admin!','_self')</script>";
    exit();
} else {
    include("includes/db.php");

    if (isset($_GET['delete_pro'])) {
        $delete_id = intval($_GET['delete_pro']); // Sanitize input

        // Check if product exists before trying to delete
        $check_query = "SELECT * FROM products WHERE product_id = $delete_id";
        $check_result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $delete_pro = "DELETE FROM products WHERE product_id = $delete_id";
            $run_delete = mysqli_query($con, $delete_pro);

            if ($run_delete) {
                echo "<script>alert('Product has been deleted..!')</script>";
            } else {
                echo "<script>alert('Failed to delete product.')</script>";
            }
        } else {
            echo "<script>alert('Product not found.')</script>";
        }

        echo "<script>window.open('index.php?view_products','_self')</script>";
    }
}?>
