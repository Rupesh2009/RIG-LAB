<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $text = $_POST["text"];

    // Ngrok endpoint (replace with your actual ngrok URL)
    $ngrok_url = "https://loved-quick-dory.ngrok-free.app/generate";

    // Data to send
    $data = ["text" => $text];

    // Initialize cURL session
    $ch = curl_init($ngrok_url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification if needed
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/x-www-form-urlencoded"
    ]);

    // Execute cURL request and get response
    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close cURL session
    curl_close($ch);

    // Handle response
    if ($http_code == 200 && $result !== false) {
        // Redirect to the generated video file directly
        echo "<script>
                alert('Video generated successfully!');
                window.location.href = 'sign_language_video.mp4';
              </script>";
    } else {
        echo "<script>alert('Error generating video. Try again!'); window.location.href='sign.php';</script>";
    }
} else {
    // Redirect to form if accessed directly
    header("Location: sign.php");
    exit();
}
?>
