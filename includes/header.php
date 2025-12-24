<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BONUS-APP | 評価管理システム</title>
    <link rel="stylesheet" href="/bonus-app/style.css?v=<?= time() ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="main-layout">
        <nav class="sidebar">
            <div class="sidebar-logo">BONUS-APP</div>
            <a href="/bonus-app/index.php" class="<?= ($current_page == 'index') ? 'active' : '' ?>">ホーム</a>
            <a href="/bonus-app/dashboard.php" class="<?= ($current_page == 'dashboard') ? 'active' : '' ?>">ダッシュボード</a>
            
            <a href="/bonus-app/admin/staff_list.php" class="<?= ($current_page == 'staff_list') ? 'active' : '' ?>">従業員一覧</a>
            <a href="/bonus-app/admin/admin.php" class="<?= ($current_page == 'admin') ? 'active' : '' ?>">管理設定</a>
        </nav>
        <main class="main-content">