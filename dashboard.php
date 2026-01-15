<?php
$current_page = 'dashboard'; 
require_once('includes/db_config.php'); 

// 既存のロジックを維持
try {
    $total_staff = $pdo->query("SELECT COUNT(*) FROM staffs")->fetchColumn() ?: 0;
    $eval_count = $pdo->query("SELECT COUNT(*) FROM evaluations")->fetchColumn() ?: 0;
    $dept_sql = "SELECT department, AVG(total_score) as avg_dept_score FROM (SELECT s.department, ((COALESCE(e.sales_attainment_rate, 0) * 0.4) + (COALESCE(e.score_attitude, 0) * 20 * 0.3) + (COALESCE(e.score_skill, 0) * 20 * 0.3)) as total_score FROM staffs s JOIN evaluations e ON s.id = e.target_id) as subquery GROUP BY department";
    $dept_data = $pdo->query($dept_sql)->fetchAll();
    $avg_total = count($dept_data) > 0 ? array_sum(array_column($dept_data, 'avg_dept_score')) / count($dept_data) : 0;
} catch (PDOException $e) { $dept_data = []; $avg_total = 0; }

include('includes/header.php'); 
?>

<div class="top-header">
    <h2>📊 経営ダッシュボード</h2>
</div>

<div class="content-area">
    <div class="stat-group">
        <div class="stat-card">
            <span class="stat-label">組織平均スコア</span>
            <p class="stat-value primary"><?= number_format($avg_total, 1) ?><span class="unit">点</span></p>
        </div>
        <div class="stat-card">
            <span class="stat-label">想定ボーナス総額（試算）</span>
            <p class="stat-value">¥<?= number_format($eval_count * 300000) ?></p>
        </div>
        <div class="stat-card">
            <span class="stat-label">評価完了率</span>
            <p class="stat-value warning">
                <?= ($total_staff > 0) ? round(($eval_count / $total_staff) * 100) : 0 ?>%
            </p>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="card">
            <h3>評価実施ステータス</h3>
            <div class="chart-container">
                <canvas id="evaluationChart" data-completed="<?= (int)$eval_count ?>" data-total="<?= (int)$total_staff ?>"></canvas>
            </div>
        </div>

        <div class="card">
            <h3>部門別平均パフォーマンス</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>部門名</th>
                        <th>平均スコア</th>
                        <th>状況</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dept_data as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['department']) ?></td>
                        <td class="font-bold"><?= number_format($d['avg_dept_score'], 1) ?></td>
                        <td>
                            <span class="status-badge <?= ($d['avg_dept_score'] >= 70) ? 'good' : 'check' ?>">
                                <?= ($d['avg_dept_score'] >= 70) ? '良好' : '要確認' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="js/dashboard.js"></script>
<?php include('includes/footer.php'); ?>