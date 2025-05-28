<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $code = $_POST['country_code'];
  $phone = $_POST['phone'];
  $role = $_POST['role'];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email.");
  }

  try {
    $stmt = $pdo->prepare("INSERT INTO users (email, country_code, phone_number, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$email, $code, $phone, $role]);
    header("Location: ../views/success_signup.html");
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
} else {
  echo "Invalid request.";
}
