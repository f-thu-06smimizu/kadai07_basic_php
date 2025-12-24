<?php
/**
 * ダッシュボード詳細画面
 * 各スタッフの評価スコアを算出し、平均スコア、分布グラフ、一覧表を表示します。
 */

// --- 1. 初期設定と接続 ---
$current_page = 'dashboard_detail'; // メニューのactive制御用
require_once('includes/db_config.php');
include('includes/header.php'); 

// --- 2. データの取得 ---
// 重み付けの設定を取得
$sys = $pdo->query("SELECT * FROM system_settings WHERE id = 1")->fetch();

// スタッフ情報と最新の評価データを結合
$sql = "
    SELECT s.*, e.sales_attainment_rate as sales, e.score_attitude as attitude, e.score_skill as skill
    FROM staffs s
    LEFT JOIN (
        SELECT target_id, sales_attainment_rate, score_attitude, score_skill
        FROM evaluations WHERE id IN (SELECT MAX(id) FROM evaluations GROUP BY target_id)
    ) e ON s.id = e.target_id
";
$staffs = $pdo->query($sql)->fetchAll();

// --- 3. 評価スコアの算出ロジック ---
$names = []; 
$scores = []; 
$total_sum = 0;

foreach ($staffs as &$staff) {
    // 区分（直接/間接）による重みの切り替え
    $is_d = ($staff['is_direct'] == 1);
    $w_sales    = ($is_d ? $sys['direct_weight_sales'] : $sys['indirect_weight_sales']) / 100;
    $w_attitude = ($is_d ? $sys['direct_weight_attitude'] : $sys['indirect_weight_attitude']) / 100;
    $w_skill    = ($is_d ? $sys['direct_weight_skill'] : $sys['indirect_weight_skill']) / 100;

    // 総合スコア計算（100点満点換算）
    $score = (($staff['sales'] ?? 0) * $w_sales) + 
             (($staff['attitude'] ?? 0) * 20 * $w_attitude) + 
             (($staff['skill'] ?? 0) * 20 * $w_skill);
    
    $staff['total_score'] = $score;
    $names[] = $staff['name'];
    $scores[] = round($score, 1);
    $total_sum += $score;
}

// 平均スコアの算出
$avg_score = count($staffs) > 0 ? round($total_sum / count($staffs), 1) : 0;
?>

<div class="top-header">
    <h2>📊 評価ダッシュボード詳細</h2>
</div>

<div class="content-area">
    <div class="dashboard-grid">
        <div class="card score-display">
            <p class="score-label">全体平均スコア</p>
            <div class="score-value">
                <?= $avg_score ?><span class="score-unit">点</span>
            </div>
        </div>

        <div class="card">
            <h3 class="m-0">個人別スコア分布</h3>
            <div class="chart-container" style="height: 300px; margin-top: 15px;">
                <canvas id="scoreChart" 
                        data-labels='<?= json_encode($names, JSON_HEX_APOS) ?>' 
                        data-scores='<?= json_encode($scores) ?>'>
                </canvas>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 20px;">
        <h3>算出結果詳細</h3>
        <table class="staff-table">
            <thead>
                <tr>
                    <th>氏名</th>
                    <th>区分</th>
                    <th class="text-right">総合スコア</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staffs as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['name']) ?></td>
                    <td><?= ($s['is_direct'] == 1) ? '🎯 直接' : '🤝 間接' ?></td>
                    <td class="text-right font-bold"><?= number_format($s['total_score'], 1) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="js/score_chart.js"></script>

<?php include('includes/footer.php'); ?>