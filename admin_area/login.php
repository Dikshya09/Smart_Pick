<!DOCTYPE html>
<html>
<head>
	<title>Admin Login</title>
	<link rel="stylesheet" type="text/css" href="styles/login_style.css" media="all">
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
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
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

    .login {
        
      width: 100%;
      max-width: 400px;
      background: #fff;
      padding: 2rem;
      border-radius: var(--radius);
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .login h1 {
      text-align: left;
      margin-bottom: 1.5rem;
      color: #000;
    }
    .error-message {
      color: var(--error-color);
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid var(--input-border);
      border-radius: var(--radius);
      background: var(--input-bg);
      margin-bottom: 1.5rem;
    }
    button[type="submit"] {
      padding: 0.75rem 1.5rem;
      background: var(--btn-bg);
      color: #fff;
      border: none;
      border-radius: var(--radius);
      cursor: pointer;
      transition: background 0.3s;
      width: 100%;
    }
    button[type="submit"]:hover {
      background: var(--btn-hover-bg);
    }
  </style>
</head>
<body>
<header class="navbar">
  <div class="logo" onclick="location.href='index.php'">SmartPick</div>
</header>
<div class="login">
  <h1>Admin Login</h1>
  <form method="post" action="login.php">
    <?php if(isset($_POST['login']) && empty($_POST['email'])) { echo '<div class="error-message">Email is required.</div>'; } ?>
    <input type="text" name="email" placeholder="Enter Your Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" />
    <?php if(isset($_POST['login']) && empty($_POST['password'])) { echo '<div class="error-message">Password is required.</div>'; } ?>
    <input type="password" name="password" placeholder="Password" />
    <button type="submit" name="login">Login</button>
  </form>
</div>
</body>
</html>

<?php
session_start();

include("includes/db.php");
global $con;

if(isset($_POST['login']))
{
   $email=mysqli_real_escape_string($con,$_POST['email']);
   $pass=mysqli_real_escape_string($con,$_POST['password']);

  $sel_user="select * from admins where user_email='$email' and user_pass='$pass'";

  $run_user=mysqli_query($con,$sel_user);

  $check_user=mysqli_num_rows($run_user);

  if($check_user==0)
  {
  	echo "<script> alert('Invalid Email or Password !') </script>";
  }

 else
 {
 	$_SESSION['user_email']=$email;

 	echo "<script> window.open('index.php?logged_in=You Successfully logged in ..!','_self') </script>";
 }
}
?>