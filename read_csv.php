<?php
$str = '';
$file = fopen('data/data.csv', 'r');
if ($file) {
    while ($line = fgetcsv($file)) {
        // CSVの各項目をテーブルの行として組み立て
        $str .= "<tr>";
        foreach ($line as $value) {
            $str .= "<td>" . htmlspecialchars($value, ENT_QUOTES) . "</td>";
        }
        $str .= "</tr>";
    }
}
fclose($file);
include('includes/header.php');
?>
<div class="content-area">
    <div class="card">
        <h3>CSV保存履歴（課題用データ確認）</h3>
        <table class="data-table">
            <thead>
                <tr><th>保存日時</th><th>対象ID</th><th>スコア</th><th>コメント</th></tr>
            </thead>
            <tbody>
                <?= $str ?>
            </tbody>
        </table>
        <p><a href="index.php">← ホームへ戻る</a></p>
    </div>
</div>
<?php include('includes/footer.php'); ?>