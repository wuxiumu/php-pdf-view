<?php
session_start();
$config = require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === $config['username'] && $_POST['password'] === $config['password']) {
        $_SESSION['logged_in'] = true;
        $_SESSION['viewed'] = []; // 初始化今日阅读记录
        header('Location: index.php');
        exit;
    } else {
        $error = "用户名或密码错误";
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>登录</title>
<style>
  body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 40px 20px;
    background: #f2f2f2;
    display: flex;
    justify-content: center;
  }
  .login-container {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
  }
  h2 {
    margin-top: 0;
    text-align: center;
  }
  input {
    width: 100%;
    padding: 10px;
    margin: 8px 0 16px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  button {
    width: 100%;
    background: #007BFF;
    color: white;
    border: none;
    padding: 12px;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
  }
  button:hover {
    background: #0056b3;
  }
  .error {
    color: red;
    text-align: center;
  }
</style>
</head>
<body>
  <div class="login-container">
    <h2>登录系统</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
      <input name="username" placeholder="用户名">
      <input type="password" name="password" placeholder="密码">
      <button type="submit">登录</button>
    </form>
  </div>
</body>
</html>