<?php
session_start();
require_once '../config/db.php';

// Получаем ID работы
if (!isset($_GET['id'])) {
  echo "Missing work ID.";
  exit;
}

$id = $_GET['id'];

// Обработка комментария
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
  $comment = $_POST['comment'];
  $work_id = $_POST['work_id'];
  $user_email = $_SESSION['user']['email'] ?? 'Anonymous';

  $stmt = $conn->prepare("INSERT INTO comments (work_id, user_email, comment) VALUES (?, ?, ?)");
  $stmt->execute([$work_id, $user_email, $comment]);

  // После добавления комментария — редирект на эту же страницу
  header("Location: document_view.php?id=" . $work_id);
  exit;
}

// Загружаем саму работу
$stmt = $conn->prepare("SELECT w.*, u.email AS author_email FROM works w JOIN users u ON w.user_id = u.id WHERE w.id = ?");
$stmt->execute([$id]);
$work = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$work) {
  echo "Work not found.";
  exit;
}

// Загружаем комментарии
$comments_stmt = $conn->prepare("SELECT * FROM comments WHERE work_id = ? ORDER BY created_at DESC");
$comments_stmt->execute([$id]);
$comments = $comments_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>DocAi Check - Document View</title>
  <style>
    body {
      margin: 0;
      background-color: #cfd8d6;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      width: 100%;
      max-width: 800px;
      padding: 40px 20px;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .profile-dropdown {
      border-radius: 20px;
      padding: 5px 12px;
    }

    h1 {
      font-size: 20px;
      text-align: center;
      margin-bottom: 5px;
    }

    .subheading {
      text-align: center;
      font-size: 14px;
      color: #555;
      margin-bottom: 20px;
    }

    .interaction-bar {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 20px;
    }

    .interaction-bar button {
      padding: 6px 16px;
      border-radius: 20px;
      border: none;
      background-color: white;
      cursor: pointer;
    }

    .translation-bar {
      text-align: center;
      margin-bottom: 20px;
    }

    .translation-bar select {
      padding: 6px 12px;
      border-radius: 20px;
      border: 1px solid #ccc;
    }

    .topic-box, .comment-box {
      background-color: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }

    .topic-box p {
      font-size: 14px;
      color: #444;
    }

    .comment-box textarea {
      width: 100%;
      padding: 12px 16px;
      font-size: 14px;
      border-radius: 12px;
      border: 1px solid #ccc;
      resize: vertical;
      min-height: 60px;
      background-color: #f7f7f7;
    }

    .comment-box button {
      margin-top: 10px;
      padding: 8px 20px;
      border: none;
      background-color: #888;
      color: white;
      border-radius: 10px;
      cursor: pointer;
      float: right;
    }

    .back-button {
      display: block;
      margin: 0 auto;
      padding: 10px 20px;
      border: none;
      background-color: #eee;
      border-radius: 20px;
      cursor: pointer;
    }

    .footer {
      font-size: 12px;
      color: #777;
      text-align: center;
      margin-top: 40px;
    }
  </style>
</head>
<body>

  <div class="container">

    <div class="top-bar">
      <div></div>
      <select class="profile-dropdown">
        <option>Profile</option>
        <option>Settings</option>
        <option>Logout</option>
      </select>
    </div>

    <div class="subheading">Author: <?= htmlspecialchars($work['author_email']) ?></div>
    <div class="subheading">Title: <?= htmlspecialchars($work['title']) ?></div>

    <div class="interaction-bar">
      <button>Like 0</button>
      <button>Dislike 0</button>
    </div>

    <div class="translation-bar">
      <label for="lang">Available Translations:</label>
      <select id="lang">
        <option>English</option>
        <option>Latvian</option>
        <option>Spanish</option>
      </select>
    </div>

    <div class="topic-box">
      <strong>Topic</strong><br />
      <p><?= nl2br(htmlspecialchars($work['content'])) ?></p>
    </div>

    <div class="comment-box">
      <form method="POST">
        <input type="hidden" name="work_id" value="<?= $work['id'] ?>">
        <textarea name="comment" placeholder="Add your comment..." required></textarea>
        <button type="submit">Submit</button>
      </form>
    </div>

    <?php foreach ($comments as $c): ?>
      <div class="comment-box">
        <p><strong><?= htmlspecialchars($c['user_email']) ?>:</strong><br>
        <?= nl2br(htmlspecialchars($c['comment'])) ?></p>
        <small><?= $c['created_at'] ?></small>
      </div>
    <?php endforeach; ?>


    <button class="back-button" onclick="history.back()">← Back to reading</button>
  </div>

  <div class="footer">Need help? Contact us!</div>

</body>
</html>
