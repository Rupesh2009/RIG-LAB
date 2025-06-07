<?php
session_start();

// Ensure PDO connection
include 'db.php'; // Ensure this includes the working PDO connection
global $pdo;

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: sign-in.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch user details including profile picture from the database using PDO
    $stmt = $pdo->prepare("SELECT username, email, membership_status, role, login_type, profile_pic FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("❌ Error: No user data found in DB.");
    }

    // Ensure the session is updated with the correct values
    $_SESSION['username'] = $user['username'] ?? 'Guest';
    $_SESSION['email'] = $user['email'] ?? 'Not Available';
    $_SESSION['membership_status'] = $user['membership_status'] ?? null;
    $_SESSION['role'] = $user['role'] ?? null;
    $_SESSION['profile_pic'] = $user['profile_pic'] ?? 'default1.png'; // Set default image

    // Assign updated values to variables
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $membership_status = $_SESSION['membership_status'];
    $role = $_SESSION['role'];
    $profile_pic = $_SESSION['profile_pic'];

} catch (PDOException $e) {
    die("❌ Query Error: " . $e->getMessage());
}

// 🔒 Add anti-inspect script for non-admin users
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
    <!-- Title -->
    <title> RIG LAB</title>
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

    <!-- ============================ Sidebar Start ============================ -->

<aside class="sidebar">
    <!-- sidebar close btn -->
     <button type="button" class="sidebar-close-btn text-gray-500 hover-text-white hover-bg-main-600 text-md w-24 h-24 border border-gray-100 hover-border-main-600 d-xl-none d-flex flex-center rounded-circle position-absolute"><i class="ph ph-x"></i></button>
    <!-- sidebar close btn -->
    
    <a href="index.php" class="sidebar__logo text-center p-20 position-sticky inset-block-start-0 bg-white w-100 z-1 pb-10">
        <img src="asser/images/logo/logo.png" alt="Logo">
    </a>

    <div class="sidebar-menu-wrapper overflow-y-auto scroll-sm">
        <div class="p-20 pt-10">
            <ul class="sidebar-menu">
                <li class="sidebar-menu__item">
                    <a href="dashboard.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-squares-four"></i></span>
                        <span class="text">Dashboard</span>
                       
                    </a>
                    <!-- Submenu start -->
                   
                    <!-- Submenu End -->
                </li>
               
                <li class="sidebar-menu__item">
                    <a href="library.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-books"></i></span>
                        <span class="text">Library</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="pricing-plan.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-coins"></i></span>
                        <span class="text">Pricing</span>
                    </a>
                </li>
                
                <li class="sidebar-menu__item">
                    <span class="text-gray-300 text-sm px-20 pt-20 fw-semibold border-top border-gray-100 d-block text-uppercase">Settings</span>
                </li>
                <li class="sidebar-menu__item">
                    <a href="setting.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-gear"></i></span>
                        <span class="text">Account Settings</span>
                    </a>
                </li>

                
                
            </ul>
        </div>
         <div class="p-20 pt-80">
                <div class="bg-main-50 p-20 pt-0 rounded-16 text-center mt-74">
                    <span class="border border-5 bg-white mx-auto border-primary-50 w-114 h-114 rounded-circle flex-center text-success-600 text-2xl translate-n74">
                        <img src="asser/images/icons/certificate.png" alt="" class="centerised-img">
                    </span>
                    <div class="mt-n74">
                        <h5 class="mb-4 mt-22">Get Pro Certificate</h5>
                        <p class="">Explore 400+ courses with lifetime members</p>
                        <a href="#"
                        class="btn btn-main mt-16 rounded-pill"
                        style="background-color: #3b82f6; color: #fff; padding: 12px 24px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2); border: none; transition: all 0.3s ease;"
                        onclick="document.getElementById('promoPopup').style.display='block'">
                        Get Access
                        </a>

                    </div>
                </div>
            </div>
        </div>
    
    </aside>   
    
    <div id="promoPopup" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); z-index:9999;">
  <div style="background:white; padding:30px; max-width:400px; margin:100px auto; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.2); text-align:center;">
    <h3 style="margin-bottom: 20px;">🎁 Apply Promo Code</h3>

    <input type="text" id="promoCode" class="form-control" placeholder="Enter promo code"
           style="margin: 10px 0; padding: 12px; width: 100%; border-radius: 8px; border: 1px solid #ccc; background: #fff;" />

    <button onclick="applyPromo()" style="background-color: #3b82f6; color: white; padding: 10px 20px; margin-right: 10px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background-color 0.3s;">
      ✅ Activate
    </button>

    <button onclick="document.getElementById('promoPopup').style.display='none'"
            style="background-color: #e5e7eb; color: #111827; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background-color 0.3s;">
      ❌ Close
    </button>

    <div id="promoMsg" style="margin-top: 16px; font-weight: bold;"></div>
  </div>
