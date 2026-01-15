<?php
require_once('db_config.php');

// 1. 設定の更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE system_settings SET 
            total_budget = ?, direct_weight_sales = ?, direct_weight_attitude = ?, direct_weight_skill = ?,
            indirect_weight_sales = ?, indirect_weight_attitude = ?, indirect_weight_skill = ?
            WHERE id = 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['total_budget'], $_POST['direct_weight_sales'], $_POST['direct_weight_attitude'], $_POST['direct_weight_skill'],
        $_POST['indirect_weight_sales'], $_POST['indirect_weight_attitude'], $_POST['indirect_weight_skill']
    ]);
    $message = "設定を保存しました。";
}

// 2. 現在の設定を取得
$stmt = $pdo->query("SELECT * FROM system_settings WHERE id = 1");
$settings = $stmt->fetch();

// データがない場合の初期値
if (!$settings) {
    $settings = [
        'total_budget' => 5000000,
        'direct_weight_sales' => 70, 'direct_weight_attitude' => 15, 'direct_weight_skill' => 15,
        'indirect_weight_sales' => 20, 'indirect_weight_attitude' => 40, 'indirect_weight_skill' => 40
    ];
}

include('header.php'); 
?>

<div class="top-header">
    <h2>部門別計算ロジック設定</h2>
</div>

<div class="content-area">
    <?php if (isset($message)): ?>
        <div class="alert-success"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="card mb-20">
            <h3 class="m-0 border-bottom-10">全体予算設定</h3>
            <div class="form-group">
                <label class="font-bold">インセンティブ総予算 (円)</label>
                <input type="number" name="total_budget" value="<?= htmlspecialchars($settings['total_budget']) ?>" class="input-field" style="max-width: 300px;">
            </div>
        </div>

        <div class="settings-flex">
            <div class="card settings-card">
                <h3 class="text-teal border-bottom-10">🎯 直接部門 (営業・現場)</h3>
                <div class="form-group">
                    <label>売上達成率の重み (%)</label>
                    <input type="number" name="direct_weight_sales" value="<?= $settings['direct_weight_sales'] ?>" class="input-field">
                </div>
                <div class="form-group">
                    <label>行動姿勢の重み (%)</label>
                    <input type="number" name="direct_weight_attitude" value="<?= $settings['direct_weight_attitude'] ?>" class="input-field">
                </div>
                <div class="form-group">
                    <label>スキルの重み (%)</label>
                    <input type="number" name="direct_weight_skill" value="<?= $settings['direct_weight_skill'] ?>" class="input-field">
                </div>
            </div>

            <div class="card settings-card">
                <h3 class="border-bottom-10">間接部門 (バックオフィス)</h3>
                <div class="form-group">
                    <label>売上/目標達成率の重み (%)</label>
                    <input type="number" name="indirect_weight_sales" value="<?= $settings['indirect_weight_sales'] ?>" class="input-field">
                </div>
                <div class="form-group">
                    <label>行動姿勢の重み (%)</label>
                    <input type="number" name="indirect_weight_attitude" value="<?= $settings['indirect_weight_attitude'] ?>" class="input-field">
                </div>
                <div class="form-group">
                    <label>スキルの重み (%)</label>
                    <input type="number" name="indirect_weight_skill" value="<?= $settings['indirect_weight_skill'] ?>" class="input-field">
                </div>
            </div>
        </div>

        <div class="text-center mt-30">
            <button type="submit" class="btn-submit" style="max-width: 400px;">
                設定を保存して更新する
            </button>
        </div>
    </form>
</div>

</div> </body> </html>