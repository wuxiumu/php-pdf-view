<?php
require 'auth.php';
check_login();

$config = require 'config.php';
$file = basename($_GET['file'] ?? '');

// 判断pdf_data 内容是否为空
$pdf_data = file_get_contents($config['pdf_data']);
if ($pdf_data) {
     $filepath = $config['pdf_dir'] . '/' . $file;
}else{
    $filepath = $config['pdf_dir'] . '/' . $file;
}

if (!preg_match('/\.pdf$/i', $file) || !file_exists($filepath)) {
    $img = '<img style="width: 400px; max-width: 100%; height: auto; margin: 20px auto; display: block;" src="'. $config['qrcode_weixin']. '" alt="下载二维码"  referrerpolicy="no-referrer">';
    die("<h1 style='text-align: center; margin-top: 100px;'>文件丢失，请联系管理员</h1>".$img);
}

// var_dump($file, $filepath);
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

$logFile = __DIR__ . '/data/log.json';
$log = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

// 判断 ./pdf/ 目录下是否有该文件，如果没有，则复制到
if (!file_exists(__DIR__ . '/pdf/' . $file)) {
    copy($filepath, __DIR__ . '/pdf/' . $file);
}

// 记录日志
$log[] = [
    'time' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR'],
    'file' => $file,
];
file_put_contents($logFile, json_encode($log));

?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($file) ?></title>
    <style>
        body { margin: 0; font-family: sans-serif; background: #f9f9f9; }
        header { padding: 10px 20px; background: #333; color: white; }
        iframe, embed {
            width: 100%;
            height: 100vh;
            border: none;
        }
    </style>
</head>
<body>
<header>
    <a href="index.php" style="color:#ccc;">← 返回目录</a> | 正在阅读：<?= htmlspecialchars($file) ?>
</header>
<embed src="./pdf/<?= $file ?>" type="application/pdf">
</body>
</html>