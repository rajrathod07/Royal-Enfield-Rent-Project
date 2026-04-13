<?php
session_start();
include '../includes/db.php';

// Login handler
if (isset($_POST['login'])) {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];

        if (isset($_GET['redirect'])) {
            header("Location: ../" . $_GET['redirect']);
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $login_error = "Invalid login credentials!";
    }
}

// Register handler
if (isset($_POST['register'])) {
    $name = $_POST['reg_name'];
    $email = $_POST['reg_email'];
    $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $register_error = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            // Auto-login after register
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_name'] = $name;

            if (isset($_GET['redirect'])) {
                header("Location: ../" . $_GET['redirect']);
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $register_error = "Error registering user!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Royal Enfield - Login/Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
            background: #000;
            position: relative;
            overflow: hidden;
        }
        .container {
            background: rgba(28, 28, 30, 0.95);
            padding: 40px;
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.7);
            animation: fadeIn 0.6s ease-in-out;
            margin: auto;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 150px;
            filter: drop-shadow(0 0 10px #e60000aa);
        }
        .tabs {
            display: flex;
            margin-bottom: 20px;
            border: 2px solid #141212ff;
            border-radius: 8px;
            overflow: hidden;
        }
        .tabs button {
            flex: 1;
            padding: 12px;
            background: #2c2c2e;
            border: none;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s;
            font-weight: 600;
        }
        .tabs button.active {
            background: #e60000;
        }
        .form { display: none; }
        .form.active { display: block; }
        form {
            display: flex;
            flex-direction: column;
        }
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        .input-group input {
            width: 100%;
            padding: 12px 40px;
            border-radius: 8px;
            border: 1px solid #444;
            background: #1c1c1e;
            color: #fff;
            font-size: 15px;
        }
        .input-group i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #aaa;
        }
        button[type="submit"] {
            padding: 12px;
            background: #e60000;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
        }
        button[type="submit"]:hover {
            background: #ff1a1a;
        }
        h3 {
            text-align: center;
            font-size: 24px;
            color: #fff;
            margin-bottom: 20px;
        }
        .message {
            margin-top: 10px;
            padding: 10px;
            text-align: center;
            background: #2f2f31;
            color: #ff4d4d;
            border-radius: 8px;
        }
        .success {
            color: #00ff99;
        }
        @media (max-width: 480px) {
            .container {
                padding: 25px;
            }
        }
        .outer-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
    </style>

    <script>
        function showForm(formId) {
            document.querySelectorAll('.form').forEach(f => f.classList.remove('active'));
            document.querySelectorAll('.tabs button').forEach(b => b.classList.remove('active'));
            document.getElementById(formId).classList.add('active');
            document.querySelector('[data-target="'+formId+'"]').classList.add('active');
        }
        window.onload = () => {
            showForm('loginForm');
        }
    </script>
</head>
<body>

<div class="outer-wrapper">
    <div class="container">
        <div class="logo">
            <a href="../index.php">
                <img src="logo.svg" alt="Royal Enfield Logo">
            </a>
        </div>

        <div class="tabs">
            <button class="active" data-target="loginForm" onclick="showForm('loginForm')">Login</button>
            <button data-target="registerForm" onclick="showForm('registerForm')">Register</button>
        </div>

        <!-- Login Form -->
        <div class="form" id="loginForm">
            <h3><i class="fas fa-sign-in-alt"></i> Login</h3>
            <?php if (isset($login_error)) echo "<div class='message'>$login_error</div>"; ?>
            <form method="post">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="login_email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="login_password" placeholder="Password" required>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
        </div>

        <!-- Register Form -->
        <div class="form" id="registerForm">
            <h3><i class="fas fa-user-plus"></i> Register</h3>
            <?php 
                if (isset($register_error)) echo "<div class='message'>$register_error</div>"; 
                if (isset($register_success)) echo "<div class='message success'>$register_success</div>"; 
            ?>
            <form method="post">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="reg_name" placeholder="Full Name" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="reg_email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="reg_password" placeholder="Password" required>
                </div>
                <button type="submit" name="register">Register</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
