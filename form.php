<?php
// エラー表示設定
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 【設定読み込み】データベース接続
require_once('includes/db_config.php'); 

// 【レイアウト】共通ヘッダーとサイドバーの表示
include('includes/header.php'); 

// 【データ取得】URLパラメータから対象スタッフを特定
$staff_id = isset($_GET['id']) ? $_GET['id'] : null;
$staff = null;

if ($staff_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM staffs WHERE id = ?");
        $stmt->execute([$staff_id]);
        $staff = $stmt->fetch();
    } catch (PDOException $e) {
        echo "データベースエラー: " . htmlspecialchars($e->getMessage());
    }
}

// 対象スタッフが不明な場合はエラー表示
if (!$staff) {
    die("<div class='content-area'><div class='card'>スタッフ情報が見つかりません。<a href='index.php'>戻る</a></div></div>");
}
?>

<div class="top-header">
    <h2>📝 評価入力 / <?= htmlspecialchars($staff['name']) ?> さん</h2>
</div>

<div class="content-area">
    <div class="card">
        <form action="save.php" method="POST">
            <input type="hidden" name="target_id" value="<?= htmlspecialchars($staff['id']) ?>">
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">評価者の立場</label>
                <select name="relationship" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px;">
                    <option value="上司">上司</option>
                    <option value="同僚">同僚</option>
                    <option value="部下">部下</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 5px;">① 売上達成率 (%)</label>
                    <input type="number" name="sales_attainment_rate" min="0" max="200" required style="width: 100%; padding: 10px; border: 1px solid #ddd;" placeholder="例: 105">
                </div>

                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 5px;">② 貢献利益 (円)</label>
                    <input type="number" name="contribution_profit" required style="width: 100%; padding: 10px; border: 1px solid #ddd;" placeholder="例: 500000">
                </div>

                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 5px;">③ 姿勢スコア (1-5)</label>
                    <input type="number" name="score_attitude" min="1" max="5" required style="width: 100%; padding: 10px; border: 1px solid #ddd;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 5px;">④ スキルスコア (1-5)</label>
                    <input type="number" name="score_skill" min="1" max="5" required style="width: 100%; padding: 10px; border: 1px solid #ddd;">
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">評価の具体的根拠（自由記述）</label>
                <textarea name="comment" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="AI要約の元データになります"></textarea>
            </div>

            <div style="display: flex; align-items: center; gap: 15px;">
                <button type="submit" style="background-color: #008080; color: white; padding: 12px 30px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">評価を保存する</button>
                <a href="index.php" style="color: #666; text-decoration: none;">キャンセル</a>
            </div>
        </form>
    </div>
</div>

<?php 
if (file_exists('includes/footer.php')) {
    include('includes/footer.php');
} else {
    echo "</div></body></html>";
}
?>