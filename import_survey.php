<?php
// ヘッダー読み込み（DB接続・CSS・サイドバー開始を含む）
include('header.php'); 
?>

<div class="top-header">
    <h2>スタッフ評価CSVインポート</h2>
</div>

<div class="content-area">
    <div class="card form-container">
        <h3>CSVファイルのアップロード</h3>
        <p class="upload-instruction">
            <strong>【CSV形式】</strong> 社員ID, 平均スコア（5点満点）<br>
            例: 1, 4.5
        </p>
        
        <form action="upload_process.php" method="post" enctype="multipart/form-data" class="upload-box">
            <input type="file" name="csv_file" accept=".csv" required>
            <br><br>
            <button type="submit" class="btn-primary">
                CSVを取り込む
            </button>
        </form>

        <div style="margin-top: 20px; font-size: 0.8rem; color: #999;">
            ※取り込まれたスコアは「スキルスコア」として最新評価に反映されます。
        </div>
    </div>
</div>

</div> </body>
</html>