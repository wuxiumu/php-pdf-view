<?php
$config = require 'config.php';
$file = basename($_GET['file'] ?? ''); // 获取文件名

$book_name = preg_replace('/\.pdf$/i', '', $file);

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>书籍购买页面</title>
    <meta name="referrer" content="no-referrer">
    <style>
        body {
            font-family: "PingFang SC", sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .buy-btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 1.2em;
            margin: 10px 0;
            cursor: pointer;
        }
        .buy-btn:first-child { background-color: #ff6600; } /* 橙色按钮 */
        .buy-btn:last-child { background-color: #0066cc; } /* 蓝色按钮 */
        .qrcode-box {
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 20px 0;
        }
        .qrcode-img {
            width: 150px;
            height: 150px;
            background: #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- 封面模块 -->
        <div class="section">
            <img src="https://archive.biliimg.com/bfs/archive/e1be40e4e02f130cd085904fa8903427c0647a7f.png" alt="书籍封面" style="width: 100%;  margin: 0 auto display: block;">
            <h1 style="text-align: center; color: #003366;"><?php echo ($book_name); ?></h1>
            <p style="text-align: center; color: #666;">知识有价，即刻获取</p>
        </div>

        <!-- 购买选项模块 -->
        <div class="section">
            <h2 style="color: #003366;">选择购买套餐</h2>
            <button class="buy-btn">单文件购买 ¥9.9</button>
            <button class="buy-btn">全部文件购买 ¥99（推荐）</button>
        </div>

        <!-- 购买须知模块 -->
        <div class="section">
            <h3>购买须知</h3>
            <div class="qrcode-box">
                <div class="qrcode-img"><img style="width: 100%; height: 100%;" src="<?php echo ($config['qrcode_weixin']);?>" alt="下载二维码"></div>
                <div>
                    <p>✅ 请添加管理员微信后购买</p>
                    <p>✅ 24小时内发送文件及下载链接</p>
                    <p style="color: red;">⚠️ 下载链接有效期15天</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>