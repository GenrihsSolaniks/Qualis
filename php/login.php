<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Используем $conn, как указано в db.php
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        if ($role === 'Author') {
            header("Location: ../views/author_dashboard.php");
        } else {
            header("Location: ../views/user_dashboard.php");
        }
        exit();
    } else {
        echo "User with this role not found!";
    }
}
?>
