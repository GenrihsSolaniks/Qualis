<?php
session_start();
require_once '../config/db.php';

// Проверка авторизации
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Author') {
    header("Location: ../views/login.html");
    exit;
}

$user_id  = $_SESSION['user']['id'];
$title    = $_POST['title'] ?? '';
$category = $_POST['category'] ?? '';
$content  = $_POST['content'] ?? '';
$type     = $_POST['type'] ?? '';
$created  = date('Y-m-d H:i:s');

try {
    $stmt = $conn->prepare("INSERT INTO works (user_id, title, category, content, type, created_at, status)
                            VALUES (:user_id, :title, :category, :content, :type, :created_at, 'pending')");

    $stmt->execute([
        ':user_id'    => $user_id,
        ':title'      => $title,
        ':category'   => $category,
        ':content'    => $content,
        ':type'       => $type,
        ':created_at' => $created
    ]);

    header("Location: ../views/success_upload.html");
    exit;

} catch (PDOException $e) {
    echo "Upload error: " . $e->getMessage();
}
