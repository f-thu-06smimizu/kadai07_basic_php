<?php
// 1. ページ識別子を設定（サイドバーの「active」表示に必要）
$current_page = 'staff_list';

// 2. 読み込みパスの修正（../ を付けて一つ上の階層を見に行く）
require_once('../includes/db_config.php');

try {
    $stmt = $pdo->query("SELECT * FROM staffs ORDER BY id ASC");
    $staffs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit('データベース接続エラー: ' . $e->getMessage());
}

// 3. ヘッダーの読み込み（これも ../ が必要）
include('../includes/header.php');
?>

<div class="top-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>👥 従業員一覧</h2>
        <a href="staff_new.php" class="btn-primary" style="padding: 10px 20px; font-size: 0.9rem; background: var(--main-teal); color: white; border-radius: 6px; text-decoration: none;">+ 新規登録</a>
    </div>
</div>

<div class="content-area">
    <div class="card">
        <table class="data-table"> <thead>
                <tr>
                    <th>ID</th>
                    <th>氏名</th>
                    <th>所属部署</th>
                    <th>算出タイプ</th>
                    <th style="text-align: center;">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staffs as $staff): ?>
                <tr>
                    <td>#<?= (int)$staff['id'] ?></td>
                    <td><strong><?= htmlspecialchars($staff['name']) ?></strong></td>
                    <td><?= htmlspecialchars($staff['department']) ?></td>
                    <td>
                        <?php if (isset($staff['is_direct']) && $staff['is_direct'] == 1): ?>
                            <span style="color: var(--main-teal); font-weight: bold;">直接部門</span>
                        <?php else: ?>
                            <span style="color: #7f8c8d;">間接部門</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <a href="staff_edit.php?id=<?= $staff['id'] ?>" style="color: var(--main-teal); text-decoration: none; font-weight: bold;">編集</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
// 4. フッターの読み込み（最後にある余計な </div> などをフッターに任せる）
include('../includes/footer.php'); 
?>