</div>

    

<script>
  const user = {
    name: "<?php echo $_SESSION['username'] ?? 'Guest'; ?>",
    email: "<?php echo $_SESSION['email'] ?? ''; ?>"
  };

  function openPromoPopup() {
    document.getElementById("popupName").innerText = user.name;
    document.getElementById("popupEmail").innerText = user.email;
    document.getElementById("promoPopup").style.display = "block";
    document.getElementById("promoMsg").innerText = "";
    document.getElementById("promoCode").value = "";
  }

  function applyPromo() {
    const code = document.getElementById("promoCode").value.trim();
    const messageBox = document.getElementById("promoMsg");

    if (!code) {
      messageBox.innerText = "❌ Please enter a promo code.";
      return;
    }

    fetch("update_status.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        email: user.email,
        code: code
      })
    })
    .then(res => res.json())
    .then(data => {
      messageBox.innerText = data.success ? "✅ " + data.message : "❌ " + data.message;
    })
    .catch(() => {
      messageBox.innerText = "❌ Something went wrong.";
    });
  }
</script>
<!-- ============================ Sidebar End  ============================ -->

    <div class="dashboard-main-wrapper">
        <div class="top-navbar flex-between gap-16">

    <div class="flex-align gap-16">
        <!-- Toggle Button Start -->
         <button type="button" class="toggle-btn d-xl-none d-flex text-26 text-gray-500"><i class="ph ph-list"></i></button>
        <!-- Toggle Button End -->
        
       
    </div>

    <div class="flex-align gap-16">
        <div class="flex-align gap-8">
                    
            
        </div>


        <!-- User Profile Start -->
        <div class="dropdown">
            <button class="users arrow-down-icon border border-gray-200 rounded-pill p-4 d-inline-block pe-40 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="position-relative">
                    <img src="profile_pics/<?php echo htmlspecialchars($profile_pic, ENT_QUOTES, 'UTF-8'); ?>" alt="Image" class="h-32 w-32 rounded-circle">
                    <span class="activation-badge w-8 h-8 position-absolute inset-block-end-0 inset-inline-end-0"></span>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
                <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                    <div class="card-body">
                        <div class="flex-align gap-8 mb-20 pb-20 border-bottom border-gray-100">
                        <img src="profile_pics/<?php echo htmlspecialchars($profile_pic, ENT_QUOTES, 'UTF-8'); ?>" alt="" class="w-54 h-54 rounded-circle">
                            <div class="">
                                <h4 class="mb-0"><?php echo htmlspecialchars(ucfirst($username), ENT_QUOTES, 'UTF-8'); ?></h4>
                                <p class="fw-medium text-13 text-gray-200"><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                            </div>
                        </div>
                        <ul class="max-h-270 overflow-y-auto scroll-sm pe-4">
                            <li class="mb-4">
                                <a href="setting.php" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                    <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-gear"></i></span>
                                    <span class="text">Account Settings</span>
                                </a>
                            </li>
                            <li class="mb-4">
                                <a href="pricing-plan.php" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                    <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-chart-bar"></i></span>
                                    <span class="text">Upgrade Plan</span>
                                </a>
                            </li>
                            
                            <li class="pt-8 border-top border-gray-100">
                                <a href="logout.php" class="py-12 text-15 px-20 hover-bg-danger-50 text-gray-300 hover-text-danger-600 rounded-8 flex-align gap-8 fw-medium text-15">
                                    <span class="text-2xl text-danger-600 d-flex"><i class="ph ph-sign-out"></i></span>
                                    <span class="text">Log Out</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- User Profile Start -->

    </div>
</div>
    

        
        <div class="dashboard-body">
            <!-- Breadcrumb Start -->
<div class="breadcrumb mb-24">
    <ul class="flex-align gap-4">
        <li><a href="index.php" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
        <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
        <li><span class="text-main-600 fw-normal text-15">Pricing Plan</span></li>
    </ul>
