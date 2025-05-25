<?php
return [
    'username' => '51admin',
    'password' => '123456',
    'users' => [
        'admin' => ['password' => '123456', 'is_admin' => true],
        'user1' => ['password' => 'abc123', 'is_admin' => false],
    ],
    'pdf_dir' =>   './pdf',
    'pdf_data' =>   './data/pdf.json',//   这里是pdf.json文件的路径，可以根据自己的实际情况修改。 如果是空，读pdf_dir目录下的所有pdf文件。
    'title' => 'PDF 阅览系统',
    'logo' => 'logo.png',
    'qrcode_weixin'=> 'https://archive.biliimg.com/bfs/archive/10a598d53fdc1bfc069879a4080673d679acf34e.jpg?config',
];