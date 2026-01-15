<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 設定ファイル読み込み（パスをincludesに修正）
require_once('includes/db_config.php'); 
require_once('includes/ai_functions.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 送信データの受け取り（エラー回避のため??で空文字を代入）
        $target_id    = $_POST['target_id'] ?? '';
        $sales_rate   = $_POST['sales_attainment_rate'] ?? 0;
        $profit       = $_POST['contribution_profit'] ?? 0;
        $s_attitude   = $_POST['score_attitude'] ?? 0;
        $s_skill      = $_POST['score_skill'] ?? 0;
        $comment      = $_POST['comment'] ?? '';

        // --- 1. AI分析の実行 ---
        $analysis_input = "売上達成率:{$sales_rate}% 姿勢:{$s_attitude} スキル:{$s_skill} コメント:{$comment}";
        $ai_summary = getGeminiAnalysis($analysis_input);

        // --- 2. データベースへの保存 ---
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
        $avg_score = ($s_attitude + $s_skill) / 2;

        $stmt->execute([
            ':target_id'             => $target_id,
            ':sales_attainment_rate' => $sales_rate,
            ':contribution_profit'   => $profit,
            ':score_attitude'        => $s_attitude,
            ':score_skill'           => $s_skill,
            ':score'                 => $avg_score,
            ':ai_summary'            => $ai_summary 
        ]);

        // --- 3. CSVファイルへの書き込み（課題要件） ---
        $date = date('Y-m-d H:i:s');
        $csv_line = "{$date},{$target_id},{$avg_score},{$comment}\n";

        if (!file_exists('data')) {
            mkdir('data', 0777, true);
        }

        $file = fopen('data/data.csv', 'a');
        flock($file, LOCK_EX);
        fwrite($file, $csv_line);
        flock($file, LOCK_UN);
        fclose($file);

        // 保存成功したらリダイレクト
        header('Location: index.php');
        exit;

    } catch (PDOException $e) {
        die("データベースエラーが発生しました: " . $e->getMessage());
    }
} else {
    header('Location: form.php');
    exit;
}