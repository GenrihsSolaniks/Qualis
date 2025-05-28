<?php
require_once '../config/db.php';

$stmt = $conn->prepare("SELECT w.id, w.title, w.content, w.type, w.category, w.created_at, u.email AS author_email 
                        FROM works w
                        JOIN users u ON w.user_id = u.id
                        WHERE w.status = 'approved'
                        ORDER BY w.created_at DESC
                        LIMIT 10");
$stmt->execute();
$works = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($works);
