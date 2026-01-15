<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// パス注意：adminフォルダから一つ上のincludesを見る
require_once('../includes/db_config.php'); 
include('../includes/header.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = $_POST['name'] ?? '';
        $department = $_POST['department'] ?? '';
        $is_direct = (int)($_POST['is_direct'] ?? 1);

        $sql = "INSERT INTO staffs (name, department, is_direct, created_at) 
                VALUES (:name, :department, :is_direct, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':department' => $department,
            ':is_direct' => $is_direct
        ]);

        // 登録後、管理画面または一覧へ移動
        header('Location: ../admin.php'); 
        exit;
    } catch (PDOException $e) {
        die("登録エラーが発生しました: " . $e->getMessage());
    }
}
?>

<div class="top-header">
    <h2>👤 新規従業員登録</h2>
</div>

<div class="content-area">
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="staff_new.php" method="POST"> <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">氏名</label>
                <input type="text" name="name" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">部署名</label>
                <input type="text" name="department" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">部門種別</label>
                <select name="is_direct" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px;">
                    <option value="1">直接部門</option>
                    <option value="0">間接部門</option>
                </select>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" style="background-color: #008080; color: white; padding: 12px 30px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">登録</button>
                <a href="../admin.php" style="color: #666; text-decoration: none; padding-top: 10px;">キャンセル</a>
            </div>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>