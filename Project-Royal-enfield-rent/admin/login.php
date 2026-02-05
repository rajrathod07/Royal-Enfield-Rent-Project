<?php
session_start();
include 'includes/db.php'; // Your DB connection file

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare & execute query
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Login - Royal Enfield</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      background-color: #121212;
      color: #f0f0f0;
      font-family: 'Inter', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .login-box {
      background-color: #1a1a1a;
      border: 1px solid #2a2a2a;
      padding: 40px 32px;
      border-radius: 10px;
      max-width: 400px;
      width: 100%;
      box-sizing: border-box;
      text-align: center;
    }
    .login-box img {
      height: 40px;
      margin-bottom: 20px;
    }
    .login-box h2 {
      margin: 0 0 25px;
      font-weight: 600;
      font-size: 22px;
    }
    input {
      width: 90%;
      padding: 12px;
      margin-bottom: 16px;
      background-color: #262626;
      border: 1px solid #333;
      border-radius: 6px;
      color: white;
      font-size: 14px;
    }
    input:focus {
      border-color: #f5b921;
      outline: none;
    }
    button {
      width: 100%;
      padding: 12px;
      background-color: #f5b921;
      color: #121212;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      font-size: 15px;
      cursor: pointer;
    }
    button:hover {
      background-color: #e0a611;
    }
    .error {
      color: #ff4d4d;
      margin-bottom: 20px;
    }
    @media (max-width: 500px) {
      .login-box {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

<div class="login-box">
  <img src="../assets/images/logo.svg" alt="Royal Enfield Logo" />
  <h2>Admin Login</h2>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST">
    <input type="text" name="username" placeholder="Username" required autocomplete="off" />
    <input type="password" name="password" placeholder="Password" required autocomplete="off" />
    <button type="submit">Login</button>
  </form>
</div>

</body>
</html>
