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
    <h2>📝 評価アンケート入力 / <?= htmlspecialchars($staff['name']) ?> さん</h2>
</div>

<div class="content-area">
    <div class="card">
        <form action="save.php" method="POST">
            <input type="hidden" name="staff_id" value="<?= htmlspecialchars($staff['id']) ?>">
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">あなたの立場（必須）</label>
                <select name="relationship" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px;">
                    <option value="">選択してください</option>
                    <option value="上司">上司（1on1・定量/定性評価）</option>
                    <option value="同僚">同僚（360度・定性アンケート）</option>
                    <option value="部下">部下（360度・定性アンケート）</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 5px;">① KPI達成度（定量成果）</label>
                    <input type="number" name="kpi_score" min="1" max="5" required style="width: 100%; padding: 10px; border: 1px solid #ddd;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 5px;">② バリュー体現（定性行動）</label>
                    <input type="number" name="value_score" min="1" max="5" required style="width: 100%; padding: 10px; border: 1px solid #ddd;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 5px;">③ スキル・専門性</label>
                    <input type="number" name="skill_score" min="1" max="5" required style="width: 100%; padding: 10px; border: 1px solid #ddd;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: bold; margin-bottom: 5px;">④ チーム貢献度</label>
                    <input type="number" name="team_score" min="1" max="5" required style="width: 100%; padding: 10px; border: 1px solid #ddd;">
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">評価の具体的根拠（自由記述）</label>
                <textarea name="comment" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" placeholder="具体的な行動事例を記入してください"></textarea>
            </div>

            <div style="display: flex; align-items: center; gap: 15px;">
                <button type="submit" style="background-color: #008080; color: white; padding: 12px 30px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">評価を保存する</button>
                <a href="index.php" style="color: #666; text-decoration: none;">キャンセル</a>
            </div>
        </form>
    </div>
</div>

<?php 
// 【レイアウト】共通フッター。Warning解消のためパスを確認。
if (file_exists('includes/footer.php')) {
    include('includes/footer.php');
} else {
    // パスが通らない場合の予備終了タグ
    echo "</div></body></html>";
}
?>