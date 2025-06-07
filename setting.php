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
    $stmt = $pdo->prepare("
        SELECT username, email, membership_status, role, profile_pic 
        FROM users 
        WHERE id = ?
    ");
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

// 🔒 Anti-inspect script for non-admin users
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

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Preserve username
    $username = $_SESSION['username'];

    // Fetch and sanitize input
    $first_name = trim($_POST['fname'] ?? '');
    $last_name = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $zip_code = trim($_POST['zip'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $role = trim($_POST['role'] ?? '');

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("❌ Invalid email format.");
    }

    // Handle profile picture upload
    $profile_pic = $_SESSION['profile_pic']; // Default to existing image

    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "profile_pics/";
        $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 2 * 1024 * 1024; // 2MB limit

        if (!in_array($file_extension, $allowed_extensions)) {
            die("❌ Invalid file format. Only JPG, JPEG, PNG & GIF allowed.");
        }

        if ($_FILES["profile_pic"]["size"] > $max_file_size) {
            die("❌ File size exceeds 2MB limit.");
        }

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $profile_pic = $file_name;
        } else {
            die("❌ Error uploading file.");
        }
    }

    try {
        // Update user details (excluding username)
        $stmt = $pdo->prepare("
            UPDATE users SET 
                first_name = :first_name, 
                last_name = :last_name,
                email = :email, 
                phone = :phone,
                zip_code = :zip_code,
                bio = :bio,
                role = :role, 
                profile_pic = :profile_pic
            WHERE id = :user_id
        ");
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':phone' => $phone,
            ':zip_code' => $zip_code,
            ':bio' => $bio,
            ':role' => $role,
            ':profile_pic' => $profile_pic,
            ':user_id' => $user_id
        ]);

        // Preserve updated values in session
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        $_SESSION['zip_code'] = $zip_code;
        $_SESSION['bio'] = $bio;
        $_SESSION['role'] = $role;
        $_SESSION['profile_pic'] = $profile_pic;

        // **Re-fetch username to avoid session loss**
        $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['username'] = $user['username'] ?? 'Guest';

        echo "<script>alert('✅ Profile updated successfully!'); window.location.href = 'setting.php';</script>";
        exit;

    } catch (PDOException $e) {
        die("❌ Database Error: " . $e->getMessage());
    }
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
            <!-- Notification Start -->
           


        <!-- User Profile Start -->
        <div class="dropdown">
            <button class="users arrow-down-icon border border-gray-200 rounded-pill p-4 d-inline-block pe-40 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="position-relative">
                    <img src="profile_pics/<?php echo !empty($_SESSION['profile_pic']) ? htmlspecialchars($_SESSION['profile_pic'], ENT_QUOTES, 'UTF-8') : 'default3.png'; ?>" alt="Image" class="h-32 w-32 rounded-circle">
                    <span class="activation-badge w-8 h-8 position-absolute inset-block-end-0 inset-inline-end-0"></span>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
                <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                    <div class="card-body">
                        <div class="flex-align gap-8 mb-20 pb-20 border-bottom border-gray-100">
                            <img src="profile_pics/<?php echo !empty($_SESSION['profile_pic']) ? htmlspecialchars($_SESSION['profile_pic'], ENT_QUOTES, 'UTF-8') : 'default3.png'; ?>" alt="" class="w-54 h-54 rounded-circle">
                            <div class="">
                            <h4 class="mb-0">
    <?php 
    echo !empty($username) 
        ? htmlspecialchars(ucfirst($username), ENT_QUOTES, 'UTF-8') 
        : "Guest"; 
    ?>
</h4>
                            <p class="fw-medium text-13 text-gray-200"><?php echo htmlspecialchars($_SESSION['email'] ?? 'No Email Provided', ENT_QUOTES, 'UTF-8'); ?>  
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
        <li><span class="text-main-600 fw-normal text-15">Setting</span></li>
    </ul>
