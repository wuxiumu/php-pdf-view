<?php
require 'auth.php';
$config = require 'config.php';

// 判断pdf_data 内容是否为空
$pdf_data = file_get_contents($config['pdf_data']);
if ($pdf_data) {
    $files = json_decode($pdf_data, true); // 获取pdf文件列表
}else{
    $files = glob($config['pdf_dir'] . '/*.pdf'); // 获取pdf文件列表
}


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
$perPage = 20;
$total = count($filtered);
$totalPages = ceil($total / $perPage);
$display = array_slice($filtered, ($page - 1) * $perPage, $perPage);
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title><?= $config['title'] ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { font-family: sans-serif; padding: 20px; margin: 0; background: #f9f9f9; }
  h1 { font-size: 22px; margin-bottom: 10px; }
  table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
  th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
  tr {
    background: rgb(255, 87, 87);
    border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    margin-bottom: 10px;
  }
  tr:nth-child(even) {
    background: #f1f1f1;
  }
  form input[type="text"] { padding: 8px; width: 70%; max-width: 300px; }
  form button { padding: 8px 12px; }
  .pagination { margin-top: 20px; text-align: center; }
  .pagination a, .pagination strong {
    display: inline-block;
    padding: 5px 10px;
    margin: 0 3px;
    border-radius: 4px;
    text-decoration: none;
    border: 1px solid #007BFF;
    color: #007BFF;
  }
  .pagination strong {
    background: #007BFF;
    color: white;
  }
  @media (max-width: 600px) {
    table, tbody, tr, td, th {
      display: block;
      width: 100%;
    }
    tr { margin-bottom: 15px; }
    td::before, th::before {
      content: none;
    }
    td[data-label="操作"] {
      display: flex;
      flex-direction: row;
      gap: 5px;
      flex-wrap: wrap;
    }
  }
  body.dark-mode {
    background: #1e1e1e;
    color: #ddd;
  }
  body.dark-mode table {
    background: #2a2a2a;
  }
  body.dark-mode th, body.dark-mode td {
    border-color: #444;
    color: #ddd;
  }
  body.dark-mode tr {
    background: #2a2a2a;
  }
  body.dark-mode tr:nth-child(even) {
    background: #242424;
  }
  body.dark-mode .pagination a,
  body.dark-mode .pagination strong {
    border-color: #555;
  }
  body.dark-mode .pagination strong {
    background: #555;
  }
  .read-btn, .buy-btn {
    display: inline-block;
    padding: 6px 10px;
    margin: 2px 5px 2px 0;
    text-decoration: none;
    color: white;
    border-radius: 4px;
    font-size: 14px;
  }
  .read-btn {
    background: #007BFF;
  }
  .buy-btn {
    background: #28a745;
  }
    .container {
      max-width: 800px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
</style>
</head>
<body>
      <div class="container">
<div style="display: flex; justify-content: space-between; align-items: center;">
  <h1><?= $config['title'] ?></h1>
  <button onclick="toggleDark()" title="切换夜间模式">🌙</button>
</div>
<?php if (is_logged_in()): ?>
    <p>欢迎，已登录！<a href="logout.php">退出</a></p>
<?php else: ?>
    <p><a href="login.php">登录</a></p>
<?php endif; ?>

<form method="get">
    <input name="search" value="<?= htmlspecialchars($search) ?>" placeholder="搜索PDF名称" type="text">
    <button type="submit">搜索</button>
</form>

<table>

<?php foreach ($display as $file): ?>
<tr>
    <td><?= htmlspecialchars(preg_replace('/\.pdf$/i', '', $file)) ?></td>
    <td>
        <?php if (is_logged_in()): ?>
            <a class="read-btn" href="view.php?file=<?= urlencode($file) ?>">📖 阅读</a>
            <a class="buy-btn" href="pay.php?file=<?= urlencode($file) ?>">💰 购买</a>
        <?php else: ?>
            <a class="read-btn">登录后可阅读</a><a class="buy-btn" href="pay.php?file=<?= urlencode($file) ?>">💰 购买</a>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>

<div>
    共 <?= $total ?> 项 &nbsp;&nbsp;
    页码：
</div>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">« 上一页</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?= $i == $page ? "<strong>$i</strong>" : "<a href='?search=" . urlencode($search) . "&page=$i'>$i</a>" ?>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
        <a href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">下一页 »</a>
    <?php endif; ?>
    <form method="get" style="display: inline-block; margin-left: 10px;">
        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
        跳转到第 <input type="number" name="page" min="1" max="<?= $totalPages ?>" style="width: 60px;"> 页
        <button type="submit">Go</button>
    </form>
</div>
</div>
<script>
  function toggleDark() {
    document.body.classList.toggle('dark-mode');
  }
</script>
</body>
</html>