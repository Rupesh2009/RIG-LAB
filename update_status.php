<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');
$code = trim($data['code'] ?? '');

$validCodes = ['PROMO15', 'RIGLAB15', 'WELCOME15'];

if (!$email || !$code) {
    echo json_encode(["success" => false, "message" => "Missing email or promo code."]);
    exit;
}

if (!in_array($code, $validCodes)) {
    echo json_encode(["success" => false, "message" => "Invalid promo code."]);
    exit;
}

try {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id, membership_status, membership_expires_at FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["success" => false, "message" => "User not found."]);
        exit;
    }

    // Check if user is already active and still valid
    if (
        $user['membership_status'] === 'active' &&
        !empty($user['membership_expires_at']) &&
        strtotime($user['membership_expires_at']) > time()
    ) {
        echo json_encode(["success" => false, "message" => "Your account is already active and not expired."]);
        exit;
    }

    // Prevent repeated use of promo
    if (!empty($user['membership_expires_at'])) {
        echo json_encode(["success" => false, "message" => "Promo code already used once."]);
        exit;
    }

    // Set status active for 15 days
    $expiry = date('Y-m-d H:i:s', strtotime('+15 days'));

    $update = $pdo->prepare("UPDATE users SET membership_status = 'active', membership_expires_at = :expires WHERE email = :email");
    $update->execute([
        ':expires' => $expiry,
        ':email' => $email
    ]);

    echo json_encode(["success" => true, "message" => "✅ Account activated successfully for 15 days."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "❌ Database error."]);
}
