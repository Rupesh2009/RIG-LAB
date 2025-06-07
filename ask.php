<?php
include 'protect.php';
$question = $_POST['question'] ?? '';
$pdf_text = $_POST['pdf_text'] ?? '';

// Send to Cloudways ask-api
$ch = curl_init('https://phpstack-1468728-5546886.cloudwaysapps.com/ask-api.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'question' => $question,
    'pdf_text' => $pdf_text
]));
curl_setopt($ch, CURLOPT_POST, true);
$response = curl_exec($ch);
curl_close($ch);

$json = json_decode($response, true);
$answer = $json['answer'] ?? '❌ Failed to retrieve answer from Cloudways.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RIG LAB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary: #2e86de;
            --primary-dark: #1b5fa6;
            --background: linear-gradient(135deg, #f2f7fc 0%, #e3ecf7 100%);
            --sidebar-bg: #1f1f2e;
            --card-bg: #ffffff;
            --highlight-bg: #f8f9fa;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --radius: 12px;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-dark);
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 240px;
            background-color: var(--sidebar-bg);
            color: #fff;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar h2 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 40px;
        }
        .nav-link {
            color: #b0b3c1;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: 20px;
            display: block;
            padding: 10px 15px;
            border-radius: var(--radius);
            transition: background 0.3s ease;
        }
        .nav-link:hover,
        .nav-link.active {
            background-color: #2e86de44;
            color: #fff;
        }
        .sidebar-footer {
            font-size: 12px;
            color: #999;
        }
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .header {
            padding: 30px 60px 10px;
            font-size: 24px;
            font-weight: 600;
            color: var(--primary);
            border-bottom: 1px solid #dfe6ed;
            background-color: #fff;
        }
        .content {
            flex: 1;
            padding: 40px 60px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .card {
            width: 100%;
            max-width: 700px;
            background-color: var(--card-bg);
            padding: 40px;
            border-radius: var(--radius);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
            animation: fadeIn 0.5s ease-out;
        }
        h3 {
            margin-top: 0;
            font-size: 24px;
            color: var(--primary);
        }
        .note {
            font-size: 14px;
            color: var(--text-light);
            margin-top: -10px;
            margin-bottom: 25px;
        }
        p {
            white-space: pre-line;
        }
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .header { padding: 20px; }
            .content { padding: 20px; }
            .card { padding: 25px; }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div>
        <h2>📘 RIG LAB</h2>
        <a class="nav-link" href="dashboard.php">📊 Dashboard</a>
        <a class="nav-link active" href="library.php">📚 Library</a>
        <a class="nav-link" href="pricing-plan.php">💰 Pricing</a>
        <a class="nav-link" href="setting.php">👤 Account Settings</a>
    </div>
    <div class="sidebar-footer">v1.0 | Made with ❤️</div>
</div>

<div class="main">
    <div class="header">AI PROBLEM SOLVER</div>
    <div class="content">
        <div class="card">
            <h3>💬 Answer to Your Question</h3>
            <p><strong>Question:</strong> <?= htmlspecialchars($question) ?></p>
            <hr>
            <p><?= $answer ?></p>
        </div>
    </div>
</div>
</body>
</html>
