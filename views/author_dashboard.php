<?php
require_once '../php/session.php';

if ($_SESSION['user']['role'] !== 'Author') {
    header("Location: ../views/login.html");
    exit;
}

require_once '../config/db.php';

$user_id = $_SESSION['user']['id'];

$stmt = $conn->prepare("SELECT * FROM works WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$works = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Author Dashboard - DocAi Check</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #cfd8d6;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 40px;
      background-color: #cfd8d6;
    }

    .header h1 {
      margin: 0;
      font-size: 24px;
    }

    .stats {
      display: flex;
      gap: 20px;
      font-size: 14px;
      background-color: #cfd8d6;
      padding: 0 40px;
    }

    .content {
      display: flex;
      gap: 30px;
      padding: 30px 40px;
      justify-content: space-between;
    }

    .card-column {
      flex: 1;
    }

    .card-column h2 {
      margin-bottom: 20px;
    }

    .paper-card, .comment-card {
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .paper-card h3 {
      margin-top: 0;
      margin-bottom: 5px;
    }

    .paper-card .rating {
      font-size: 14px;
      color: #666;
      margin-bottom: 10px;
    }

    .paper-card p {
      font-size: 14px;
      color: #444;
    }

    .see-more {
      font-size: 12px;
      color: #888;
      text-decoration: underline;
      cursor: pointer;
    }

    .upload-column {
      width: 200px;
      text-align: center;
    }

    .upload-box {
      border: 2px dashed #888;
      border-radius: 5px;
      padding: 40px;
      cursor: pointer;
    }

    .upload-box:hover {
      background-color: #e0e0e0;
    }

    .signout-button {
      background-color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 20px;
      cursor: pointer;
      font-weight: bold;
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .footer {
      font-size: 12px;
      color: #888;
      text-align: right;
      padding: 20px 40px;
    }
  </style>
</head>
<body>
  <div class="header">
    <h1>DocAi Check</h1>
    <form action="../php/logout.php" method="POST">
      <button class="signout-button">Sign Out</button>
    </form>
  </div>

  <div class="stats">
    <div>Rating: <strong>4.5</strong></div>
    <div>Balance: <strong>5$</strong></div>
  </div>

  <div class="content">
  <div class="card-column">
    <h2>Recent works</h2>
    <?php foreach ($works as $work): ?>
      <div class="paper-card">
        <h3><?= htmlspecialchars($work['title']) ?></h3>
        <div class="rating">Type: <?= htmlspecialchars($work['type']) ?></div>
        <p><?= mb_strimwidth(strip_tags($work['content']), 0, 100, "...") ?></p>
        <div class="see-more">See more...</div>
      </div>
    <?php endforeach; ?>
  </div>

    <div class="card-column">
      <h2>Reader comments</h2>
      <div class="comment-card">
        <p><strong>person1:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
        <div class="see-more">See more...</div>
      </div>
      <div class="comment-card">
        <p><strong>person2:</strong> Nullam placerat tincidunt rhoncus...</p>
        <div class="see-more">See more...</div>
      </div>
      <div class="comment-card">
        <p><strong>person3:</strong> Mauris eleifend ligula leo, sit amet venenatis neque...</p>
        <div class="see-more">See more...</div>
      </div>
      <div class="comment-card">
        <p><strong>person4:</strong> Donec sodales in elit a iaculis...</p>
        <div class="see-more">See more...</div>
      </div>
    </div>

    <div class="upload-column">
      <h2>Upload new work</h2>
      <a href="upload.html">
        <div class="upload-box">
          <span style="font-size: 32px;">⬆️</span>
        </div>
      </a>
    </div>
  </div>

  <div class="footer">Need help? Contact us!</div>
</body>
</html>
