<?php
require __DIR__ . '/db.php';

$error = '';

// Save a new message (INSERT with a prepared statement)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = trim($_POST['author'] ?? '');
    $text = trim($_POST['text'] ?? '');

    if ($author === '' || $text === '') {
        $error = 'Please fill in your name and message.';
    } elseif (mb_strlen($author) > 50 || mb_strlen($text) > 255) {
        $error = 'Name up to 50 characters and message up to 255.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO messages (author, text) VALUES (?, ?)');
        $stmt->execute([$author, $text]);
        header('Location: /');   // Post/Redirect/Get pattern: avoids saving twice on refresh
        exit;
    }
}

// Read the messages (SELECT)
$messages = $pdo->query('SELECT author, text, created_at FROM messages ORDER BY id DESC')->fetchAll();
$total = count($messages);

// Technical information to show in the report
$dbVersion = $pdo->query('SELECT VERSION() AS v')->fetch()['v'];
$container = gethostname();

function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Guestbook · LAMP with Docker</title>
  <style>
    body { font-family: system-ui, sans-serif; background: #f4f5f7; color: #222; margin: 0; }
    main { max-width: 720px; margin: 0 auto; padding: 24px 16px; }
    h1 { margin-bottom: 4px; }
    .sub { color: #666; margin-top: 0; }
    .card { background: #fff; border-radius: 10px; padding: 16px 20px; margin: 16px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    label { display: block; font-weight: 600; margin: 10px 0 4px; }
    input, textarea { width: 100%; box-sizing: border-box; padding: 8px; border: 1px solid #ccc;
                      border-radius: 6px; font: inherit; }
    button { margin-top: 12px; padding: 8px 18px; border: 0; border-radius: 6px;
             background: #2563eb; color: #fff; font-weight: 600; cursor: pointer; }
    .error { color: #b91c1c; font-weight: 600; }
    .msg { border-top: 1px solid #eee; padding: 10px 0; }
    .msg:first-child { border-top: 0; }
    .meta { color: #888; font-size: .85em; }
    .info { font-size: .9em; color: #444; }
    .info code { background: #eef; padding: 1px 5px; border-radius: 4px; }
  </style>
</head>
<body>
<main>
  <h1>Guestbook</h1>
  <p class="sub">Test PHP application running on a LAMP stack with Docker Compose</p>

  <div class="card info">
    Web server: <code>Apache + PHP <?= e(PHP_VERSION) ?></code> in container <code><?= e($container) ?></code><br>
    Database: <code><?= e($dbVersion) ?></code> on host <code><?= e(getenv('DB_HOST') ?: 'db') ?></code><br>
    Stored messages: <code><?= $total ?></code>
  </div>

  <div class="card">
    <h2>Leave a message</h2>
    <?php if ($error): ?><p class="error"><?= e($error) ?></p><?php endif; ?>
    <form method="post">
      <label for="author">Name</label>
      <input id="author" name="author" maxlength="50" required>
      <label for="text">Message</label>
      <textarea id="text" name="text" rows="3" maxlength="255" required></textarea>
      <button type="submit">Save</button>
    </form>
  </div>

  <div class="card">
    <h2>Messages</h2>
    <?php foreach ($messages as $m): ?>
      <div class="msg">
        <strong><?= e($m['author']) ?></strong>
        <span class="meta">· <?= e($m['created_at']) ?></span>
        <div><?= nl2br(e($m['text'])) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</main>
</body>
</html>
