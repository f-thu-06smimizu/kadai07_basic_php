<?php
$current_page = 'index'; 
require_once('includes/db_config.php'); 

// 従業員一覧をデータベースから取得
$stmt = $pdo->query("SELECT * FROM staffs ORDER BY department ASC");
$staffs = $stmt->fetchAll();

include('includes/header.php'); 
?>

<div class="top-header">
    <h2>🏠 ホーム / 評価対象者選択</h2>
</div>

<div class="content-area">
    
    <div class="card">
        <div class="staff-header">
            <h3>評価対象者を選択</h3>
            <p style="color: #666; margin-bottom: 25px;">氏名をクリックして評価入力に進んでください。</p>
        </div>
        
        <div class="staff-grid">
            <?php foreach ($staffs as $staff): ?>
                <a href="form.php?id=<?= $staff['id'] ?>" class="staff-card">
                    <div class="staff-avatar">
                        <?= mb_substr($staff['name'], 0, 1) ?>
                    </div>
                    <div class="staff-name"><?= htmlspecialchars($staff['name']) ?></div>
                    <div class="staff-dept"><?= htmlspecialchars($staff['department']) ?></div>
                    <div class="staff-link-text">評価を入力する →</div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>