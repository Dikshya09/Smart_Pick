<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ElectroShop - Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #000;
      --accent: #fff;
      --bg: #f7f7f7;
      --input-bg: #fff;
      --input-border: #ccc;
      --btn-bg: #000;
      --btn-hover-bg: #333;
      --radius: 8px;
      --error-color: red;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg);
      color: #333;
      padding-top: 70px;
    }
    a {
      text-decoration: none;
      color: var(--primary);
    }
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: var(--primary);
      color: var(--accent);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      z-index: 1000;
    }
    .navbar .logo {
      font-size: 1.5rem;
      font-weight: bold;
      cursor: pointer;
    }

    .login-container {
      width: 100%;
      max-width: 500px;
      margin: 2rem auto;
      background: #fff;
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #000;
    }
    .form-group {
      margin-bottom: 1.5rem;
    }
    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
    }
    .error-message {
      color: var(--error-color);
      font-size: 0.9rem;
      margin-bottom: 0.25rem;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid var(--input-border);
      border-radius: var(--radius);
      background: var(--input-bg);
    }
    input[type="submit"] {
      padding: 0.75rem 1.5rem;
      background: var(--btn-bg);
      color: #fff;
      border: none;
      border-radius: var(--radius);
      cursor: pointer;
      transition: background 0.3s;
      width: 100%;
    }
    input[type="submit"]:hover {
      background: var(--btn-hover-bg);
    }
    .forgot-pass, .register-link {
      display: block;
      text-align: center;
      margin-top: 1rem;
    }
  </style>
</head>
<body>
  <?php
  session_start();
  include("includes/db.php"); // make sure your DB connection is included

  $error = "";

  if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $pass = mysqli_real_escape_string($con, $_POST['pass']);

    $sel_customer = "SELECT * FROM customers WHERE customer_email='$email' AND customer_pass='$pass'";
    $run_customer = mysqli_query($con, $sel_customer);

    $check_customer = mysqli_num_rows($run_customer);

    if ($check_customer > 0) {
      $_SESSION['customer_email'] = $email;
      echo "<script>window.open('index.php','_self')</script>";
    } else {
      $error = "Invalid email or password.";
    }
  }
  ?>

  <header class="navbar">
    <div class="logo" onclick="location.href='index.php'">SmartPick</div>
  </header>

  <div class="login-container">
    <h2>Log In or Register</h2>
    <form method="post" action="" onsubmit="return validateForm()">
      <div class="form-group">
        <?php if ($error) echo '<div class="error-message">'.$error.'</div>'; ?>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" placeholder="Enter Your Email">
      </div>

      <div class="form-group">
        <label for="pass">Password:</label>
        <input type="password" id="pass" name="pass" placeholder="Enter Your Password">
      </div>

      <div class="form-group">
        <input type="submit" name="login" value="Login">
      </div>
    </form>

    <a class="forgot-pass" href="checkout.php?forgot_pass">Forgot Password?</a>
    <a class="register-link" href="customer_register.php">New? Register Here</a>
  </div>

  <script>
    function validateForm() {
      let email = document.getElementById("email").value;
      let pass = document.getElementById("pass").value;
      let valid = true;

      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert("Please enter a valid email address.");
        valid = false;
      } else if (pass.length < 6) {
        alert("Password must be at least 6 characters long.");
        valid = false;
      }
      return valid;
    }
  </script>
</body>
</html>
