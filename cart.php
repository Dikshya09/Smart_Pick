<!DOCTYPE html>
<?php
session_start();
include("functions/functions.php");
include("header.php");
?>

<html>
<head>
  <title>Shopping Cart</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fff;
      color: black;
    }
    #products_box {
      width: 80%;
      margin: 20px auto;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 10px;
      background-color: white;
    }
    #products_box h2 {
      text-align: center;
      color: black;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 15px;
      text-align: center;
      border-top: 1px solid #ddd;
    }
    th {
      color: black;
    }
    img {
      max-width: 80px;
      height: auto;
    }
    .qty-btn {
      padding: 5px 10px;
      background-color: black;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .qty-input {
      width: 40px;
      text-align: center;
    }
    .action-btn {
      background-color: black;
      color: white;
      border: none;
      border-radius: 5px;
      padding: 10px 20px;
      font-weight: bold;
      cursor: pointer;
    }
    .action-btn:disabled {
      background-color: #e0e0e0;
      color: #888;
      cursor: not-allowed;
    }
    .related-products {
      margin: 3rem;
      padding: 2rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      background: #fff;
    }
    .related-products h3 {
      color: black;
      margin-bottom: 1rem;
      text-align: center;
    }
    .related-products .products {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 1.5rem;
    }
    .product-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 6px;
      overflow: hidden;
      height: 320px;
      padding: 0.5rem;
    }
    .product-card img {
      width: 180px;
      height: 180px;
      object-fit: contain;
      background: #fafafa;
      margin: auto;
    }
    .product-info {
      text-align: center;
      flex: 1;
      padding: 0.5rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      color: black;
    }
    .product-info h4 {
      font-size: 1rem;
      margin-bottom: 0.5rem;
      line-height: 1.2em;
      height: 2.4em;
      overflow: hidden;
    }
    .product-info p.price {
      font-weight: bold;
      margin-bottom: 0.75rem;
    }
    .product-actions {
      display: flex;
      justify-content: center;
      gap: 0.5rem;
      margin-bottom: 0.5rem;
    }
    .product-actions .btn {
      padding: 0.5rem 1rem;
      background: #000;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 0.85rem;
      cursor: pointer;
      transition: background 0.2s;
    }
    .product-actions .btn:hover {
      background: #333;
    }
  </style>
</head>
<body>
  <div id="products_box">
    <h2>Shopping Cart</h2>
    <form method="post">
      <table>
        <tr>
          <th>Remove</th>
          <th>Product</th>
          <th>Quantity</th>
          <th>Total Price</th>
        </tr>
        
        <?php
        $total = 0;
        $ip = getIp();
        $sel_price = "SELECT * FROM cart WHERE ip_add='$ip'";
        $run_price = mysqli_query($con, $sel_price);
        while ($p_price = mysqli_fetch_array($run_price)) {
          $pro_id = $p_price['p_id'];
          $qty = $p_price['qty'];
          $pro_price = "SELECT * FROM products WHERE product_id='$pro_id'";
          $run_pro_price = mysqli_query($con, $pro_price);
          while ($pp_price = mysqli_fetch_array($run_pro_price)) {
            $product_title = $pp_price['product_title'];
            $product_image = $pp_price['product_image'];
            $product_price = $pp_price['product_price'];
            $product_total = $product_price * $qty;
            $total += $product_total;
        ?>
        <tr>
          <td><input type="checkbox" name="remove[]" value="<?php echo $pro_id; ?>" /></td>
          <td><?php echo $product_title; ?><br><img src="admin_area/product_images/<?php echo $product_image; ?>" /></td>
          <td>
            <button type="submit" name="decrement[<?php echo $pro_id; ?>]" class="qty-btn">-</button>
            <input type="text" name="qty[<?php echo $pro_id; ?>]" class="qty-input" value="<?php echo $qty; ?>" readonly />
            <button type="submit" name="increment[<?php echo $pro_id; ?>]" class="qty-btn">+</button>
          </td>
          <td>Rs <?php echo $product_total; ?></td>
        </tr>
        <?php } } ?>
        <tr>
          <td colspan="3"><b>Sub Total:</b></td>
          <td>Rs <?php echo $total; ?></td>
        </tr>
        <tr>
          <td colspan="3">
            <input type="submit" name="continue" value="Continue Shopping" class="action-btn" />
            <input type="submit" name="update_cart" value="Update Cart" class="action-btn" />
          </td>
          <td>
            <button type="submit" name="checkout" class="action-btn">Checkout</button>
          </td>
        </tr>
      </table>
    </form>
  </div>

  <div class="related-products">
    <h3>Products related to your Browsing History</h3>
    <div class="products">
      <?php