</div>
<!-- Breadcrumb End -->
             
            <div class="card overflow-hidden">
                <div class="card-body p-0">
                    <div class="cover-img position-relative">
                        
                        <div class="avatar-upload">
                            <input type='file' id="coverImageUpload" accept=".png, .jpg, .jpeg">
                            <div class="avatar-preview">
                                <div id="coverImagePreview" style="background-image: url('asser/images/thumbs/setting-cover-img.png');">
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div class="setting-profile px-24">
                        <div class="flex-between">
                            <div class="d-flex align-items-end flex-wrap mb-32 gap-24">
                            <img src="profile_pics/<?php echo !empty($_SESSION['profile_pic']) ? htmlspecialchars($_SESSION['profile_pic'], ENT_QUOTES, 'UTF-8') : 'default3.png'; ?>" alt="" class="w-120 h-120 rounded-circle border border-white">
                                <div>
                                <h4 class="mb-8"><?php echo !empty($username) ? htmlspecialchars(ucfirst($username), ENT_QUOTES, 'UTF-8') : "User"; ?></h4>
                                    <p class="fw-medium text-13 text-gray-200"><?php echo htmlspecialchars($_SESSION['email'] ?? 'No Email Provided', ENT_QUOTES, 'UTF-8'); ?>  
                                    <div class="setting-profile__infos flex-align flex-wrap gap-16">
                                
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="nav common-tab style-two nav-pills mb-0" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="pills-details-tab" data-bs-toggle="pill" data-bs-target="#pills-details" type="button" role="tab" aria-controls="pills-details" aria-selected="true">My Details</button>
                            </li>
                            
                            
                            <li class="nav-item" role="presentation">
                              <button class="nav-link" id="pills-plan-tab" data-bs-toggle="pill" data-bs-target="#pills-plan" type="button" role="tab" aria-controls="pills-plan" aria-selected="false">Plan</button>
                            </li>
                           
                            
                        </ul>
                    </div>

                </div>
            </div>

            <div class="tab-content" id="pills-tabContent">
                <!-- My Details Tab start -->
                <div class="tab-pane fade show active" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab" tabindex="0">
                    <div class="card mt-24">
                        <div class="card-header border-bottom">
                            <h4 class="mb-4">My Details</h4>
                            <p class="text-gray-600 text-15">Please fill full details about yourself</p>
                        </div>
                        <div class="card-body">
                        <form action="setting.php" method="POST" enctype="multipart/form-data">
    <div class="row gy-4">
        <div class="col-sm-6">
            <label class="form-label h6">First Name</label>
            <input type="text" class="form-control" name="fname" value="<?= htmlspecialchars($_SESSION['first_name'] ?? '') ?>" required>
        </div>
        <div class="col-sm-6">
            <label class="form-label h6">Last Name</label>
            <input type="text" class="form-control" name="lname" value="<?= htmlspecialchars($_SESSION['last_name'] ?? '') ?>" required>
        </div>
        <div class="col-sm-6">
            <label class="form-label h6">Email</label>
            <input type="email" class="form-control" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" readonly>
            <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>">
        </div>
        <div class="col-sm-6">
            <label class="form-label h6">Phone Number</label>
            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($_SESSION['phone'] ?? '') ?>">
        </div>
        <div class="col-sm-6">
            <label class="form-label h6">Role</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['role'] ?? '') ?>" readonly>
            <input type="hidden" name="role" value="<?= htmlspecialchars($_SESSION['role'] ?? '') ?>">
        </div>
        <div class="col-sm-6">
            <label class="form-label h6">ZIP Code</label>
            <input type="text" class="form-control" name="zip" value="<?= htmlspecialchars($_SESSION['zip_code'] ?? '') ?>">
        </div>
        <div class="col-12">
            <label class="form-label h6">Bio</label>
            <textarea class="form-control" name="bio"><?= htmlspecialchars($_SESSION['bio'] ?? '') ?></textarea>
        </div>
        <div class="col-12">
            <label class="form-label h6">Profile Picture</label>
            <div class="profile-pic-container">
                <input type="file" name="profile_pic" id="imageUpload" accept=".png, .jpg, .jpeg">
                <div class="profile-pic-preview" style="background-image: url('profile_pics/<?= htmlspecialchars($_SESSION['profile_pic'] ?? 'default1.png') ?>');"></div>
            </div>
        </div>
        

        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
           
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</form>



                        </div>
                    </div>
                </div>
                <!-- My Details Tab End -->
                
                <!-- Profile Tab Start -->
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                    <div class="row gy-4">
                        <div class="col-lg-6">
                            <div class="card mt-24">
                                <div class="card-body">
                                    <h6 class="mb-12">About Me</h6>
                                    <p class="text-gray-600 text-15 rounded-8 border border-gray-100 p-16">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Commodo pellentesque massa tellus ac augue. Lectus arcu at in in rhoncus malesuada ipsum turpis.</p>
                                </div>
                            </div>
                            <div class="card mt-24">
                                <div class="card-body">
                                    <h6 class="mb-12">Recent Messages</h6>
                                    
                                    <div class="rounded-8 border border-gray-100 p-16 mb-16">
                                        <div class="comments-box__content flex-between gap-8">
                                            <div class="flex-align align-items-start gap-12">
                                                <img src="asser/images/thumbs/user-img1.png" class="w-32 h-32 rounded-circle object-fit-cover flex-shrink-0" alt="User Image">
                                                <div>
                                                    <h6 class="text-lg mb-8">Michel Smith</h6>
                                                    <p class="text-gray-600 text-15">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Commodo pellentesque massa </p>
                                                </div>
                                            </div>
                                            <button type="button" class="flex-shrink-0 fw-bold text-13 text-main-600 flex-align gap-8 hover-text-main-800">Reply <i class="ph ph-arrow-bend-up-left d-flex text-lg"></i> </button>
                                        </div>
                                    </div>

                                    <div class="rounded-8 border border-gray-100 p-16 mb-16">
                                        <div class="comments-box__content flex-between gap-8">
                                            <div class="flex-align align-items-start gap-12">
                                                <img src="asser/images/thumbs/user-img5.png" class="w-32 h-32 rounded-circle object-fit-cover flex-shrink-0" alt="User Image">
                                                <div>
                                                    <h6 class="text-lg mb-8">Zara Maliha</h6>
                                                    <p class="text-gray-600 text-15">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Commodo pellentesque massa </p>
                                                </div>
                                            </div>
                                            <button type="button" class="flex-shrink-0 fw-bold text-13 text-main-600 flex-align gap-8 hover-text-main-800">Reply <i class="ph ph-arrow-bend-up-left d-flex text-lg"></i> </button>
                                        </div>
                                    </div>

                                    <div class="rounded-8 border border-gray-100 p-16 mb-16">
                                        <div class="comments-box__content flex-between gap-8">
                                            <div class="flex-align align-items-start gap-12">
                                                <img src="asser/images/thumbs/user-img3.png" class="w-32 h-32 rounded-circle object-fit-cover flex-shrink-0" alt="User Image">
                                                <div>
                                                    <h6 class="text-lg mb-8">Simon Doe</h6>
                                                    <p class="text-gray-600 text-15">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Commodo pellentesque massa </p>
                                                </div>
                                            </div>
                                            <button type="button" class="flex-shrink-0 fw-bold text-13 text-main-600 flex-align gap-8 hover-text-main-800">Reply <i class="ph ph-arrow-bend-up-left d-flex text-lg"></i> </button>
                                        </div>
                                    </div>

                                    <div class="rounded-8 border border-gray-100 p-16 mb-16">
                                        <div class="comments-box__content flex-between gap-8">
                                            <div class="flex-align align-items-start gap-12">
                                                <img src="asser/images/thumbs/user-img4.png" class="w-32 h-32 rounded-circle object-fit-cover flex-shrink-0" alt="User Image">
                                                <div>
                                                    <h6 class="text-lg mb-8">Elejabeth Jenny</h6>
                                                    <p class="text-gray-600 text-15">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Commodo pellentesque massa </p>
                                                </div>
                                            </div>
                                            <button type="button" class="flex-shrink-0 fw-bold text-13 text-main-600 flex-align gap-8 hover-text-main-800">Reply <i class="ph ph-arrow-bend-up-left d-flex text-lg"></i> </button>
                                        </div>
                                    </div>

                                    <div class="rounded-8 border border-gray-100 p-16 mb-16">
                                        <div class="flex-between gap-8">
                                            <div class="flex-align align-items-start gap-12">
                                                <img src="asser/images/thumbs/user-img8.png" class="w-32 h-32 rounded-circle object-fit-cover flex-shrink-0" alt="User Image">
                                                <div>
                                                    <h6 class="text-lg mb-8">Ronald Doe</h6>
                                                    <p class="text-gray-600 text-15">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Commodo pellentesque massa </p>
                                                </div>
                                            </div>
                                            <button type="button" class="flex-shrink-0 fw-bold text-13 text-main-600 flex-align gap-8 hover-text-main-800">Reply <i class="ph ph-arrow-bend-up-left d-flex text-lg"></i> </button>
                                        </div>
                                    </div>

                                    <a href="#" class="flex-shrink-0 fw-bold text-13 text-main-600 flex-align gap-8 hover-text-main-800 hover-text-decoration-underline">
                                        View All <i class="ph ph-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card mt-24">
                                <div class="card-body">
                                    <h6 class="mb-12">Social Media</h6>
                                    <ul class="flex-align flex-wrap gap-8">
                                        <li>
                                            <a href="https://www.facebook.com" class="flex-center w-36 h-36 border border-main-600 text-main-600 rounded-circle text-xl hover-bg-main-100 hover-border-main-800"><i class="ph ph-facebook-logo"></i></a> 
                                        </li>
                                        <li>
                                            <a href="https://www.google.com" class="flex-center w-36 h-36 border border-main-600 text-main-600 rounded-circle text-xl hover-bg-main-100 hover-border-main-800"> <i class="ph ph-twitter-logo"></i></a>
                                        </li>
                                        <li>
                                            <a href="https://www.twitter.com" class="flex-center w-36 h-36 border border-main-600 text-main-600 rounded-circle text-xl hover-bg-main-100 hover-border-main-800"><i class="ph ph-linkedin-logo"></i></a>
                                        </li>
                                        <li>
                                            <a href="https://www.instagram.com" class="flex-center w-36 h-36 border border-main-600 text-main-600 rounded-circle text-xl hover-bg-main-100 hover-border-main-800"><i class="ph ph-instagram-logo"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card mt-24">
                                <div class="card-body">
                                    <div class="row gy-4">
                                        <div class="col-xxl-4 col-xl-6 col-md-4 col-sm-6">
                                            <div class="statistics-card p-xl-4 p-16 flex-align gap-10 rounded-8 bg-main-50">
                                                <span class="text-white bg-main-600 w-36 h-36 rounded-circle flex-center text-xl flex-shrink-0"><i class="ph ph-users-three"></i></span>
                                                <div>
                                                    <h4 class="mb-0">450k</h4>
                                                    <span class="fw-medium text-main-600">Followers</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-xl-6 col-md-4 col-sm-6">
                                            <div class="statistics-card p-xl-4 p-16 flex-align gap-10 rounded-8 bg-info-50">
                                                <span class="text-white bg-info-600 w-36 h-36 rounded-circle flex-center text-xl flex-shrink-0"><i class="ph ph-users-three"></i></span>
                                                <div>
                                                    <h4 class="mb-0">289k</h4>
                                                    <span class="fw-medium text-info-600">Following</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-xl-6 col-md-4 col-sm-6">
                                            <div class="statistics-card p-xl-4 p-16 flex-align gap-10 rounded-8 bg-purple-50">
                                                <span class="text-white bg-purple-600 w-36 h-36 rounded-circle flex-center text-xl flex-shrink-0"><i class="ph ph-thumbs-up"></i></span>
                                                <div>
                                                    <h4 class="mb-0">1256k</h4>
                                                    <span class="fw-medium text-purple-600">Likes</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-24">
                                        <div class="flex-align gap-8 flex-wrap mb-16">
                                            <span class="flex-center w-36 h-36 text-main-600 bg-main-100 rounded-circle text-xl"> 
                                                <i class="ph ph-phone"></i>
                                            </span>
                                            <div class="flex-align gap-8 flex-wrap text-gray-600">
                                                <span>+00 123 456 789</span>
                                                <span>+00 123 456 789</span>
                                            </div>
                                        </div>
                                        <div class="flex-align gap-8 flex-wrap mb-16">
                                            <span class="flex-center w-36 h-36 text-main-600 bg-main-100 rounded-circle text-xl"> 
                                                <i class="ph ph-envelope-simple"></i>
                                            </span>
                                            <div class="flex-align gap-8 flex-wrap text-gray-600">
                                                <span>exampleinfo1@mail.com,</span>
                                                <span>exampleinfo2@mail.com</span>
                                            </div>
                                        </div>
                                        <div class="flex-align gap-8 flex-wrap mb-16">
                                            <span class="flex-center w-36 h-36 text-main-600 bg-main-100 rounded-circle text-xl"> 
                                                <i class="ph ph-map-pin"></i>
                                            </span>
                                            <div class="flex-align gap-8 flex-wrap text-gray-600">
                                                <span>Inner Circular Road, New York City, 0123</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-24">
                                <div class="card-body">
                                    <h6 class="mb-12">About Me</h6>
                                    <div class="recent-post rounded-8 border border-gray-100 p-16 d-flex gap-12 mb-16">
                                        <div class="d-inline-flex w-100 max-w-130 flex-shrink-0">
                                            <img src="asser/images/thumbs/recent-post-img1.png" alt="" class="rounded-6 cover-img max-w-130">
                                        </div>
                                        <div>
                                            <p class="text-gray-600 text-line-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Commodo pellentesque massa tellus ac augue. Lectus arcu at in in rhoncus malesuada ipsum turpis.</p>
                                            <div class="flex-align gap-8 mt-24">
                                                <img src="asser/images/thumbs/user-img1.png" alt="" class="w-32 h-32 rounded-circle cover-img">
                                                <span class="text-gray-600 text-13">Michel Bruice</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="recent-post rounded-8 border border-gray-100 p-16 d-flex gap-12 mb-16">
                                        <div class="d-inline-flex w-100 max-w-130 flex-shrink-0">
                                            <img src="asser/images/thumbs/recent-post-img2.png" alt="" class="rounded-6 cover-img max-w-130">
                                        </div>
                                        <div>
                                            <p class="text-gray-600 text-line-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Commodo pellentesque massa tellus ac augue. Lectus arcu at in in rhoncus malesuada ipsum turpis.</p>
                                            <div class="flex-align gap-8 mt-24">
                                                <img src="asser/images/thumbs/user-img2.png" alt="" class="w-32 h-32 rounded-circle cover-img">
                                                <span class="text-gray-600 text-13">Sara Smith</span>
                                            </div>
                                        </div>
                                    </div>

                                    <h6 class="mb-12 mt-24">Add New Post</h6>
                                    <div class="editor style-two">
                                        <div id="editorTwo">
                                            <p>Write something new...</p>
                                        </div>
                                    </div>

                                    <div class="flex-between flex-wrap gap-8 mt-24">
                                        <div class="flex-align flex-wrap gap-8">
                                            <button type="button" class="flex-center w-26 h-26 text-gray-600 bg-gray-50 hover-bg-gray-100 rounded-circle text-md"> 
                                                <i class="ph ph-smiley"></i>
                                            </button>
                                            <button type="button" class="flex-center w-26 h-26 text-gray-600 bg-gray-50 hover-bg-gray-100 rounded-circle text-md"> 
                                                <i class="ph ph-camera"></i>
                                            </button>
                                            <button type="button" class="flex-center w-26 h-26 text-gray-600 bg-gray-50 hover-bg-gray-100 rounded-circle text-md"> 
                                                <i class="ph ph-image"></i>
                                            </button>
                                            <button type="button" class="flex-center w-26 h-26 text-gray-600 bg-gray-50 hover-bg-gray-100 rounded-circle text-md"> 
                                                <i class="ph ph-video-camera"></i>
                                            </button>
                                            <button type="button" class="flex-center w-26 h-26 text-gray-600 bg-gray-50 hover-bg-gray-100 rounded-circle text-md"> 
                                                <i class="ph ph-google-drive-logo"></i>
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-main rounded-pill py-9"> Post Now</button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Profile Tab End -->

               

                <!-- Plan Tab Start -->
                <div class="tab-pane fade" id="pills-plan" role="tabpanel" aria-labelledby="pills-plan-tab" tabindex="0">
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
                <button type="button" class="btn btn-main text-sm btn-sm px-24 rounded-pill py-12 d-flex align-items-center gap-2 mt-24" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="ph ph-plus me-4"></i>
                    Add New Plan 
                </button>
            </div>
        </div>
    </div>