</div>
<!-- Breadcrumb End -->
             
            <div class="card mt-24">
    <div class="card-header border-bottom">
        <h4 class="mb-4">Pricing Breakdown</h4>
        <p class="text-gray-600 text-15">Creating a detailed pricing plan for your course requries considering various factors.</p>
    </div>
    <div class="card-body">
        <div class="row gy-4">
            <div class="col-md-4 col-sm-6">
                <div class="plan-item rounded-16 border border-gray-100 transition-2 position-relative">
                    <span class="text-2xl d-flex mb-16 text-main-600"><i class="ph ph-package"></i></span>
                    <h3 class="mb-4">Basic Plan</h3>
                    <span class="text-gray-600">Perfect plan for students</span>
                    <h2 class="h1 fw-medium text-main mb-32 mt-16 pb-32 border-bottom border-gray-100 d-flex gap-4">
                        $50 <span class="text-md text-gray-600">/year</span>
                    </h2>
                    <ul>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Intro video the course
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Interactive quizes 
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Course curriculum
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Community supports
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Certificate of completion
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Sample lesson showcasing
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Access to course community
                        </li>
                    </ul>
                    <a href="#" class="btn btn-outline-main w-100 rounded-pill py-16 border-main-300 text-17 fw-medium mt-32">Get Started</a>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="plan-item rounded-16 border border-gray-100 transition-2 position-relative active">
                    <span class="plan-badge py-4 px-16 bg-main-600 text-white position-absolute inset-inline-end-0 inset-block-start-0 mt-8 text-15">Recommended</span>
                    <span class="text-2xl d-flex mb-16 text-main-600"><i class="ph ph-planet"></i></span>
                    <h3 class="mb-4">Standard Plan</h3>
                    <span class="text-gray-600">For users who want to do more</span>
                    <h2 class="h1 fw-medium text-main mb-32 mt-16 pb-32 border-bottom border-gray-100 d-flex gap-4">
                        $129 <span class="text-md text-gray-600">/year</span>
                    </h2>

                    <ul>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Intro video the course
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Interactive quizes 
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Course curriculum
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Community supports
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Certificate of completion
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Sample lesson showcasing
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Access to course community
                        </li>
                    </ul>
                    <a href="#" class="btn btn-main w-100 rounded-pill py-16 border-main-600 text-17 fw-medium mt-32">Get Started</a>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="plan-item rounded-16 border border-gray-100 transition-2 position-relative">
                    <span class="text-2xl d-flex mb-16 text-main-600"><i class="ph ph-trophy"></i></span>
                    <h3 class="mb-4">Premium Plan</h3>
                    <span class="text-gray-600">Your entire friends in one place</span>
                    <h2 class="h1 fw-medium text-main mb-32 mt-16 pb-32 border-bottom border-gray-100 d-flex gap-4">
                        $280 <span class="text-md text-gray-600">/year</span>
                    </h2>

                    <ul>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Intro video the course
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Interactive quizes 
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Course curriculum
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Community supports
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Certificate of completion
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4 mb-20">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Sample lesson showcasing
                        </li>
                        <li class="flex-align gap-8 text-gray-600 mb-lg-4">
                            <span class="text-24 d-flex text-main-600"><i class="ph ph-check-circle"></i></span>
                            Access to course community
                        </li>
                    </ul>
                    <a href="#" class="btn btn-outline-main w-100 rounded-pill py-16 border-main-300 text-17 fw-medium mt-32">Get Started</a>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label mb-8 h6 mt-32">Terms & Policy</label>
                <ul class="list-inside">
                    <li class="text-gray-600 mb-4">1. Set up multiple pricing levels with different features and functionalities to maximize revenue</li>
                    <li class="text-gray-600 mb-4">2. Continuously test different price points and discounts to find the sweet spot that resonates with your target audience</li>
                    <li class="text-gray-600 mb-4">3. Price your course based on the perceived value it provides to students, considering factors</li>
                </ul>
               
            </div>
        </div>
    </div>
</div>
           
        </div>
        <div class="dashboard-footer">
    <div class="flex-between flex-wrap gap-16">
        <p class="text-gray-300 text-13 fw-normal"> &copy; Copyright RIG LAB 2024, All Right Reserverd</p>
        <div class="flex-align flex-wrap gap-16">
            
            <a href="#" class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">Support</a>
        </div>
    </div>
</div>
    </div>
        
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