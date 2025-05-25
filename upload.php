<?php
require 'auth.php';
check_login();
if (!$_SESSION['is_admin']) die("无权限");

$config = require 'config.php';
$targetDir = $config['pdf_dir'];

if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== 0) {
    die("上传失败");
}

$name = basename($_FILES['pdf']['name']);
if (!preg_match('/\.pdf$/i', $name)) {
    die("只能上传 PDF 文件");
}

move_uploaded_file($_FILES['pdf']['tmp_name'], "$targetDir/$name");
header("Location: index.php");