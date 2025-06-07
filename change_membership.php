<?php
session_start();
require 'db.php'; 

// Debug session data if access is denied
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<pre>";
    var_dump($_SESSION);
    echo "</pre>";
    die("Access denied. Admins only. Please log in again.");
}

// Validate user_id
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    die("Invalid User ID.");
}

$user_id = intval($_GET['user_id']); // Ensure it's an integer

try {
    // Get current membership status
    $stmt = $pdo->prepare("SELECT membership_status FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }

    // Toggle membership status
    $new_status = ($user['membership_status'] == 'active') ? 'inactive' : 'active';

    // Update membership status
    $update_stmt = $pdo->prepare("UPDATE users SET membership_status = ? WHERE id = ?");
    $update_stmt->execute([$new_status, $user_id]);

    // Redirect back to dashboard with a success message
    header("Location: dashboard.php?message=Membership updated");
    exit();
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage()); // Log error for debugging
    die("Something went wrong. Please try again later.");
}
?>
