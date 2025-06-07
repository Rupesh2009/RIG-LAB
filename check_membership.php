<?php
session_start();
include 'db.php'; // Connect to the database

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['membership' => 'inactive']);
    exit;
}

// Query to check membership status
$query = $pdo->prepare("SELECT membership_status FROM users WHERE id = ?");
$query->execute([$user_id]);
$result = $query->fetch(PDO::FETCH_ASSOC);

if ($result && $result['membership_status'] === 'active') {
    echo json_encode(['membership' => 'active']);
} else {
    echo json_encode(['membership' => 'inactive']);
}
?>
