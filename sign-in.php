<?php
session_start();
include 'db.php'; // Ensure this file correctly defines $pdo

// Check if the user is already logged in, if yes, redirect them to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // Fetch user data
        $query = "SELECT id, password, role, membership_status FROM users WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['membership_status'] = $user['membership_status'];

            // Redirect based on role
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// 🔒 Anti-inspect protection for non-admin users (applies before login, after login it's in dashboard)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo <<<EOD
<script>
// Disable right-click
document.addEventListener("contextmenu", e => e.preventDefault());

// Disable F12, Ctrl+Shift+I/J/C, Ctrl+U
document.addEventListener("keydown", function(e) {
    if (
        e.key === "F12" ||
        (e.ctrlKey && e.shiftKey && ["I", "J", "C"].includes(e.key)) ||
        (e.ctrlKey && e.key === "U")
    ) {
        e.preventDefault();
    }
});
</script>
EOD;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:title" content="Rig Lab | AI Problem Solver Platform">
    <meta property="og:description" content="AI-powered chatbot, quiz generator, and PDF solver for students. Smart tools for smarter learning.">
    <meta property="og:image" content="https://riglab.my.to/assets/preview.jpg"> <!-- use correct image path -->
    <meta property="og:url" content="https://riglab.my.to/dashboard">
    <meta property="og:type" content="website">
    <!-- Title -->
    <title>RIG LAB</title>
    <meta name="description" content="Use our AI-powered tools like quizzes and chatbots to simplify your learning experience.">

    <!-- Favicon -->
    <link rel="shortcut icon" href="asser/images/logo/favicon.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="asser/css/bootstrap.min.css">
    <!-- file upload -->
    <link rel="stylesheet" href="asser/css/file-upload.css">
    <!-- file upload -->
    <link rel="stylesheet" href="asser/css/plyr.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <!-- full calendar -->
    <link rel="stylesheet" href="asser/css/full-calendar.css">
    <!-- jquery Ui -->
    <link rel="stylesheet" href="asser/css/jquery-ui.css">
    <!-- editor quill Ui -->
    <link rel="stylesheet" href="asser/css/editor-quill.css">
    <!-- apex charts Css -->
    <link rel="stylesheet" href="asser/css/apexcharts.css">
    <!-- calendar Css -->
    <link rel="stylesheet" href="asser/css/calendar.css">
    <!-- jvector map Css -->
    <link rel="stylesheet" href="asser/css/jquery-jvectormap-2.0.5.css">
    <!-- Main css -->
    <link rel="stylesheet" href="asser/css/main.css">
</head> 
<body>
    
<!--==================== Preloader Start ====================-->
  <div class="preloader">
    <div class="loader"></div>
  </div>
<!--==================== Preloader End ====================-->

