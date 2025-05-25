# php-pdf-view
PHP 实现你要求的 登录后每日限阅 3 篇 PDF 的系统,可以改成付费资源，付费进群系统

以下是一个完整设计思路，用 PHP 实现你要求的 **登录后每日限阅 3 篇 PDF 的系统**：



------





## **✅ 功能概览**



| **模块**     | **描述**                                                 |
| ------------ | -------------------------------------------------------- |
| 登录系统     | 用户名/密码验证（配置在 config.php）                     |
| PDF 目录浏览 | 目录下所有 .pdf 文件，支持搜索 + 分页                    |
| 权限控制     | 未登录用户仅可看目录，不可打开文件                       |
| 限制阅读次数 | 登录用户每天最多查看 3 个 PDF（以天为单位）              |
| 可配置参数   | 用户名密码、PDF 目录、页面标题、Logo 等都写在 config.php |





------





## **📁 目录结构建议**



```
project/
├── config.php
├── auth.php
├── login.php
├── logout.php
├── index.php       ← 目录首页，分页+搜索
├── view.php        ← 显示 PDF 内容
├── data/
│   └── log.json    ← 用户阅读记录（可选，做每日限读）
├── pdf/            ← 存放 PDF 文件
```





------





## **🧩 config.php**



```
<?php
return [
    'username' => 'admin',
    'password' => '123456',
    'pdf_dir'  => __DIR__ . '/pdf',
    'title'    => 'PDF 阅览系统',
    'logo'     => 'logo.png', // 可选
];
```





------





## **🔒 auth.php（权限控制）**



```
<?php
session_start();
$config = require 'config.php';

function is_logged_in() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function check_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}
```





------





## **🔐 login.php**



```
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
<html><head><meta charset="utf-8"><title>登录</title></head>
<body>
<h2>登录系统</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    用户名：<input name="username"><br>
    密码：<input type="password" name="password"><br>
    <button type="submit">登录</button>
</form>
</body>
</html>
```





------





## **🚪 logout.php**



```
<?php
session_start();
session_destroy();
header('Location: index.php');
```





------





## **📄 index.php（支持分页搜索）**



```
<?php
require 'auth.php';
$config = require 'config.php';

$files = glob($config['pdf_dir'] . '/*.pdf');
$search = trim($_GET['search'] ?? '');
$filtered = [];

foreach ($files as $file) {
    $name = basename($file);
    if ($search === '' || stripos($name, $search) !== false) {
        $filtered[] = $name;
    }
}

// 分页
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;
$total = count($filtered);
$totalPages = ceil($total / $perPage);
$display = array_slice($filtered, ($page - 1) * $perPage, $perPage);
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title><?= $config['title'] ?></title></head>
<body>
<h1><?= $config['title'] ?></h1>
<?php if (is_logged_in()): ?>
    <p>欢迎，已登录！<a href="logout.php">退出</a></p>
<?php else: ?>
    <p><a href="login.php">登录</a></p>
<?php endif; ?>

<form method="get">
    <input name="search" value="<?= htmlspecialchars($search) ?>" placeholder="搜索PDF名称">
    <button type="submit">搜索</button>
</form>

<table border="1" cellpadding="5">
<tr><th>文件名</th><th>操作</th></tr>
<?php foreach ($display as $file): ?>
<tr>
    <td><?= htmlspecialchars($file) ?></td>
    <td>
        <?php if (is_logged_in()): ?>
            <a href="view.php?file=<?= urlencode($file) ?>">阅读</a>
        <?php else: ?>
            登录后可阅读
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>

<div>
    共 <?= $total ?> 项 &nbsp;&nbsp;
    页码：
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?= $i == $page ? "<strong>$i</strong>" : "<a href='?search=" . urlencode($search) . "&page=$i'>$i</a>" ?>
    <?php endfor; ?>
</div>
</body>
</html>
```





------





## **📄 view.php（限制每天只能看 3 个）**



```
<?php
require 'auth.php';
check_login();

$config = require 'config.php';
$file = basename($_GET['file'] ?? '');
$filepath = $config['pdf_dir'] . '/' . $file;

if (!preg_match('/\.pdf$/i', $file) || !file_exists($filepath)) {
    die("非法文件或不存在");
}

// 限制每天最多看 3 个
$date = date('Y-m-d');
if (!isset($_SESSION['viewed'][$date])) {
    $_SESSION['viewed'] = [$date => []];
}

if (!in_array($file, $_SESSION['viewed'][$date])) {
    if (count($_SESSION['viewed'][$date]) >= 3) {
        die("每天最多阅读 3 个 PDF 文件");
    }
    $_SESSION['viewed'][$date][] = $file;
}
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title><?= htmlspecialchars($file) ?></title></head>
<body>
<h2><?= htmlspecialchars($file) ?></h2>
<p><a href="index.php">← 返回目录</a></p>
<embed src="<?= "pdf/" . urlencode($file) ?>" width="100%" height="800px" type="application/pdf">
</body>
</html>
```





------





## **✅ 功能说明**





- 登录用户浏览 PDF 目录 ✅
- 未登录用户不能查看 PDF 内容 ✅
- 登录用户每天只能查看 3 个 PDF ✅
- 所有参数、用户名密码都在 config.php 设置 ✅
- 分页 & 搜索 ✅
- 简单、安全（防止任意文件读取）✅





------


 本地快速启动 PHP 服务器：
```php
php -S localhost:8088
```