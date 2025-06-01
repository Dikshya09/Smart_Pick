<?php
$con = mysqli_connect("localhost", "root", "", "ecom");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['customer_email'])) {
    $customer_email = $_SESSION['customer_email'];
    $get_customer = "SELECT customer_name, customer_image FROM customers WHERE customer_email='$customer_email'";
    $run_customer = mysqli_query($con, $get_customer);
    if ($run_customer && mysqli_num_rows($run_customer) > 0) {
        $row_customer = mysqli_fetch_array($run_customer);
        $_SESSION['customer_name'] = $row_customer['customer_name'];
        $GLOBALS['customer_image'] = 'customer/customer_images/' . $row_customer['customer_image'];
    }
}

if (!function_exists('getIp')) {
    function getIp() {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $ip;
    }
}

// Duplicate declaration removed

if (!function_exists('cart')) {
    function cart() {
        global $con;
        if (isset($_GET['add_cart'])) {
            $ip = getIp();
            $pro_id = $_GET['add_cart'];
            $check_pro = "SELECT * FROM cart WHERE ip_add='$ip' AND p_id='$pro_id'";
            $run_check = mysqli_query($con, $check_pro) or die('error');
            if (mysqli_num_rows($run_check) > 0) {
                echo "<script>alert('Product has already been added once.')</script>";
                echo "<script>window.open('index.php','_self')</script>";
            } else {
                $insert_pro = "INSERT INTO cart(p_id, qty, ip_add) VALUES('$pro_id','1','$ip')";
                mysqli_query($con, $insert_pro);
                echo "<script>window.open('index.php','_self')</script>";
            }
        }
    }
}

if (!function_exists('total_items')) {
    function total_items() {
        global $con;
        $ip = getIp();
        $get_items = "SELECT * FROM cart WHERE ip_add='$ip'";
        $run_items = mysqli_query($con, $get_items);
        echo mysqli_num_rows($run_items);
    }
}

if (!function_exists('total_price')) {
    function total_price() {
        $total = 0;
        global $con;
        $ip = getIp();
        $sel_price = "SELECT * FROM cart WHERE ip_add='$ip'";
        $run_price = mysqli_query($con, $sel_price);
        while ($p_price = mysqli_fetch_array($run_price)) {
            $pro_id = $p_price['p_id'];
            $pro_price = "SELECT * FROM products WHERE product_id='$pro_id'";
            $run_pro_price = mysqli_query($con, $pro_price);
            while ($pp_price = mysqli_fetch_array($run_pro_price)) {
                $total += $pp_price['product_price'];
            }
        }
        echo " Rs $total";
    }
}

if (!function_exists('getCats')) {
    function getCats() {
        global $con;
        $get_cats = "SELECT * FROM categories";
        $run_cats = mysqli_query($con, $get_cats);
        while ($row_cats = mysqli_fetch_array($run_cats)) {
            $cat_id = $row_cats['cat_id'];
            $cat_title = $row_cats['cat_title'];
            echo "<li><a href='index.php?cat=$cat_id'>$cat_title</a></li>";
        }
    }
}

if (!function_exists('getBrands')) {
    function getBrands() {
        global $con;
        $get_brands = "SELECT * FROM brands";
        $run_brands = mysqli_query($con, $get_brands);
        while ($row_brands = mysqli_fetch_array($run_brands)) {
            $brand_id = $row_brands['brand_id'];
            $brand_title = $row_brands['brand_title'];
            echo "<li><a href='index.php?brand=$brand_id'>$brand_title</a></li>";
        }
    }
}

if (!function_exists('getpro')) {
    function getpro() {
        if (!isset($_GET['cat']) && !isset($_GET['brand'])) {
            echo '<link rel="stylesheet" href="styles/style.css" media="all" />';
            global $con;
            $get_pro = "SELECT * FROM products";
            $run_pro = mysqli_query($con, $get_pro);
            while ($row_pro = mysqli_fetch_array($run_pro)) {
                $pro_id = $row_pro['product_id'];
                $pro_title = $row_pro['product_title'];
                $pro_price = $row_pro['product_price'];
                $pro_image = $row_pro[5];
                echo "<div id='single_product'>
                        <h3>$pro_title</h3>
                        <img src='admin_area/product_images/$pro_image' width='180' height='180' />
                        <p> Rs <b>$pro_price</b></p>
                        <a href='details.php?pro_id=$pro_id' style='float:left'>Details</a>
                        <a href='index.php?add_cart=$pro_id'><button style='float:right'>Add to cart</button></a>
                      </div>";
            }
        }
    }
}

if (!function_exists('getcatpro')) {
    function getcatpro() {
        if (isset($_GET['cat'])) {
            $cat_id = $_GET['cat'];
            echo '<link rel="stylesheet" href="styles/style.css" media="all" />';
            global $con;
            $get_cat_pro = "SELECT * FROM products WHERE product_cat='$cat_id'";
            $run_cat_pro = mysqli_query($con, $get_cat_pro);
            if (mysqli_num_rows($run_cat_pro) == 0) {
                echo "<h2>Sorry!</h2><h3>No Products available for this category</h3>";
            } else {
                while ($row_cat_pro = mysqli_fetch_array($run_cat_pro)) {
                    $pro_id = $row_cat_pro['product_id'];
                    $pro_title = $row_cat_pro['product_title'];
                    $pro_price = $row_cat_pro['product_price'];
                    $pro_image = $row_cat_pro[5];
                    echo "<div id='single_product'>
                            <h3>$pro_title</h3>
                            <img src='admin_area/product_images/$pro_image' width='180' height='180' />
                            <p> Rs <b>$pro_price</b></p>
                            <a href='details.php?pro_id=$pro_id' style='float:left'>Details</a>
                            <a href='index.php?add_cart=$pro_id'><button style='float:right'>Add to cart</button></a>
                          </div>";
                }
            }
        }
    }
}

if (!function_exists('getbrandpro')) {
    function getbrandpro() {
        if (isset($_GET['brand'])) {
            $brand_id = $_GET['brand'];
            echo '<link rel="stylesheet" href="styles/style.css" media="all" />';
            global $con;
            $get_brand_pro = "SELECT * FROM products WHERE product_brand='$brand_id'";
            $run_brand_pro = mysqli_query($con, $get_brand_pro);
            if (mysqli_num_rows($run_brand_pro) == 0) {
                echo "<h2>Sorry!</h2><h3>No Products available for this Brand.</h3>";
            } else {
                while ($row_brand_pro = mysqli_fetch_array($run_brand_pro)) {
                    $pro_id = $row_brand_pro['product_id'];
                    $pro_title = $row_brand_pro['product_title'];
                    $pro_price = $row_brand_pro['product_price'];
                    $pro_image = $row_brand_pro[5];
                    echo "<div id='single_product'>
                            <h3>$pro_title</h3>
                            <img src='admin_area/product_images/$pro_image' width='180' height='180' />
                            <p> Rs <b>$pro_price</b></p>
                            <a href='details.php?pro_id=$pro_id' style='float:left'>Details</a>
                            <a href='index.php?add_cart=$pro_id'><button style='float:right'>Add to cart</button></a>
                          </div>";
                }
            }
        }
    }
}
?>
