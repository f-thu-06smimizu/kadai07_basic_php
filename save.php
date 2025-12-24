<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('db_config.php');
require_once('ai_functions.php'); // AI関数を読み込み

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // --- 1. AI分析の実行 ---
        // フォームから送られたコメントと数値を合体させてAIに渡す
        $analysis_input = "
            売上達成率: {$_POST['sales_attainment_rate']}%
            姿勢スコア: {$_POST['score_attitude']}
            スキルスコア: {$_POST['score_skill']}
            上長コメント: {$_POST['comment']}
        ";
        
        // AI要約を生成（ai_functions.phpの関数を呼び出し）
        $ai_summary = getGeminiAnalysis($analysis_input);

        // --- 2. SQLの準備（ai_summary カラムを追加） ---
        $sql = "INSERT INTO evaluations (
                    target_id, 
                    sales_attainment_rate, 
                    contribution_profit, 
                    score_attitude, 
                    score_skill, 
                    score, 
                    ai_summary, 
                    created_at
                ) VALUES (
                    :target_id, 
                    :sales_attainment_rate, 
                    :contribution_profit, 
                    :score_attitude, 
                    :score_skill, 
                    :score, 
                    :ai_summary, 
                    NOW()
                )";
        
        $stmt = $pdo->prepare($sql);

        // スコア算出（100点満点ベースで計算しておくとダッシュボードと整合性が取れます）
        $avg_score = ($_POST['score_attitude'] + $_POST['score_skill']) / 2;

        $stmt->execute([
            ':target_id'             => $_POST['target_id'],
            ':sales_attainment_rate' => $_POST['sales_attainment_rate'],
            ':contribution_profit'   => $_POST['contribution_profit'],
            ':score_attitude'        => $_POST['score_attitude'],
            ':score_skill'           => $_POST['score_skill'],
            ':score'                 => $avg_score,
            ':ai_summary'            => $ai_summary // AIの結果を保存
        ]);

        header('Location: admin.php');
        exit;

    } catch (PDOException $e) {
        die("データベースエラーが発生しました: " . $e->getMessage());
    }
} else {
    header('Location: form.php');
    exit;
}