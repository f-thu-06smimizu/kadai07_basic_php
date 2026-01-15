<?php
$host = 'localhost';
$dbname = 'bonus_app_db';
$user = 'root';
$pass = ''; // MacのXAMPPは初期設定では空です。変数名を $pass に統一します。

try {
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('DB接続エラー:' . $e->getMessage());
}