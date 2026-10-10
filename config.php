<?php
/**
 * PDF 限阅配置。口令请用环境变量，勿提交真实密码。
 * PDF_ADMIN_USER / PDF_ADMIN_PASS / PDF_USER1_PASS 等
 */
$adminPass = getenv('PDF_ADMIN_PASS') ?: '';
$user1Pass = getenv('PDF_USER1_PASS') ?: '';
return [
    'username' => getenv('PDF_ADMIN_USER') ?: 'admin',
    'password' => $adminPass,
    'users' => [
        'admin' => ['password' => $adminPass, 'is_admin' => true],
        'user1' => ['password' => $user1Pass, 'is_admin' => false],
    ],
    'pdf_dir' => getenv('PDF_DIR') ?: './pdf',
    'pdf_data' => getenv('PDF_DATA') ?: './data/pdf.json',
    'title' => 'PDF 阅览系统',
    'logo' => 'logo.png',
    'qrcode_weixin'=> 'https://archive.biliimg.com/bfs/archive/10a598d53fdc1bfc069879a4080673d679acf34e.jpg?config',
];
