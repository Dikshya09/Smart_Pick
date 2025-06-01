<?php
session_start();

$host = 'localhost';
$dbname = 'ecom';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
    if ($productId <= 0) die("Invalid product ID.");

    if (!isset($_SESSION['browsing_history'])) {
        $_SESSION['browsing_history'] = [];
    }
    if (!in_array($productId, $_SESSION['browsing_history'])) {
        $_SESSION['browsing_history'][] = $productId;
    }

    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) die("Product not found.");

    $stmtAll = $pdo->query("SELECT * FROM products");
    $allProducts = $stmtAll->fetchAll(PDO::FETCH_ASSOC);

    function vectorize($title, $desc, $keywords, $brand) {
    $text = strtolower($title . ' ' . $desc . ' ' . $keywords . ' ' . $brand);
    $words = preg_split('/\W+/', $text, -1, PREG_SPLIT_NO_EMPTY);
    $vector = [];
    foreach ($words as $word) {
        $vector[$word] = ($vector[$word] ?? 0) + 1;
    }
    return $vector;
}

function cosineSimilarity($vec1, $vec2) {
    $all_keys = array_unique(array_merge(array_keys($vec1), array_keys($vec2)));
    $dot = $mag1 = $mag2 = 0;
    foreach ($all_keys as $k) {
        $a = $vec1[$k] ?? 0;
        $b = $vec2[$k] ?? 0;
        $dot += $a * $b;
        $mag1 += $a * $a;
        $mag2 += $b * $b;
    }
    return ($mag1 && $mag2) ? $dot / (sqrt($mag1) * sqrt($mag2)) : 0;
}

function getCollaborativeScore($productA, $productB, $pdo) {
    // Example collaborative score using co-purchase counts (you can replace this with your actual logic)
    $stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM cart_pairs WHERE (p1 = :a AND p2 = :b) OR (p1 = :b AND p2 = :a)");
    $stmt->execute(['a' => $productA, 'b' => $productB]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return min($row['cnt'], 10) / 10; // Normalize to [0, 1]
}

$targetVec = vectorize($product['product_title'], $product['product_desc'], $product['product_keywords'], $product['product_brand']);
$finalScores = [];

foreach ($allProducts as $other) {
    if ($other['product_id'] == $productId || $other['product_stock'] <= 0) continue;

    $vec = vectorize($other['product_title'], $other['product_desc'], $other['product_keywords'], $other['product_brand']);
    $simContent = cosineSimilarity($targetVec, $vec);
    $simCollaborative = getCollaborativeScore($productId, $other['product_id'], $pdo);

    // Weighted hybrid score (70% content-based, 30% collaborative)
    $hybridScore = 0.7 * $simContent + 0.3 * $simCollaborative;
    $finalScores[$other['product_id']] = $hybridScore;
}

arsort($finalScores);
$topProductIds = array_slice(array_keys($finalScores), 0, 5);

    $categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

    $historyProducts = [];
    if (!empty($_SESSION['browsing_history'])) {
        $ids = array_filter($_SESSION['browsing_history'], fn($id) => $id != $productId);
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmtHistory = $pdo->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
            $stmtHistory->execute(array_values($ids));
            $historyProducts = $stmtHistory->fetchAll(PDO::FETCH_ASSOC);
        }
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle Add to Cart functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    include_once("functions/functions.php");
    $product_id = (int)$_POST['product_id'];
    $qty = (int)$_POST['qty'];
    $ip = getIp();

    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    // Check if product is already in the cart
    $check = $conn->prepare("SELECT * FROM cart WHERE p_id = ? AND ip_add = ?");
    $check->bind_param("is", $product_id, $ip);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Product is already in the cart.')</script>";
    } else {
        // Insert product into the cart if not already in the cart
        $insert = $conn->prepare("INSERT INTO cart (p_id, qty, ip_add) VALUES (?, ?, ?)");
        $insert->bind_param("iis", $product_id, $qty, $ip);
        if ($insert->execute()) {
            echo "<script>alert('Product added to cart.'); window.location.href = 'cart.php';</script>";
            exit;
        } else {
            echo "<script>alert('Failed to add product to cart.');</script>";
        }
    }
    $conn->close();
}
?>
<?php
// Filter top recommendations to exclude out-of-stock products
$topProductIds = array_filter($topProductIds, function($id) use ($allProducts) {
    foreach ($allProducts as $product) {
        if ($product['product_id'] == $id && $product['product_stock'] > 0) {
            return true;
        }
    }
    return false;
});

