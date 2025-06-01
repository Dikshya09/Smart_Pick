<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include("functions/functions.php");
include("header.php");

cart();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ElectroShop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    :root {
      --bg: #fff;
      --fg: #fff;
      --fg-light: #ccc;
      --border: #fff;
      --radius: 6px;
      --gap: 1rem;
      --btn-bg: #000;
      --btn-fg: #fff;
      --btn-hover-bg: #333;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: sans-serif;
      background: rgb(247, 247, 247);
      color: var(--fg);
      line-height: 1.5;
      padding-top: 70px;
    }
    a { color: inherit; text-decoration: none; }
    ul { list-style: none; }

    .banner { margin: var(--gap) 2rem; border-radius: var(--radius); overflow: hidden; }
    .banner img { width: 100%; display: block; }
    .content { display: grid; grid-template-columns: 250px 1fr; gap: 2rem; padding: 0 var(--gap); }
    .sidebar {
      background: var(--bg); border: 1px solid var(--border);
      border-radius: var(--radius); padding: var(--gap); color: black;
    }
    .sidebar h3 { margin-bottom: 0.75rem; font-size: 1.1rem; }
    .sidebar ul li + li { margin-top: 0.5rem; }
    .products {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px,1fr));
      gap: 1.5rem;
    }
    .product-card {
      display: flex; flex-direction: column;
      background: var(--bg); border: 1px solid #ccc;
      border-radius: var(--radius); overflow: hidden;
      height: 280px;
      transition: 0.3s;
    }
    .product-card img {
      width: 100%; height: 120px; object-fit: contain; margin-bottom: 0.5rem;
    }
    .product-info {
      flex: 1; padding: 0.5rem;
      display: flex; flex-direction: column; color: black;
    }
    .product-info h4 {
      font-size: 0.9rem; margin-bottom: 0.5rem;
      line-height: 1.2em; height: 2.4em; overflow: hidden;
    }
    .product-info p.price {
      font-weight: bold; margin-bottom: 0.75rem;
    }
    .product-actions {
      padding: 0.5rem; border-top: 1px solid #eee;
      display: flex; gap: 0.5rem;
    }
    .product-actions .btn {
      flex: 1; padding: 0.4rem;
      background: var(--btn-bg); color: var(--btn-fg);
      border: none; border-radius: var(--radius);
      font-size: 0.85rem; cursor: pointer; transition: background 0.2s;
    }
    .product-actions .btn:hover { background: var(--btn-hover-bg); }
  </style>
</head>
<body>
  <div class="banner">
    <img src="images/banner.avif" alt="Banner">
  </div>

  <main class="content">
    <aside class="sidebar">
      <h3>Categories</h3>
      <ul><?php getCats(); ?></ul>
      <h3 style="margin-top:1.5rem;">Brands</h3>
      <ul><?php getBrands(); ?></ul>
    </aside>

    <section class="products">
      <?php
      include("includes/db.php");

      if (isset($_GET['cat'])) {
        $cat_id = $_GET['cat'];
        $run_pro = mysqli_query($con, "SELECT * FROM products WHERE product_cat='$cat_id' AND product_stock > 0");
      } elseif (isset($_GET['brand'])) {
        $brand_id = $_GET['brand'];
        $run_pro = mysqli_query($con, "SELECT * FROM products WHERE product_brand='$brand_id' AND product_stock > 0");
      } else {
        $run_pro = mysqli_query($con, "SELECT * FROM products WHERE product_stock > 0");
      }

      while ($row = mysqli_fetch_array($run_pro)) {
        $id    = $row['product_id'];
        $title = $row['product_title'];
        $price = $row['product_price'];
        $img   = $row['product_image'];
        ?>
        <div class="product-card">
          <img src="admin_area/product_images/<?php echo $img; ?>" alt="<?php echo htmlspecialchars($title); ?>">
          <div class="product-info">
            <h4><?php echo htmlspecialchars($title); ?></h4>
            <p class="price">Rs <?php echo number_format($price); ?></p>
          </div>
          <div class="product-actions">
            <a href="index.php?add_cart=<?php echo $id; ?>" class="btn">Add to Cart</a>
            <a href="product_detail.php?product_id=<?php echo $id; ?>" class="btn">View Details</a>
          </div>
        </div>
      <?php } ?>
    </section>
  </main>

  <footer></footer>
</body>
</html>
