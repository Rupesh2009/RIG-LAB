<?php
session_start();
require 'db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data['user_id'] ?? null;
$email = $data['email'] ?? null;
$login_type = $data['login_type'] ?? 'google'; // default to google if not sent

if (!$user_id || !$email) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Missing user ID or email"
    ]);
    exit;
}

$username = explode('@', $email)[0];
$password = ''; // Not used for OAuth
$membership_status = 'inactive';
$role = 'user';
$profile_pic = 'default3.png';

try {
    // ✅ Step 1: Check if user already exists
    $stmt = $pdo->prepare("SELECT id, login_type FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userRow) {
        $userId = $userRow['id'];

        // Optional: you can update the login_type if it's different
        if ($userRow['login_type'] !== $login_type) {
            $update = $pdo->prepare("UPDATE users SET login_type = ? WHERE id = ?");
            $update->execute([$login_type, $userId]);
        }
    } else {
        // ❌ New user — insert record
        $insert = $pdo->prepare("INSERT INTO users (username, email, password, login_type, membership_status, role, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert->execute([$username, $email, $password, $login_type, $membership_status, $role, $profile_pic]);
        $userId = $pdo->lastInsertId();
    }

    // ✅ Set session
    $_SESSION['user_id'] = $userId;
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $username;

    echo json_encode([
        "success" => true,
        "message" => "Login successful",
        "user_id" => $userId
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Server error: " . $e->getMessage()
    ]);
}
