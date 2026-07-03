<?php
// 作成処理（フォームから送られた値を検証してDBに INSERT する）
require_once 'config.php';

// POST以外でアクセスされたら一覧へ
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: select.php');
  exit;
}

// 1) 入力値を受け取る（未入力は空文字に）
$title       = trim($_POST['title'] ?? '');
$talent_name = trim($_POST['talent_name'] ?? '');
$place       = trim($_POST['place'] ?? '');
$start_at    = trim($_POST['start_at'] ?? '');
$end_at      = trim($_POST['end_at'] ?? '');
$status      = $_POST['status'] ?? '打診中';
$memo        = trim($_POST['memo'] ?? '');

// 2) 入力チェック（バリデーション）
$errors = [];
if ($title === '')       { $errors[] = '案件名は必須です。'; }
if ($talent_name === '') { $errors[] = 'タレント名は必須です。'; }
if ($start_at === '')    { $errors[] = '開始日時は必須です。'; }
// ステータスは決められた値（ホワイトリスト）のみ許可
if (!in_array($status, STATUS_LIST, true)) { $status = '打診中'; }

// datetime-local の値は "2026-07-10T13:00" 形式。MySQLのDATETIME用に T を空白へ
$start_at = $start_at !== '' ? str_replace('T', ' ', $start_at) : null;
$end_at   = $end_at   !== '' ? str_replace('T', ' ', $end_at)   : null;
$place    = $place !== '' ? $place : null;
$memo     = $memo  !== '' ? $memo  : null;

// エラーがあれば入力画面へ戻す（メッセージ付き）
if ($errors) {
  header('Location: input.php?err=' . urlencode(implode(' ', $errors)));
  exit;
}

// 3) INSERT（プリペアドステートメントでSQLインジェクション対策）
$sql = 'INSERT INTO bookings (title, talent_name, place, start_at, end_at, status, memo)
        VALUES (:title, :talent_name, :place, :start_at, :end_at, :status, :memo)';
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':title'       => $title,
  ':talent_name' => $talent_name,
  ':place'       => $place,
  ':start_at'    => $start_at,
  ':end_at'      => $end_at,
  ':status'      => $status,
  ':memo'        => $memo,
]);

// 4) 登録後は一覧へ移動（PRGパターン：再読み込みでの二重登録を防ぐ）
header('Location: select.php?done=1');
exit;
