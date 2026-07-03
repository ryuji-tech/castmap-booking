<?php
// 参照 & 表示処理（DBから案件を取り出して一覧表示）
require_once 'config.php';

// SELECT（開始日時の早い順）。表示するだけなのでプレースホルダは不要
$stmt = $pdo->query('SELECT * FROM bookings ORDER BY start_at ASC');
$bookings = $stmt->fetchAll();

// 日時を見やすく整える小関数（NULLなら「—」）
function fmt_dt($s) {
  if (!$s) return '—';
  return date('n/j H:i', strtotime($s));
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>案件一覧 — CastMap</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
  <div class="logo"><span class="pin"></span>CastMap</div>
  <nav><a href="select.php">案件一覧</a><a href="input.php">＋ 新規登録</a></nav>
</header>

<div class="wrap">
  <h1>案件一覧 <span class="muted">（<?= count($bookings) ?>件）</span></h1>

  <?php if (isset($_GET['done'])): ?>
    <div class="msg" style="background:#ecfdf5;color:#047857;border-color:#a7f3d0;">案件を登録しました。</div>
  <?php endif; ?>

  <div class="toolbar">
    <input type="text" id="search" placeholder="🔍 タレント名・案件名・場所で絞り込み">
    <a class="btn" href="input.php">＋ 新規登録</a>
  </div>

  <?php if (!$bookings): ?>
    <div class="card empty">まだ案件がありません。「＋ 新規登録」から追加してください。</div>
  <?php else: ?>
    <table id="list">
      <thead>
        <tr>
          <th>案件名</th><th>タレント</th><th>場所</th><th>開始</th><th>終了</th><th>ステータス</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $b): ?>
          <tr>
            <td><?= h($b['title']) ?></td>
            <td><?= h($b['talent_name']) ?></td>
            <td><?= h($b['place'] ?? '—') ?></td>
            <td><?= fmt_dt($b['start_at']) ?></td>
            <td><?= fmt_dt($b['end_at']) ?></td>
            <td><span class="badge <?= h($b['status']) ?>"><?= h($b['status']) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<script>
// JSによる絞り込み：入力したキーワードを含む行だけ表示（ページ再読み込みなし）
const search = document.getElementById('search');
if (search) {
  search.addEventListener('input', function () {
    const kw = this.value.trim().toLowerCase();
    document.querySelectorAll('#list tbody tr').forEach(tr => {
      tr.style.display = tr.textContent.toLowerCase().includes(kw) ? '' : 'none';
    });
  });
}
</script>
</body>
</html>
