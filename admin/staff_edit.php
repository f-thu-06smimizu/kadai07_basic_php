<?php
// セッション開始（CSRF対策等で必要になる場合が多い）
session_start();
require_once('includes/db_config.php');

// URLの id を取得
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: staff_list.php');
    exit;
}

// 従業員データの取得（プリペアドステートメントで安全に）
$stmt = $pdo->prepare("SELECT * FROM staffs WHERE id = ?");
$stmt->execute([$id]);
$staff = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$staff) {
    echo "従業員が見つかりません。";
    exit;
}

// ヘッダー読み込み
$current_page = 'staff_list'; 
include('includes/header.php'); 
?>

<div class="main-container">
    <div class="header" style="margin-bottom: 20px;">
        <h2 style="margin:0; font-size:1.4rem;">従業員情報の編集</h2>
    </div>

    <div class="content">
        <div class="card" style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <form action="staff_update.php" method="POST">
                <input type="hidden" name="id" value="<?php echo (int)$staff['id']; ?>">

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display:block; font-weight:bold; margin-bottom:8px; color: #444;">氏名</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($staff['name'], ENT_QUOTES, 'UTF-8'); ?>" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display:block; font-weight:bold; margin-bottom:8px; color: #444;">所属部署</label>
                    <input type="text" name="department" value="<?php echo htmlspecialchars($staff['department'], ENT_QUOTES, 'UTF-8'); ?>" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 30px; padding: 20px; background: #f0f7f7; border-radius: 8px; border: 1px dashed var(--main-teal);">
                    <label style="display:block; font-weight:bold; margin-bottom:12px; color: #008080;">📊 算出ロジックの適用区分</label>
                    <div style="display: flex; gap: 40px;">
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="is_direct" value="1" <?php echo ($staff['is_direct'] == 1) ? 'checked' : ''; ?>> 
                            <span style="font-weight: bold;">直接部門</span>
                        </label>
                        <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="is_direct" value="0" <?php echo ($staff['is_direct'] == 0) ? 'checked' : ''; ?>> 
                            <span style="font-weight: bold;">間接部門</span>
                        </label>
                    </div>
                    <p style="font-size: 0.8rem; color: #666; margin-top: 10px; line-height: 1.4;">
                        ※直接部門は「売上達成率」を重視し、間接部門は「スキル・姿勢スコア」を重く配分します。
                    </p>
                </div>

                <div style="display: flex; gap: 15px;">
                    <button type="submit" style="flex: 2; background: #008080; color: white; border: none; padding: 14px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: opacity 0.2s;">
                        変更を保存する
                    </button>
                    <a href="staff_list.php" style="flex: 1; text-align: center; background: #eee; color: #333; text-decoration: none; padding: 14px; border-radius: 6px; font-weight: bold; display: flex; align-items: center; justify-content: center;">
                        キャンセル
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>