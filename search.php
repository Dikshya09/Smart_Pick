<?php
session_start();
include("includes/db.php");
include("functions/functions.php");
include("header.php");

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results - SmartPick</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f9f9f9; color: #333; margin: 0; padding: 0; }
    .container { max-width: 1200px; margin: 2rem auto; margin-top: 10rem; padding: 1rem; background: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .products { display: flex; flex-wrap: wrap; gap: 1.5rem; }
    .product-card {
      position: relative; width: 220px; border: 1px solid #ddd; border-radius: 6px; padding: 1rem;
      text-align: center; background: #fff; transition: box-shadow 0.2s; color: black;
    }
    .product-card img { width: 100%; height: 160px; object-fit: contain; margin-bottom: 0.5rem; }
    .product-card h4 { font-size: 1rem; color: #007185; margin: 0.5rem 0; }
    .product-card p { font-size: 0.9rem; color: #000; }
    .product-card.out-of-stock { opacity: 0.5; pointer-events: none; }
    .btn { display: inline-block; padding: 6px 12px; background: #000; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 8px; text-decoration: none; font-size: 0.85rem; }
    .btn:hover { background: #333; }
    .btn-disabled { background-color: #9ca3af; color: white; padding: 6px 12px; border-radius: 4px; cursor: not-allowed; border: none; font-size: 0.85rem; }
    .stock-overlay {
      position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg);
      font-size: 24px; color: rgba(255, 0, 0, 0.7); font-weight: bold; pointer-events: none; z-index: 10;
    }
    .no-results { padding: 2rem; text-align: center; font-size: 1.2rem; color: #666; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Search Results for: "<?= htmlspecialchars($search_query) ?>"</h2>
    <?php
    if ($search_query !== '') {
      $search_query_escaped = mysqli_real_escape_string($con, $search_query);
      $query = "SELECT * FROM products WHERE product_title LIKE '%$search_query_escaped%' OR product_desc LIKE '%$search_query_escaped%' OR product_keywords LIKE '%$search_query_escaped%'";
      $result = mysqli_query($con, $query);

      if (mysqli_num_rows($result) > 0) {
        echo '<div class="products">';
        while ($row = mysqli_fetch_assoc($result)) {
          $id    = $row['product_id'];
          $title = $row['product_title'];
          $price = $row['product_price'];
          $img   = $row['product_image'];
          $stock = $row['product_stock'];
          $is_out_of_stock = ($stock <= 0);

          echo '<div class="product-card ' . ($is_out_of_stock ? 'out-of-stock' : '') . '">';
          if ($is_out_of_stock) echo '<div class="stock-overlay">OUT OF STOCK</div>';
          echo '<img src="admin_area/product_images/' . $img . '" alt="' . htmlspecialchars($title) . '">';
          echo '<h4>' . htmlspecialchars($title) . '</h4>';
          echo '<p>Rs ' . number_format($price) . '</p>';
          if ($is_out_of_stock) {
            echo '<button class="btn-disabled" disabled>Add to Cart</button>';
            echo '<button class="btn-disabled" disabled>View Details</button>';
          } else {
            echo '<a href="index.php?add_cart=' . $id . '" class="btn">Add to Cart</a>';
            echo '<a href="product_detail.php?product_id=' . $id . '" class="btn">View Details</a>';
          }
          echo '</div>';
        }
        echo '</div>';
      } else {
        echo '<div class="no-results">No products found.</div>';
      }
    } else {
      echo '<div class="no-results">Please enter a search term.</div>';
    }
    ?>
  </div>
</body>
</html>