// Filter browsing history to exclude out-of-stock products
$historyProducts = array_filter($historyProducts, function($product) {
    return $product['product_stock'] > 0;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['product_title']) ?> | SmartPick</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { margin: 0; font-family: sans-serif; background: #f7f7f7; color: black; }
    .category-bar {
      background: #fff; display: flex; justify-content: center; gap: 1rem;
      flex-wrap: wrap; padding: 1rem; border-bottom: 1px solid #ddd;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .category-bar a {
      padding: 12px 20px; border-radius: 20px; text-decoration: none;
      background:rgb(0, 0, 0); color: white; font-weight: 500;
      transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .category-bar a:hover { background:rgb(5, 5, 5); transform: scale(1.05); }
    .product-detail { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; padding: 2rem; }
    .product-image img { width: 100%; height: auto; object-fit: contain; }
    .product-info {
      overflow-y: auto; max-height: 80vh; padding: 1rem;
      background: white; border-radius: 8px; border: 1px solid #ddd; color: black;
    }
    .product-info h1 { margin-bottom: 1rem; color: #0f1111; }
    .product-info p { margin-top: 1rem; }
    .product-info .price { font-size: 1.25rem; margin: 0.5rem 0; }
    .product-rating { margin-top: 1rem; color: #0f1111; }
    .add-to-cart {
      color:rgb(251, 253, 253);
      margin-top: 1rem; padding: 0.75rem 1.5rem;
      background-color:rgb(9, 9, 9); border: 1px solid #0f1111;;
      border-radius: 4px; font-weight: bold; cursor: pointer;
      transition: background-color 0.3s; 
    }
    .add-to-cart:hover { background-color: #0f1111; }
    .product-description { margin-top: 2rem; }

    .recommended-products {
      margin: 4rem; padding: 2rem; background: #fff;
      border-radius: 8px; border: 1px solid #ddd; color: #0f1111;
    }
    .recommended-products h2 { margin-bottom: 1rem; }
    .recommended-products ul {
      display: flex; gap: 1.8rem; list-style: none;
      padding: 0; margin: 0;
    }
    .recommended-products li {
      background: white; padding: 1rem; border-radius: 6px;
      width: 240px; text-align: center; border: 1px solid #ccc;
      transition: transform 0.2s;
    }
    .recommended-products li:hover { transform: translateY(-5px); }
    .recommended-products img { width: 100%; height: 120px; object-fit: contain; margin-bottom: 0.5rem; }
    .recommended-products div:nth-child(2) { color:rgb(2, 2, 2); font-weight: bold; margin-bottom: 0.25rem; }
    .recommended-products div:nth-child(3) { font-size: 0.95rem; color: #000; }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="category-bar">
    <?php foreach ($categories as $cat): ?>
      <a href="index.php?cat=<?= $cat['cat_id'] ?>"> <?= htmlspecialchars($cat['cat_title']) ?> </a>
    <?php endforeach; ?>
  </div>

  <main class="product-detail">
    <div class="product-image">
      <img src="admin_area/product_images/<?= $product['product_image'] ?>" alt="<?= htmlspecialchars($product['product_title']) ?>">
    </div>
    <div class="product-info">
      <h1><?= htmlspecialchars($product['product_title']) ?></h1>
      <p class="price">Rs <?= number_format($product['product_price']) ?></p>
      <div class="product-rating"><span>★★★★☆</span> <span>(4.5/5, 120 Reviews)</span></div>
      
      <form method="post">
        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
        <input type="hidden" name="qty" value="1">
        <button class="add-to-cart" type="submit">Add to Cart</button>
      </form>
      
      <div class="product-description">
        <h3>Description</h3>
        <p><?= htmlspecialchars($product['product_desc']) ?></p>
      </div>
    </div>
  </main>

  <div class="recommended-products">
    <h2>Recommended Products:</h2>
    <ul>
      <?php foreach ($topProductIds as $id): ?>
          <?php foreach ($allProducts as $rec): ?>
              <?php if ($rec['product_id'] == $id): ?>
                  <li>
                    <a href="product_detail.php?product_id=<?= $rec['product_id'] ?>">
                        <img src="admin_area/product_images/<?= $rec['product_image'] ?>" alt="<?= htmlspecialchars($rec['product_title']) ?>">
                        <div><?= htmlspecialchars($rec['product_title']) ?></div>
                        <div>Rs <?= number_format($rec['product_price']) ?></div>
                    </a>
                  </li>
              <?php endif; ?>
          <?php endforeach; ?>
      <?php endforeach; ?>
    </ul>
  </div> 
  
<div class="recommended-products">
    <h2>Products from Your Browsing History</h2>
    <ul>
        <?php foreach ($historyProducts as $rec): ?>
            <li>
                <a href="product_detail.php?product_id=<?= $rec['product_id'] ?>">
                    <img src="admin_area/product_images/<?= $rec['product_image'] ?>" alt="<?= htmlspecialchars($rec['product_title']) ?>">
                    <div><?= htmlspecialchars($rec['product_title']) ?></div>
                    <div>Rs <?= number_format($rec['product_price']) ?></div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
  </div>
</body>
</html>
</body>
</html>
