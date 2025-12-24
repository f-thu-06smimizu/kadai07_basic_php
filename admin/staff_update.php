<?php
require_once('includes/db_config.php'); // パスは環境に合わせて調整してください

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // データの受け取りと型キャスト
    $id         = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name       = trim($_POST['name'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $is_direct  = isset($_POST['is_direct']) ? (int)$_POST['is_direct'] : 0;

    // 簡単なバリデーション
    if ($id <= 0 || empty($name) || empty($department)) {
        die("入力データが正しくありません。戻ってやり直してください。");
    }

    try {
        // データベースを更新する
        $sql = "UPDATE staffs SET name = ?, department = ?, is_direct = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        $result = $stmt->execute([$name, $department, $is_direct, $id]);

        if ($result) {
            // 成功したら一覧画面へ戻す　メッセージを添える場合はセッションを使うのが一般的
            header('Location: staff_list.php?msg=updated');
            exit;
        } else {
            echo "更新処理に失敗しました。";
        }
    } catch (PDOException $e) {
        // データベースエラー（接続切れや制約違反など）の処理
        echo "エラーが発生しました: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
} else {
    // POST以外でアクセスされた場合は一覧へリダイレクト
    header('Location: staff_list.php');
    exit;
}