<!--==================== Sidebar Overlay End ====================-->
<div class="side-overlay"></div>
<!--==================== Sidebar Overlay End ====================-->


    <section class="auth d-flex">
        <div class="auth-left bg-main-50 flex-center p-24">
            <img src="asser/images/thumbs/auth-img1.png" alt="">
        </div>
        <div class="auth-right py-40 px-24 flex-center flex-column">
            <div class="auth-right__inner mx-auto w-100">
                <a href="index.php" class="auth-right__logo">
                    <img src="asser/images/logo/logo.png" alt="">
                </a>
                <h2 class="mb-8">Welcome to Back! &#128075;</h2>
                <p class="text-gray-600 text-15 mb-32">Please sign in to your account and start the adventure</p>

                <form id="login-form" method="POST">
                    <div class="mb-24">
                        <label for="email" class="form-label mb-8 h6">Email</label>
                        <div class="position-relative">
                            <input type="email" class="form-control py-11 ps-40" id="email" name="email" placeholder="Enter your email" required>
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex">
                                <i class="ph ph-user"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-24">
                        <label for="password" class="form-label mb-8 h6">Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control py-11 ps-40" id="password" name="password" placeholder="Enter Password" required>
                            <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash"></span>
                            <span class="position-absolute top-50 translate-middle-y ms-16 text-gray-600 d-flex">
                                <i class="ph ph-lock"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-32 flex-between flex-wrap gap-8">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input flex-shrink-0 rounded-4" type="checkbox" id="remember">
                            <label class="form-check-label text-15 flex-grow-1" for="remember">Remember Me</label>
                        </div>
                        <a href="forgot-password.php" class="text-main-600 hover-text-decoration-underline text-15 fw-medium">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-main rounded-pill w-100">Sign In</button>
                
                    <p class="mt-32 text-gray-600 text-center">New on our platform?
                        <a href="sign-up.php" class="text-main-600 hover-text-decoration-underline">Create an account</a>
                    </p>
                
                    <div class="divider my-32 position-relative text-center">
                        <span class="divider__text text-gray-600 text-13 fw-medium px-26 bg-white">or</span>
                    </div>

                    <ul class="flex-align gap-10 flex-wrap justify-content-center">
                        
                        
                        <li>
                           <a id="googleLoginBtn" href="#" class="w-38 h-38 flex-center rounded-6 text-google-600 bg-google-50 hover-bg-google-600 hover-text-white text-lg">
                                <i class="ph ph-google-logo"></i>
                            </a>
                        </li>
                        
                    </ul>
                    
                </form>
            </div>
        </div>
    </section>

    <!-- Firebase and Google Login Script -->

    <script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js";
  import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-auth.js";

  const firebaseConfig = {
    apiKey: "AIzaSyDorPOH04ffYcWPYsPgadLazgUIBD5XZJU",
    authDomain: "tech-bc947.firebaseapp.com",
    projectId: "tech-bc947",
    storageBucket: "tech-bc947.appspot.com",
    messagingSenderId: "25615355334",
    appId: "1:25615355334:web:7cab130087640a0b78b6c7",
    measurementId: "G-0F0VJB86H2"
  };

  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);
  const provider = new GoogleAuthProvider();
  provider.addScope('email');

  document.getElementById("googleLoginBtn").addEventListener("click", async (e) => {
    e.preventDefault();
    try {
      const result = await signInWithPopup(auth, provider);
      const user = result.user;

      if (!user || !user.uid || !user.email) {
        alert("Google sign-in failed: Missing UID or email.");
        return;
      }

      const response = await fetch("auth", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          user_id: user.uid,
          email: user.email,
          login_type: "google"
        })
      });

      const data = await response.json();

      if (data.success) {
        window.location.href = "dashboard";
      } else {
        alert("Login failed: " + data.message);
      }

    } catch (error) {
      alert("Google sign-in failed: " + error.message);
    }
  });
</script>




    


    </script>
        <!-- Jquery js -->
    <script src="asser/js/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="asser/js/boostrap.bundle.min.js"></script>
    <!-- Phosphor Js -->
    <script src="asser/js/phosphor-icon.js"></script>
    <!-- file upload -->
    <script src="asser/js/file-upload.js"></script>
    <!-- file upload -->
    <script src="asser/js/plyr.js"></script>
    <!-- dataTables -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <!-- full calendar -->
    <script src="asser/js/full-calendar.js"></script>
    <!-- jQuery UI -->
    <script src="asser/js/jquery-ui.js"></script>
    <!-- jQuery UI -->
    <script src="asser/js/editor-quill.js"></script>
    <!-- apex charts -->
    <script src="asser/js/apexcharts.min.js"></script>
    <!-- Calendar Js -->
    <script src="asser/js/calendar.js"></script>
    <!-- jvectormap Js -->
    <script src="asser/js/jquery-jvectormap-2.0.5.min.js"></script>
    <!-- jvectormap world Js -->
    <script src="asser/js/jquery-jvectormap-world-mill-en.js"></script>
    
    <!-- main js -->
    <script src="asser/js/main.js"></script>

</body>
</html>
