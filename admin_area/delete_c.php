<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    echo "<script>window.open('login.php?not_admin=You are not an admin!','_self')</script>";
    exit();
} else {
    include("includes/db.php");

    if (isset($_GET['delete_c'])) {
        $delete_id = intval($_GET['delete_c']);

        // Check if customer is associated with any orders
        $check_orders = "SELECT * FROM orders WHERE customer_id = $delete_id";
        $order_result = mysqli_query($con, $check_orders);

        if (mysqli_num_rows($order_result) > 0) {
            echo "<script>alert('Cannot delete customer: they have existing orders.')</script>";
        } else {
            $delete_c = "DELETE FROM customers WHERE customer_id = $delete_id";
            $run_delete = mysqli_query($con, $delete_c);

            if ($run_delete) {
                echo "<script>alert('A customer has been deleted..!')</script>";
            } else {
                echo "<script>alert('Failed to delete customer.')</script>";
            }
        }

        echo "<script>window.open('index.php?view_customers','_self')</script>";
    }
}
?>
