<?php
/**
 * AI分析関数（デモ用）
 * 実際にはGemini APIを叩かず、入力されたデータに基づいて
 * ランダムに「AIっぽい」要約を生成して返す
 */
function getGeminiAnalysis($text) {
    // 開発中のデバッグ用に、少し待ち時間を作ると「通信してる感」が出ます
    // usleep(500000); // 0.5秒待機（必要なければコメントアウト）

    // 入力データが空の場合
    if (empty(trim($text))) {
        return "分析対象のデータが不足しているため、要約を生成できませんでした。";
    }

    // デモ用の定型文リスト（これらを組み合わせて回答っぽく見せます）
    $strengths = ["自己研鑽に励む姿勢", "目標達成への強い意欲", "周囲を巻き込むリーダーシップ", "高い専門知識"];
    $weaknesses = ["マルチタスクの整理", "後進への適切な指示出し", "短期的な売上変動への耐性", "報告・連絡のスピード"];
    $expectations = ["次世代のリーダー候補としての活躍", "部署内の業務効率化の推進", "更なる大型案件の獲得", "技術知識のチーム内共有"];

    // ランダムに抽出
    $s = $strengths[array_rand($strengths)];
    $w = $weaknesses[array_rand($weaknesses)];
    $e = $expectations[array_rand($expectations)];

    // 実際のGemini風のフォーマットで回答を作成
    $dummy_response = "【AI分析結果】\n";
    $dummy_response .= "強み：{$s}が非常に高く評価されています。\n";
    $dummy_response .= "課題：今後は{$w}に注力することで、さらなる成長が見込めます。\n";
    $dummy_response .= "期待：{$e}としての役割を担うことを強く推奨します。";

    return $dummy_response;
}

/**
 * 将来、本番用のAPIを使いたい時に切り替えるためのメモ
 * $api_key = 'ここに新しいキー';
 * $url = "https://generativelanguage.googleapis.com/...";
 * ...（以前書いたcURLのコード）...
 */