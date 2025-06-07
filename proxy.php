<?php
header("Content-Type: application/json");

$raw_data = file_get_contents("php://input");
file_put_contents("debug_log.txt", "Received: " . $raw_data . PHP_EOL, FILE_APPEND);

$data = json_decode($raw_data, true);

if (!$data || empty($data['userText'])) {
    echo json_encode(["error" => "Empty message"]);
    exit;
}

// Debugging API request
$apiKey = "AIzaSyBg00tPunb4J1oyXsAZ1ZUsbicu39i07F0";
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;

$postData = json_encode([
    "contents" => [["parts" => [["text" => $data['userText']]]]]
]);

file_put_contents("debug_log.txt", "Sent to API: " . $postData . PHP_EOL, FILE_APPEND);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

$response = curl_exec($ch);
file_put_contents("debug_log.txt", "API Response: " . $response . PHP_EOL, FILE_APPEND);
curl_close($ch);

if (!$response) {
    echo json_encode(["error" => "No response from API"]);
    exit;
}

echo $response;
?>