</div>
                </div>
                <!-- Plan Tab End -->

                <!-- Billing Tab Start -->
                <div class="tab-pane fade" id="pills-billing" role="tabpanel" aria-labelledby="pills-billing-tab" tabindex="0">
                    <!-- Payment Method Start -->
                    <div class="card mt-24">
                        <div class="card-header border-bottom">
                            <h4 class="mb-4">Payment Method</h4>
                            <p class="text-gray-600 text-15">Update your billing details and address</p>
                        </div>
                        <div class="card-body">
                            <div class="row gy-4">
                                <div class="col-lg-5">
                                    <div class="card border border-gray-100">
                                        <div class="card-header border-bottom border-gray-100">
                                            <h6 class="mb-0">Contact Email</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="payment-method payment-method-one form-check form-radio d-flex align-items-center justify-content-between mb-16 rounded-16 bg-main-50 p-20 cursor-pointer position-relative transition-2">
                                                <div>
                                                    <h6 class="title mb-14">Send to my email account</h6>
                                                    <span class="d-block">exampleinfo@mail.com</span>
                                                </div>
                                                <label class="position-absolute inset-block-start-0 inset-inline-start-0 w-100 h-100 cursor-pointer" for="emailOne"></label>
                                                <input class="form-check-input payment-method-one" type="radio" name="emailCheck" id="emailOne">
                                            </div>
                                            <div class="payment-method payment-method-one form-check form-radio d-block rounded-16 bg-main-50 p-20 cursor-pointer position-relative transition-2 mt-24">
                                                <div class="flex-between  mb-14 gap-4">
                                                    <h6 class="title mb-0">Send to an alternative email</h6>
                                                    <input class="form-check-input payment-method-one" type="radio" name="emailCheck" id="emailTwo">
                                                </div>
                                                <label class="position-absolute inset-block-start-0 inset-inline-start-0 w-100 h-100 cursor-pointer" for="emailTwo"></label>
                                                <span class="border-text d-block bg-white border border-main-200 px-20 py-8 rounded-8">exampleinfo@mail.com</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="card border border-gray-100">
                                        <div class="card-header border-bottom border-gray-100 flex-between gap-8">
                                            <h6 class="mb-0">Card Details</h6>
                                            <a href="#" class="btn btn-outline-main rounded-pill py-6">Add New Card</a>
                                        </div>
                                        <div class="card-body">
                                            <div class="payment-method payment-method-two form-check form-radio d-flex align-items-center justify-content-between mb-16 rounded-16 bg-main-50 p-20 cursor-pointer position-relative transition-2">
                                                <div class="flex-align align-items-start gap-16">
                                                    <div>
                                                        <img src="asser/images/thumbs/payment-method1.png" alt="" class="w-54 h-40 rounded-8">
                                                    </div>
                                                    <div>
                                                        <h6 class="title mb-0">Visa **** **** 5890</h6>
                                                        <span class="d-block">Up to 60 User and 100GB team data</span>
                                                        <div class="text-13 flex-align gap-8 mt-12 pt-12 border-top border-gray-100">
                                                            <span>Set as default</span>
                                                            <a href="#" class="fw-bold">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label class="position-absolute inset-block-start-0 inset-inline-start-0 w-100 h-100 cursor-pointer" for="visaCard"></label>
                                                <input class="form-check-input payment-method-two" type="radio" name="cardDetails" id="visaCard">
                                            </div>
                                            <div class="payment-method payment-method-two form-check form-radio d-flex align-items-center justify-content-between rounded-16 bg-main-50 p-20 cursor-pointer position-relative transition-2">
                                                <div class="flex-align align-items-start gap-16">
                                                    <div>
                                                        <img src="asser/images/thumbs/payment-method2.png" alt="" class="w-54 h-40 rounded-8">
                                                    </div>
                                                    <div>
                                                        <h6 class="title mb-0">Mastercard **** **** 1895</h6>
                                                        <span class="d-block">Up to 60 User and 100GB team data</span>
                                                    </div>
                                                </div>
                                                <label class="position-absolute inset-block-start-0 inset-inline-start-0 w-100 h-100 cursor-pointer" for="masterCard"></label>
                                                <input class="form-check-input payment-method-two" type="radio" name="cardDetails" id="masterCard">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Payment Method End -->

                    <!-- Billing history Start -->
                    <div class="card mt-24">
                        <div class="card-header border-bottom">
                            <div class="flex-between flex-wrap  gap-16">
                                <div>
                                    <h4 class="mb-4">Billing History</h4>
                                    <p class="text-gray-600 text-15">See the transaction you made</p>
                                </div>
                                <div class="flex-align flex-wrap justify-content-end gap-8">
                                    <button type="button" class="toggle-search-btn btn btn-outline-main bg-main-100 border-main-100 text-main-600 rounded-pill py-9">Add Filter</button>
                                    <button type="button" class="btn btn-main rounded-pill py-9">Download All</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card toggle-search-box border-bottom border-gray-100 rounded-0">
                            <div class="card-body">
                                <form action="#" class="search-input-form">
                                    <div class="search-input">
                                        <select class="form-control form-select h6 rounded-4 mb-0 py-6 px-8">
                                            <option value="" selected disabled>Invoice Type</option>
                                            <option value="">Credit Invoice</option>
                                            <option value="">Debit Invoice</option>
                                            <option value="">Mixed Invoice</option>
                                            <option value="">Commercial Invoice</option>
                                        </select>
                                    </div>
                                    <div class="search-input">
                                        <select class="form-control form-select h6 rounded-4 mb-0 py-6 px-8">
                                            <option value="" selected disabled>amount</option>
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                        </select>
                                    </div>
                                    <div class="search-input">
                                        <input type="date" class="form-control form-select h6 rounded-4 mb-0 py-6 px-8">
                                    </div>
                                    <div class="search-input">
                                        <select class="form-control form-select h6 rounded-4 mb-0 py-6 px-8">
                                            <option value="" selected disabled>plan</option>
                                            <option value="">Basic Plan</option>
                                            <option value="">Standard Plan</option>
                                            <option value="">Premium Plan </option>
                                        </select>
                                    </div>
                                    <div class="search-input">
                                        <button type="submit" class="btn btn-main rounded-pill py-9 w-100">Apply Filter</button>
                                    </div>
                                </form>                    
                            </div>
                        </div>

                        <div class="card-body p-0 overflow-x-auto">
                            <table id="studentTable" class="table table-lg table-striped w-100">
                                <thead>
                                    <tr>
                                        <th class="fixed-width w-40 h-40 ps-20">
                                            <div class="form-check">
                                                <input class="form-check-input border-gray-200 rounded-4" type="checkbox" id="selectAll">
                                            </div>
                                        </th>
                                        <th class="h6 text-gray-600">
                                            <span class="position-relative">
                                                Invoices
                                            </span>
                                        </th>
                                        <th class="h6 text-gray-600 text-center">Amount</th>
                                        <th class="h6 text-gray-600 text-center">Dates</th>
                                        <th class="h6 text-gray-600 text-center">Status</th>
                                        <th class="h6 text-gray-600 text-center">Plan</th>
                                        <th class="h6 text-gray-600 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fixed-width w-40 h-40">
                                            <div class="form-check">
                                                <input class="form-check-input border-gray-200 rounded-4" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex-align gap-10">
                                                <div class="w-32 h-32 bg-gray-50 flex-center rounded-circle p-2"> 
                                                    <img src="asser/images/thumbs/invoice-logo1.png" alt="" class="">
                                                </div>
                                                <div class="">
                                                    <h6 class="mb-0">Design Accesibility</h6>
                                                    <span class="text-13 fw-medium text-gray-200">Edmate - #012500</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">$180</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">06/22/2024</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success-600 bg-success-100 py-2 px-10 rounded-pill">Paid</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">Basic</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-main bg-main-100 border-main-100 text-main-600 rounded-pill py-12">Download</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fixed-width w-40 h-40">
                                            <div class="form-check">
                                                <input class="form-check-input border-gray-200 rounded-4" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex-align gap-10">
                                                <div class="w-32 h-32 bg-gray-50 flex-center rounded-circle p-2"> 
                                                    <img src="asser/images/thumbs/invoice-logo2.png" alt="" class="">
                                                </div>
                                                <div class="">
                                                    <h6 class="mb-0">Design System</h6>
                                                    <span class="text-13 fw-medium text-gray-200">Edmate - #012500</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">$250</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">06/22/2024</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-info-600 bg-info-100 py-2 px-10 rounded-pill">Unpaid</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">Professional</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-main bg-main-100 border-main-100 text-main-600 rounded-pill py-12">Download</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fixed-width w-40 h-40">
                                            <div class="form-check">
                                                <input class="form-check-input border-gray-200 rounded-4" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex-align gap-10">
                                                <div class="w-32 h-32 bg-gray-50 flex-center rounded-circle p-2"> 
                                                    <img src="asser/images/thumbs/invoice-logo1.png" alt="" class="">
                                                </div>
                                                <div class="">
                                                    <h6 class="mb-0">Frondend Develop</h6>
                                                    <span class="text-13 fw-medium text-gray-200">Edmate - #012500</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">$128</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">06/22/2024</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success-600 bg-success-100 py-2 px-10 rounded-pill">Paid</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">Basic</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-main bg-main-100 border-main-100 text-main-600 rounded-pill py-12">Download</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fixed-width w-40 h-40">
                                            <div class="form-check">
                                                <input class="form-check-input border-gray-200 rounded-4" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex-align gap-10">
                                                <div class="w-32 h-32 bg-gray-50 flex-center rounded-circle p-2"> 
                                                    <img src="asser/images/thumbs/invoice-logo1.png" alt="" class="">
                                                </div>
                                                <div class="">
                                                    <h6 class="mb-0">Design Usability</h6>
                                                    <span class="text-13 fw-medium text-gray-200">Edmate - #012500</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">$132</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">06/22/2024</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-info-600 bg-info-100 py-2 px-10 rounded-pill">Unpaid</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">Basic</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-main bg-main-100 border-main-100 text-main-600 rounded-pill py-12">Download</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fixed-width w-40 h-40">
                                            <div class="form-check">
                                                <input class="form-check-input border-gray-200 rounded-4" type="checkbox">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex-align gap-10">
                                                <div class="w-32 h-32 bg-gray-50 flex-center rounded-circle p-2"> 
                                                    <img src="asser/images/thumbs/invoice-logo4.png" alt="" class="">
                                                </div>
                                                <div class="">
                                                    <h6 class="mb-0">Digital Marketing</h6>
                                                    <span class="text-13 fw-medium text-gray-200">Edmate - #012500</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">$186</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">06/22/2024</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-success-600 bg-success-100 py-2 px-10 rounded-pill">Paid</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-gray-600">Advance</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-main bg-main-100 border-main-100 text-main-600 rounded-pill py-12">Download</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer border-top border-gray-100">
                            <div class="flex-align justify-content-end gap-8">
                                <button type="reset" class="btn btn-outline-main bg-main-100 border-main-100 text-main-600 rounded-pill py-9">Cancel</button>
                                <button type="submit" class="btn btn-main rounded-pill py-9">Save  Changes</button>
                            </div>
                        </div>
                    </div>
                    <!-- Billing history End -->
                </div>
                <!-- Billing Tab End -->

                <!-- Notification Tab Start -->
                <div class="tab-pane fade" id="pills-notification" role="tabpanel" aria-labelledby="pills-notification-tab" tabindex="0">
                    <div class="card mt-24">
                        <div class="card-header border-bottom">
                            <h4 class="mb-4">Notifiction Settings</h4>
                            <p class="text-gray-600 text-15">We may still send you important notification about your account outside of your notification settings.</p>
                        </div>
                        <div class="card-body">
                            <div class="pt-24 pb-24 border-bottom border-gray-100">
                                <div class="row gy-4">
                                    <div class="col-sm-6 col-xs-6">
                                        <h6 class="mb-8">Comments</h6>
                                        <p class="max-w-280 text-gray-600 text-13">These are notifications for comments on your posts and replies to your comments</p>
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="form-switch switch-primary d-flex align-items-center gap-8 mb-16">
                                            <input class="form-check-input" type="checkbox" role="switch" id="switch1">
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch1">Push</label>
                                        </div>
                                        <div class="form-switch switch-primary d-flex align-items-center gap-8 mb-16">
                                            <input class="form-check-input" type="checkbox" role="switch" id="switch2" checked>
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch2">Email</label>
                                        </div>
                                        <div class="form-switch switch-primary d-flex align-items-center gap-8 mb-16">
                                            <input class="form-check-input" type="checkbox" role="switch" id="switch3" checked>
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch3">SMS</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-24 pb-24 border-bottom border-gray-100">
                                <div class="row gy-4">
                                    <div class="col-sm-6 col-xs-6">
                                        <h6 class="mb-8">Tags</h6>
                                        <p class="max-w-280 text-gray-600 text-13">These are notifications for when someone tags you in a comment, post or story</p>
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="form-switch switch-primary d-flex align-items-center gap-8 mb-16">
                                            <input class="form-check-input" type="checkbox" role="switch" id="switch4" checked>
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch4">Push</label>
                                        </div>
                                        <div class="form-switch switch-primary d-flex align-items-center gap-8 mb-16">
                                            <input class="form-check-input" type="checkbox" role="switch" id="switch5" >
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch5">Email</label>
                                        </div>
                                        <div class="form-switch switch-primary d-flex align-items-center gap-8 mb-16">
                                            <input class="form-check-input" type="checkbox" role="switch" id="switch6" >
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch6">SMS</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-24 pb-24 border-bottom border-gray-100">
                                <div class="row gy-4">
                                    <div class="col-sm-6 col-xs-6">
                                        <h6 class="mb-8">Reminders</h6>
                                        <p class="max-w-280 text-gray-600 text-13">These are notifications to reminds you of updates you might have missed.</p>
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="form-switch switch-primary d-flex align-items-center gap-8 mb-16">
                                            <input class="form-check-input" type="checkbox" role="switch" id="switch7" checked>
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch7">Push</label>
                                        </div>
                                        <div class="form-switch switch-primary d-flex align-items-center gap-8 mb-16">
                                            <input class="form-check-input" type="checkbox" role="switch" id="switch8">
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch8">Email</label>
                                        </div>
                                        <div class="form-switch switch-primary d-flex align-items-center gap-8 mb-16">
                                            <input class="form-check-input" type="checkbox" role="switch" id="switch9" checked>
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch9">SMS</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-24 border-bottom border-gray-100">
                                <div class="row gy-4">
                                    <div class="col-sm-6 col-xs-6">
                                        <h6 class="mb-8">More activity about you</h6>
                                        <p class="max-w-280 text-gray-600 text-13">These are notification for posts on your profile, likes and other reactions to your posts, and more.</p>
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="form-switch switch-primary d-flex align-items-center gap-8 mb-16">
                                            <input class="form-check-input" type="checkbox" role="switch" id="switch10" checked>
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch10">Push</label>
                                        </div>
                                        <div class="form-switch switch-primary d-flex align-items-center gap-8 mb-16">
                                            <input class="form-check-input" type="checkbox" role="switch" id="switch11" >
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch11">Email</label>
                                        </div>
                                        <div class="form-switch switch-primary d-flex align-items-center gap-8 mb-16">
                                            <input class="form-check-input" type="checkbox" role="switch" id="switch12" checked>
                                            <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="switch12">SMS</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="flex-align justify-content-end gap-8">
                                <button type="reset" class="btn btn-outline-main bg-main-100 border-main-100 text-main-600 rounded-pill py-9">Cancel</button>
                                <button type="submit" class="btn btn-main rounded-pill py-9">Save  Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Notification Tab End -->

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



    <script>
        // ============================= Avatar Upload js ============================= 
        function uploadImageFunction(imageId, previewId) {
            $(imageId).on('change', function () {
                var input = this; // 'this' is the DOM element here
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewId).css('background-image', 'url(' + e.target.result + ')');
                        $(previewId).hide();
                        $(previewId).fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
        }
        uploadImageFunction('#coverImageUpload', '#coverImagePreview'); 
        uploadImageFunction('#imageUpload', '#profileImagePreview');

        
        // ============================= Initialize Quill editor js Start ============================= 
        function editorFunction (editorId) {
            const quill = new Quill(editorId, {
                theme: 'snow'
            });
        }
        editorFunction('#editor'); 
        editorFunction('#editorTwo'); 
        // ============================= Initialize Quill editor js End ============================= 


        // Table Header Checkbox checked all js Start
        $('#selectAll').on('change', function () {
            $('.form-check .form-check-input').prop('checked', $(this).prop('checked')); 
        }); 
    
        // Data Tables
        new DataTable('#studentTable', {
            searching: false,
            lengthChange: false,
            info: false,   // Bottom Left Text => Showing 1 to 10 of 12 entries
            pagination: false,
            info: false,   // Bottom Left Text => Showing 1 to 10 of 12 entries
            paging: false,
            "columnDefs": [
                { "orderable": false, "targets": [0, 6] } // Disables sorting on the 1st & 7th column (index 6)
            ]
        });

    </script>

    </body>
</html>