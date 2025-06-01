<!DOCTYPE html>
<?php
  session_start();
  include("functions/functions.php");
  include("includes/db.php");
?>

<html>
<head>
  <title>Register - SmartPick</title>
  <style>
    :root {
      --primary: #000;
      --accent: #fff;
      --bg: #f9f9f9;
      --input-bg: #fff;
      --input-border: #ccc;
      --btn-bg: #000;
      --btn-hover-bg: #333;
      --radius: 10px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg);
      color: #333;
      padding-top: 70px;
    }

    .navbar {
      position: fixed;
      top: 0;
      width: 100%;
      background: var(--primary);
      color: var(--accent);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 1000;
    }

    .navbar .logo {
      font-size: 1.5rem;
      font-weight: bold;
      cursor: pointer;
    }

    .register-container {
      max-width: 600px;
      margin: 3rem auto;
      background: #fff;
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: var(--primary);
    }

    form label {
      display: block;
      margin: 1rem 0 0.25rem;
    }

    input[type="text"], input[type="password"], textarea, select, input[type="file"] {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid var(--input-border);
      border-radius: var(--radius);
      background: var(--input-bg);
    }

    .error {
      color: red;
      font-size: 0.9rem;
      margin-top: 0.25rem;
      display: none;
    }

    input[type="submit"] {
      display: block;
      width: 100%;
      padding: 1rem;
      margin-top: 2rem;
      background: var(--btn-bg);
      color: white;
      border: none;
      border-radius: var(--radius);
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s;
    }

    input[type="submit"]:hover {
      background: var(--btn-hover-bg);
    }

    .login-link {
      text-align: center;
      margin-top: 1rem;
    }

    .login-link a {
      color: var(--primary);
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <header class="navbar">
    <div class="logo" onclick="location.href='index.php'">SmartPick</div>
  </header>

  <div class="register-container">
    <h2>Create an Account</h2>
    <form id="registerForm" action="customer_register.php" method="post" enctype="multipart/form-data" novalidate>
      <label for="c_name">Name</label>
      <input type="text" name="c_name" id="c_name">
      <div class="error" id="error_name">Name is required.</div>

      <label for="c_email">Email</label>
      <input type="text" name="c_email" id="c_email">
      <div class="error" id="error_email">Valid email is required.</div>

      <label for="c_pass">Password</label>
      <input type="password" name="c_pass" id="c_pass">
      <div class="error" id="error_pass">Password must be at least 6 characters.</div>

      <label for="c_image">Image</label>
      <input type="file" name="c_image" id="c_image">
      <div class="error" id="error_image">Please upload an image.</div>

      <label for="c_country">Country</label>
      <select name="c_country" id="c_country">
        <option value="">Select a Country</option>
        <option>Afghanistan</option>
        <option>Australia</option>
        <option>China</option>
        <option>Nepal</option>
        <option>Japan</option>
        <option>Pakistan</option>
        <option>Russia</option>
        <option>Israel</option>
        <option>United Arab Emirates</option>
        <option>United States</option>
        <option>United Kingdom</option>
      </select>
      <div class="error" id="error_country">Please select a country.</div>

      <label for="c_city">City</label>
      <input type="text" name="c_city" id="c_city">

      <label for="c_contact">Contact Number</label>
      <input type="text" name="c_contact" id="c_contact">
      <div class="error" id="error_contact">Contact number is required.</div>

      <label for="c_address">Address</label>
      <textarea name="c_address" id="c_address" rows="3"></textarea>

      <input type="submit" name="register" value="Create Account">
    </form>
    <div class="login-link">
      Already have an account? <a href="customer_login.php">Login here</a>
    </div>
  </div>

  <script>
    document.getElementById("registerForm").addEventListener("submit", function(e) {
      let valid = true;

      const email = document.getElementById("c_email").value.trim();
      const name = document.getElementById("c_name").value.trim();
      const pass = document.getElementById("c_pass").value.trim();
      const image = document.getElementById("c_image").value;
      const country = document.getElementById("c_country").value;
      const contact = document.getElementById("c_contact").value.trim();

      document.querySelectorAll('.error').forEach(el => el.style.display = 'none');

      if (!name) {
        document.getElementById("error_name").style.display = 'block';
        valid = false;
      }

      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        document.getElementById("error_email").style.display = 'block';
        valid = false;
      }

      if (pass.length < 6) {
        document.getElementById("error_pass").style.display = 'block';
        valid = false;
      }

      if (!image) {
        document.getElementById("error_image").style.display = 'block';
        valid = false;
      }

      if (!country) {
        document.getElementById("error_country").style.display = 'block';
        valid = false;
      }

      if (!contact) {
        document.getElementById("error_contact").style.display = 'block';
        valid = false;
      }

      if (!valid) e.preventDefault();
    });
  </script>
</body>
</html>
