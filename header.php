<!DOCTYPE html>
<?php
  include("functions/functions.php");
  //session_start();
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ElectroShop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  />
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
      padding-top: 70px; /* height of fixed navbar */
    }
    a { color: inherit; text-decoration: none; }
    ul { list-style: none; }

    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: #000;
      color: white;
      display: flex;
      align-items: center;
      padding: 0.75rem 2rem;
      z-index: 10;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .navbar .logo {
      font-size: 1.5rem; font-weight: bold;
      margin-right: 2rem; cursor: pointer;
    }
    .navbar nav {
      display: flex; gap: 1.5rem; flex: 1;
    }
    .navbar nav a {
      font-weight: 600; padding: 0.25rem 0.5rem;
      color: white;
    }
    .navbar nav a:hover, .navbar nav a.active {
      border-bottom: 2px solid white;
    }
    .search-container {
      position: relative; margin-right: 2rem;
    }
    .search-container input {
      padding: 0.5rem 2.5rem 0.5rem 1rem;
      border: 1px solid white;
      border-radius: var(--radius);
      width: 200px;
      background: white;
      color: black;
    }
    .search-container input::placeholder {
      color: black;
    }
    .search-container .fa-search {
      position: absolute; right: 0.75rem; top: 50%;
      transform: translateY(-50%);
      color: black;
      pointer-events: none;
    }
    .icon-group {
      display: flex; align-items: center; gap: 1rem;
    }
    .cart-icon {
      font-size: 1.25rem; color: white; cursor: pointer;
    }
    .profile, .auth-links {
      position: relative;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .profile img {
      width: 32px; height: 32px; border-radius: 50%; border: 1px solid white; cursor: pointer; object-fit: cover;
    }
    .dropdown {
      position: absolute; top: 100%; right: 0;
      background: var(--bg);
      color: black;
      border: 1px solid #ccc;
      border-radius: var(--radius);
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      width: 180px; display: none; margin-top: 0.5rem;
    }
    .dropdown.active { display: block; }
    .user-info {
      display: flex; align-items: center;
      padding: 0.75rem; border-bottom: 1px solid #eee;
    }
    .user-info img {
      width: 32px; height: 32px; border-radius: 50%; margin-right: 0.5rem;
    }
    .dropdown-links a {
      display: flex; align-items: center;
      padding: 0.6rem 0.75rem; transition: background 0.2s; color: black;
    }
    .dropdown-links a:hover { background: #f4f4f4; }
    .dropdown-links i {
      width: 20px; text-align: center; margin-right: 0.5rem;
    }

  </style>

</head>
<body>
  <header class="navbar">
    <div class="logo" onclick="location.href='index.php'">SmartPick</div>
    <nav>
      <a href="index.php" class="active">Home</a>
      <a href="all_products.php">Products</a>
    </nav>
    <div class="search-container">
      <form action="search.php" method="get">
    <input type="text" name="search" placeholder="Search products..." required>
    <button type="submit"><i class="fa fa-search"></i></button>
</form>
    </div>
    <div class="icon-group">
      <i class="fas fa-shopping-cart cart-icon" onclick="location.href='cart.php'"></i>
      <?php if (isset($_SESSION['customer_email'])): ?>
        <div class="profile" onclick="toggleDropdown(event)">
        <img src="<?php echo $customer_image; ?>" alt="Profile Image" width="25" />
          <div class="dropdown" id="dropdownMenu">
            <div class="user-info">
              <strong><?= isset($_SESSION['customer_name']) ? htmlspecialchars($_SESSION['customer_name']) : 'User' ?></strong>
            </div>
            <div class="dropdown-links">
              <a href="my_account.php"><i class="fas fa-user"></i>Profile</a>
              <a href="edit_account.php"><i class="fas fa-cog"></i>Settings</a>
              <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="auth-links">
          <a href="customer_login.php">Login</a>
          <a href="customer_register.php">Register</a>
        </div>
      <?php endif; ?>
    </div>
  </header>

  
  <footer></footer>
  <script>
    function toggleDropdown(e) {
      e.stopPropagation();
      document.getElementById('dropdownMenu').classList.toggle('active');
    }
    document.addEventListener('click', e => {
      const dd = document.getElementById('dropdownMenu');
      if (dd && !e.target.closest('.profile')) {
        dd.classList.remove('active');
      }
    });
  </script>
</body>
</html> 