<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Admins only.");
}

if (!isset($_GET['user_id'])) {
    die("User ID is missing.");
}

$user_id = $_GET['user_id'];

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    header("Location: dashboard.php?message=User deleted");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
