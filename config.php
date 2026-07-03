<?php
/* =========================================================
   DB接続設定（PDO）
   実行しているサーバー名で「ローカル(XAMPP)」と「本番(さくら)」を
   自動で切り替える（クラス資料の中級編と同じ考え方）。
   → デプロイのたびに書き換えなくてよい。
   ※このファイルには本番のパスワードが入るので GitHub には上げない
     （.gitignore で config.php を除外済み。GitHubには config.sample.php を上げる）
   ========================================================= */

$host = $_SERVER['SERVER_NAME'] ?? '';

if ($host === 'localhost' || $host === '127.0.0.1') {
  // ===== ローカル(XAMPP) =====
  $DB_HOST = 'localhost';
  $DB_NAME = 'castmap';
  $DB_USER = 'root';   // XAMPP初期ユーザー
  $DB_PASS = '';       // XAMPP初期パスワードは空
} else {
  // ===== 本番(さくらのレンタルサーバー) =====
  // さくらのコントロールパネル→データベースで確認した値を入れる
  $DB_HOST = 'mysql3116.db.sakura.ne.jp'; // 割り当てられたDBサーバ名
  $DB_NAME = 'prema_php02';               // 作成したDB名（php02 → prema_php02）
  $DB_USER = 'prema_php02';               // さくらはユーザー名＝DB名
  $DB_PASS = 'あなたのDBパスワード';        // ←DB作成時に決めたパスワードに置き換える
}

try {
  $pdo = new PDO(
    "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ]
  );
} catch (PDOException $e) {
  exit('DB接続エラー: ' . $e->getMessage());
}

// 表示用の安全化関数（XSS対策）
function h($str) {
  return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// ステータスのホワイトリスト
const STATUS_LIST = ['打診中', '仮', '確定', '完了'];
