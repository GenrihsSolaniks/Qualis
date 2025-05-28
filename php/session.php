<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Author') {
  header("Location: ../views/login.html");
  exit;
}
