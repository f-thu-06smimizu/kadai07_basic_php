<?php
$current_page = 'admin';
require_once('../includes/db_config.php');

try {
    // 360度評価ロジックを組み込んだ集計クエリ
    $sql = "
        SELECT 
            s.id, s.name, s.department,
            -- 上司(1on1)の平均点
            AVG(CASE WHEN e.relationship = '上司' THEN e.total_score END) as boss_score,
            -- 同僚・部下の平均点
            AVG(CASE WHEN e.relationship IN ('同僚', '部下') THEN e.total_score END) as peer_score,
            -- 総回答数
            COUNT(e.id) as response_count
        FROM staffs s
        LEFT JOIN evaluations e ON s.id = e.target_id
        GROUP BY s.id, s.name, s.department
        ORDER BY s.id ASC
    ";
    $results = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    exit('データベース接続エラー: ' . $e->getMessage());
}

include('../includes/header.php');
?>

<div class="top-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>⚙️ 評価管理（360度・1on1 統合集計）</h2>
        <a href="../form.php" class="btn-primary" style="padding: 10px 20px; font-size: 0.9rem; background: var(--main-teal); color: white; border-radius: 6px; text-decoration: none;">+ 評価アンケート入力</a>
    </div>
</div>

<div class="content-area">
    <div class="card">
        <h3 style="margin-bottom: 20px; font-size: 1rem; color: #666;">従業員別・多面評価集計（上司60%：同僚40%）</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>対象者 / 部署</th>
                    <th>1on1スコア(上司)</th>
                    <th>360度(同僚・部下)</th>
                    <th>回答数</th>
                    <th>最終総合スコア</th>
                    <th style="text-align: right;">ボーナス配分額(試算)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                <?php 
                    // ロジック：上司6割、同僚4割で加重平均を算出
                    $b_score = $row['boss_score'] ?? 0;
                    $p_score = $row['peer_score'] ?? 0;
                    
                    if($b_score > 0 && $p_score > 0) {
                        $final_score = ($b_score * 0.6) + ($p_score * 0.4);
                    } else {
                        $final_score = $b_score ?: $p_score; // どちらか片方の場合はその値を採用
                    }
                    
                    // スコアに応じたボーナス試算（例：1点あたり5万円）
                    $bonus_amount = $final_score * 50000;
                ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                        <span style="font-size: 0.8rem; color: #999;"><?= htmlspecialchars($row['department']) ?></span>
                    </td>
                    <td style="font-weight: bold; color: <?= $b_score >= 4 ? 'var(--main-teal)' : 'inherit' ?>;">
                        <?= $b_score ? number_format($b_score, 1) : '-' ?>
                    </td>
                    <td><?= $p_score ? number_format($p_score, 1) : '-' ?></td>
                    <td><?= $row['response_count'] ?> 件</td>
                    <td>
                        <span style="padding: 4px 8px; background: #e0f2f1; border-radius: 4px; font-weight: bold;">
                            <?= number_format($final_score, 1) ?>
                        </span>
                    </td>
                    <td style="text-align: right; font-weight: bold; color: var(--main-teal); font-size: 1.1rem;">
                        ¥<?= number_format($bonus_amount) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>