$related_pro = mysqli_query($con, "SELECT * FROM products WHERE product_stock > 0 ORDER BY RAND() LIMIT 4");
while ($row = mysqli_fetch_array($related_pro)) {
  $id    = $row['product_id'];
  $title = $row['product_title'];
  $price = $row['product_price'];
  $img   = $row['product_image'];
?>
  <div class="product-card">
    <img src="admin_area/product_images/<?= $img ?>" alt="<?= htmlspecialchars($title) ?>">
    <div class="product-info">
      <h4><?= htmlspecialchars($title) ?></h4>
      <p class="price">Rs <?= number_format($price) ?></p>
    </div>
    <div class="product-actions">
      <button class="btn" onclick="location.href='?add_cart=<?= $id ?>'">Add to Cart</button>
      <button class="btn" onclick="location.href='product_detail.php?product_id=<?= $id ?>'">View Details</button>
    </div>
  </div>
<?php } ?>

      
    </div>
  </div>

  <?php
  $ip = getIp();

  if (isset($_POST['update_cart'])) {
    if (!empty($_POST['remove'])) {
      foreach ($_POST['remove'] as $remove_id) {
        mysqli_query($con, "DELETE FROM cart WHERE p_id='$remove_id' AND ip_add='$ip'");
      }
    }
    echo "<script>window.open('cart.php', '_self')</script>";
  }

  if (isset($_POST['continue'])) {
    echo "<script>window.open('index.php', '_self')</script>";
  }

  if (isset($_POST['increment'])) {
    $key = array_keys($_POST['increment'])[0];
    $current_qty = mysqli_fetch_assoc(mysqli_query($con, "SELECT qty FROM cart WHERE p_id='$key' AND ip_add='$ip'"))['qty'];
    if ($current_qty < 100) {
      mysqli_query($con, "UPDATE cart SET qty = qty + 1 WHERE p_id='$key' AND ip_add='$ip'");
      echo "<script>window.open('cart.php', '_self')</script>";
    }
  }

  if (isset($_POST['decrement'])) {
    $key = array_keys($_POST['decrement'])[0];
    $current_qty = mysqli_fetch_assoc(mysqli_query($con, "SELECT qty FROM cart WHERE p_id='$key' AND ip_add='$ip'"))['qty'];
    if ($current_qty > 1) {
      mysqli_query($con, "UPDATE cart SET qty = qty - 1 WHERE p_id='$key' AND ip_add='$ip'");
      echo "<script>window.open('cart.php', '_self')</script>";
    }
  }

  if (isset($_POST['checkout'])) {
    if ($total == 0) {
      echo "<script>alert('Please select at least one product before checking out.');</script>";
    } else {
      if (isset($_SESSION['customer_email'])) {
        echo "<script>window.open('checkout.php', '_self')</script>";
      } else {
        echo "<script>window.open('customer_login.php', '_self')</script>";
      }
    }
  }
  ?>
</body>
</html>

<?php
if (isset($_GET['add_cart'])) {
  $pro_id = $_GET['add_cart'];
  $ip = getIp();

  $check_pro = mysqli_query($con, "SELECT * FROM cart WHERE p_id='$pro_id' AND ip_add='$ip'");

  if (mysqli_num_rows($check_pro) == 0) {
    mysqli_query($con, "INSERT INTO cart (p_id, ip_add, qty) VALUES ('$pro_id', '$ip', 1)");
  }

  echo "<script>window.open('index.php','_self')</script>";
}
?>
