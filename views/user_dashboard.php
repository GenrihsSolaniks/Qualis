<?php
require_once '../php/session.php';

if ($_SESSION['user']['role'] !== 'User') {
    header("Location: ../views/login.html");
    exit;
}

require_once '../config/db.php';

$stmt = $conn->prepare("SELECT w.*, u.email AS author_email 
                        FROM works w 
                        JOIN users u ON w.user_id = u.id 
                        WHERE w.status = 'approved' 
                        ORDER BY w.created_at DESC");
$stmt->execute();
$works = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DocAi Check - User Dashboard</title>
  <style>
    body {
      margin: 0;
      background-color: #cfd8d6;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 40px;
      background-color: #cfd8d6;
    }

    .search-bar input {
      padding: 10px 20px;
      border-radius: 20px;
      border: 1px solid #ccc;
      width: 250px;
    }

    .signout-button {
      padding: 8px 16px;
      border: none;
      background-color: white;
      border-radius: 20px;
      cursor: pointer;
      font-weight: bold;
    }

    .content {
      display: flex;
      gap: 30px;
      padding: 20px 40px 60px;
      justify-content: space-between;
    }

    .column {
      flex: 1;
    }

    h2 {
      margin-bottom: 15px;
    }

    .paper-card, .comment-card {
      background-color: white;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .paper-card h3 {
      margin-top: 0;
      font-size: 16px;
    }

    .paper-card p,
    .comment-card p {
      font-size: 14px;
      color: #444;
    }

    .rating {
      font-size: 13px;
      color: #666;
      margin-bottom: 10px;
    }

    .see-more {
      font-size: 12px;
      color: #888;
      text-decoration: underline;
      cursor: pointer;
    }

    .footer {
      text-align: center;
      font-size: 12px;
      color: #777;
      padding-bottom: 20px;
    }
  </style>
</head>
<body>

  <header>
    <div class="search-bar">
      <input type="text" placeholder="Search...">
    </div>
    <h1>DocAi Check</h1>
    <form action="../php/logout.php" method="POST">
      <button class="signout-button">Sign Out</button>
    </form>
  </header>

  <div class="content">
  <!-- Recently viewed -->
  <div class="column">
    <h2>Recently viewed:</h2>
    <div class="paper-card">
      <h3>Paper 1</h3>
      <div class="rating">Rating: 4.9</div>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
      <div class="see-more">See more...</div>
    </div>
    <div class="paper-card">
      <h3>Paper 2</h3>
      <div class="rating">Rating: 4.1</div>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
      <div class="see-more">See more...</div>
    </div>
  </div>

  <!-- AI suggestions -->
  <div class="column">
    <h2>You might also like</h2>
    <div class="paper-card">
      <h3>Paper 3</h3>
      <div class="rating">Rating: 5</div>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
      <div class="see-more">See more...</div>
    </div>
    <div class="paper-card">
      <h3>Paper 4</h3>
      <div class="rating">Rating: 5</div>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
      <div class="see-more">See more...</div>
    </div>
  </div>

  <!-- ✅ Динамически загружаемые работы -->
  <div class="column">
  <h2>Available works:</h2>
  <?php foreach ($works as $work): ?>
    <div class="paper-card">
      <h3><?= htmlspecialchars($work['title']) ?></h3>
      <div class="rating">Type: <?= htmlspecialchars($work['type']) ?> | Category: <?= htmlspecialchars($work['category']) ?></div>
      <p><?= mb_strimwidth(strip_tags($work['content']), 0, 100, "...") ?></p>
      <a class="see-more" href="document_view.php?id=<?= $work['id'] ?>">See more...</a>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Comments -->
  <div class="column">
    <h2>Your comments:</h2>
    <div class="comment-card">
      <p><strong>person1:</strong> Lorem ipsum dolor sit amet...</p>
      <p><em>author:</em> Aliquam eget justo a neque rhoncus scelerisque...</p>
      <div class="see-more">See more...</div>
    </div>
    <div class="comment-card">
      <p><strong>person1:</strong> Nullam placerat tincidunt rhoncus...</p>
      <p><em>author liked your comment</em></p>
    </div>
    <div class="comment-card">
      <p><strong>person2:</strong> liked your comment</p>
    </div>
  </div>
</div>

</body>
</html>
