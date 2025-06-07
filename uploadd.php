<?php
include 'protect.php';
// Razorhost upload.php - proxies PDF to Cloudways
$text = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cloudwaysUrl = 'https://phpstack-1468728-5546886.cloudwaysapps.com/upload-api.php';

    $postFields = [];
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        $postFields['pdf'] = new CURLFile($_FILES['pdf']['tmp_name'], 'application/pdf', $_FILES['pdf']['name']);
    } elseif (isset($_FILES['image_pdf']) && $_FILES['image_pdf']['error'] === UPLOAD_ERR_OK) {
        $postFields['image_pdf'] = new CURLFile($_FILES['image_pdf']['tmp_name'], 'application/pdf', $_FILES['image_pdf']['name']);
    }

    if (!empty($postFields)) {
        $ch = curl_init($cloudwaysUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        if ($result && isset($result['text'])) {
            $text = $result['text'];
        }
    }
}
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

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        input[type="file"],
        input[type="text"],
        textarea {
            padding: 14px 16px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: var(--radius);
            background-color: #fafafa;
            transition: border 0.2s ease;
        }

        input[type="file"]:focus,
        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
        }

        button {
            padding: 14px;
            background-color: var(--primary);
            color: #fff;
            font-size: 16px;
            font-weight: 500;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--primary-dark);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
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
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $text): ?>
                <h3>✅ PDF Processed</h3>
                <form method="POST" action="ask.php">
                    <input type="text" name="question" placeholder="Ask a question about this PDF..." required>
                    <textarea name="pdf_text" hidden><?= htmlspecialchars($text) ?></textarea>
                    <button type="submit">Ask</button>
                </form>
            <?php else: ?>
                <h3>📄 Upload a PDF</h3>
                <div class="note">Upload a standard PDF or scanned PDF (image-based).</div>
                <form method="POST" enctype="multipart/form-data">
                    <label><strong>Text-based PDF:</strong></label><br>
                    <input type="file" name="pdf" accept="application/pdf"><br><br>

                    

                    <button type="submit">Upload</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>