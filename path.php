<?php
session_start();

// Ensure PDO connection
include 'db.php'; // Ensure this includes the working PDO connection
include 'protect.php';
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
    <style>
.step {
  display: none;
}
.step.active {
  display: block;
}
.step input {
  background-color: #ffffff !important;
  color: #000 !important;
}
.button-container {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 30px;
}

.button-container button {
  background-color: #4a90e2;
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.button-container button:hover {
  background-color: #357ac8;
}

.button-container button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}
</style>


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
        
        <form action="#" class="w-350 d-sm-block d-none">
            <div class="position-relative">
                <button type="submit" class="input-icon text-xl d-flex text-gray-100 pointer-event-none"><i class="ph ph-magnifying-glass"></i></button> 
                <input type="text" class="form-control ps-40 h-40 border-transparent focus-border-main-600 bg-main-50 rounded-pill placeholder-15" placeholder="Search...">
            </div>
        </form>
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
        <li><span class="text-main-600 fw-normal text-15">Library</span></li>
    </ul>
</div>
<!-- Breadcrumb End -->


            <!-- Recommended Start -->
            <div class="card mt-24">
                <div class="card-body">

              
                    
                    <div class="row g-20">
                    <style>
  .form-control {
    background-color: #ffffff !important;
    border: 1px solid #ced4da;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 20px;
    font-size: 15px;
  }

  .form-control::placeholder {
    color: #999;
    font-style: italic;
  }

  label {
    font-weight: 600;
    margin-bottom: 5px;
    display: block;
  }

  .card {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
  }

  .btn-primary, .btn-success {
    font-weight: bold;
    padding: 12px;
    font-size: 16px;
    border-radius: 8px;
  }

  #resultText {
    white-space: pre-wrap;
    background-color: #fff;
    padding: 15px;
    border-radius: 10px;
    margin-top: 10px;
    border: 1px solid #ccc;
  }
  #careerResult {
  display: none;
  text-align: center;
  background-color: #eaf0ff;
  padding: 20px;
  border-radius: 10px;
  margin-top: 20px;
}

#resultText {
  text-align: left; /* Optional: keep content left-aligned for readability */
  max-width: 800px;
  margin: 0 auto;
  margin-top: 10px;
  line-height: 1.6;
}

#downloadBtn {
  margin-top: 20px;
  display: inline-block;
}
</style>

<div class="container-fluid">
  <div class="card border border-gray-100 w-100" style="border-radius: 16px; overflow: hidden; margin-top: 20px;">
    <div class="card-body p-4" style="background-color: #eaf0ff; height: auto;">

      <h2 class="text-center mb-4">Career Path Suggestion</h2>

      <form id="careerForm">

        <div><label for="subjects">Subjects of Interest</label>
          <input type="text" id="subjects" class="form-control" placeholder="e.g. Math, Technology, etc.">
        </div>

        <div><label for="hobbies">Hobbies and Activities</label>
          <input type="text" id="hobbies" class="form-control" placeholder="e.g. Playing sports, Coding, etc.">
        </div>

        <div><label for="topics">Curious Topics</label>
          <input type="text" id="topics" class="form-control" placeholder="e.g. AI, Space, etc.">
        </div>

        <div><label for="workEnvironment">Preferred Work Environment</label>
          <input type="text" id="workEnvironment" class="form-control" placeholder="e.g. Team, Remote, etc.">
        </div>

        <div><label for="impact">Desired Impact</label>
          <input type="text" id="impact" class="form-control" placeholder="e.g. Helping people, Advancing technology, etc.">
        </div>

        <div><label for="media">Preferred Media</label>
          <input type="text" id="media" class="form-control" placeholder="e.g. Documentaries, News, etc.">
        </div>

        <div><label for="skills">What are you good at?</label>
          <input type="text" id="skills" class="form-control" placeholder="e.g. problem-solving, creativity, etc.">
        </div>

        <div><label for="skillsToDevelop">What skills do you want to develop?</label>
          <input type="text" id="skillsToDevelop" class="form-control" placeholder="e.g. coding, communication, etc.">
        </div>

        <div><label for="personalityTraits">Strongest Personality Traits</label>
          <input type="text" id="personalityTraits" class="form-control" placeholder="e.g. analytical, creative, etc.">
        </div>

        <div><label for="careerImportant">What is important to you in a career?</label>
          <input type="text" id="careerImportant" class="form-control" placeholder="e.g. salary, work-life balance, etc.">
        </div>

        <div><label for="workCulture">Preferred Work Culture</label>
          <input type="text" id="workCulture" class="form-control" placeholder="e.g. collaborative, independent, etc.">
        </div>

        <div><label for="educationLevel">Current Level of Education</label>
          <input type="text" id="educationLevel" class="form-control" placeholder="e.g. High School, College, etc.">
        </div>

        <div><label for="workExperience">Any Work/Internship Experience?</label>
          <input type="text" id="workExperience" class="form-control" placeholder="e.g. Previous internships or jobs">
        </div>

        <div><label for="furtherEducation">Further Education Plans</label>
          <input type="text" id="furtherEducation" class="form-control" placeholder="e.g. Bachelor's, Master's, etc.">
        </div>

        <div><label for="location">Where Do You Live?</label>
          <input type="text" id="location" class="form-control" placeholder="e.g. Delhi, New York, etc.">
        </div>

        <div><label for="resources">Available Resources</label>
          <input type="text" id="resources" class="form-control" placeholder="e.g. mentors, online courses, etc.">
        </div>

        <div class="button-container mt-4">
          <button type="submit" id="submitBtn" class="btn btn-primary w-100">Submit</button>
        </div>
      </form>

      <div id="careerResult" style="display:none;" class="mt-4">
        <h2>Suggested Career Path:</h2>
        <div id="resultText"></div>
        <button id="downloadBtn" class="btn btn-success mt-3" style="display:none;">Download PDF</button>
      </div>

    </div>
  </div>
</div>

<!-- JS Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("careerForm").addEventListener("submit", function (event) {
    event.preventDefault();

    const getVal = id => document.getElementById(id).value || "";

    const prompt = `Suggest a career path for a student with the following details. The response should be natural and friendly. Assume the user has completed Class 12 and is now looking for a future path. Give advice on what to study next, possible career options, and how to prepare. Do not include anything in bold:

Subjects of Interest: ${getVal("subjects")}
Hobbies and Activities: ${getVal("hobbies")}
Topics of Curiosity: ${getVal("topics")}
Preferred Work Environment: ${getVal("workEnvironment")}
Desired Impact: ${getVal("impact")}
Media Consumption: ${getVal("media")}
Skills: ${getVal("skills")}
Skills to Develop: ${getVal("skillsToDevelop")}
Personality Traits: ${getVal("personalityTraits")}
Important Career Aspects: ${getVal("careerImportant")}
Preferred Work Culture: ${getVal("workCulture")}
Current Education Level: ${getVal("educationLevel")}
Work/Internship Experience: ${getVal("workExperience")}
Further Education Plans: ${getVal("furtherEducation")}
Location: ${getVal("location")}
Available Resources: ${getVal("resources")}
`;

    const apiKey = "AIzaSyA18yz4KYnxdl7cTRSLT51cu7qMUPtOmKQ";
    const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${apiKey}`;

    const requestBody = {
      contents: [{ parts: [{ text: prompt }] }]
    };

    fetch(apiUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(requestBody),
    })
    .then(response => response.json())
    .then(data => {
      const resultText = data?.candidates?.[0]?.content?.parts?.[0]?.text;
      if (resultText) {
        document.getElementById("careerResult").style.display = "block";
        document.getElementById("resultText").textContent = resultText;
        document.getElementById("downloadBtn").style.display = "inline-block";

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: "portrait", unit: "mm", format: "a4" });

        doc.setFontSize(18);
        doc.setFont("helvetica", "bold");
        doc.text("Suggested Career Path", 105, 20, { align: "center" });

        doc.setFont("helvetica", "normal");
        doc.setFontSize(12);

        let y = 35;
        const splitText = doc.splitTextToSize(resultText, 180);
        splitText.forEach(line => {
          if (y > 270) {
            doc.addPage();
            y = 20;
          }
          doc.text(line, 15, y);
          y += 7;
        });

        document.getElementById("downloadBtn").onclick = () => {
          doc.save("Career_Path_Suggestion.pdf");
        };
      } else {
        alert("Something went wrong. Please try again.");
      }
    })
    .catch(error => {
      console.error("Error:", error);
      alert("Failed to fetch suggestion.");
    });
  });
});
</script>







                        
                        
                        
                       
                       
                        
                        
                    </div>

                    
                    
                </div>
            </div>
            <!-- Recommended End -->
        </div>

        <script>
            function checkMembership() {
                fetch('check_membership.php')
                .then(response => response.json())
                .then(data => {
                    if (data.membership === "active") {
                        window.location.href = "bot.php";
                    } else {
                        window.location.href = "pricing-plan.php";
                    }
                })
                .catch(error => console.error('Error:', error));
            }
            </script>

<script>
    function checkMembership1() {
        fetch('check_membership.php')
        .then(response => response.json())
        .then(data => {
            if (data.membership === "active") {
                window.location.href = "career.html";
            } else {
                window.location.href = "pricing-plan.php";
            }
        })
        .catch(error => console.error('Error:', error));
    }
    </script>

<script>
    function checkMembership2() {
        fetch('check_membership.php')
        .then(response => response.json())
        .then(data => {
            if (data.membership === "active") {
                window.location.href = "speech.php";
            } else {
                window.location.href = "pricing-plan.php";
            }
        })
        .catch(error => console.error('Error:', error));
    }
    </script>

<script>
    function checkMembership3() {
        fetch('check_membership.php')
        .then(response => response.json())
        .then(data => {
            if (data.membership === "active") {
                window.location.href = "image.php";
            } else {
                window.location.href = "pricing-plan.php";
            }
        })
        .catch(error => console.error('Error:', error));
    }
    </script>

<script>
    function checkMembership4() {
        fetch('check_membership.php')
        .then(response => response.json())
        .then(data => {
            if (data.membership === "active") {
                window.location.href = "ar.php";
            } else {
                window.location.href = "pricing-plan.php";
            }
        })
        .catch(error => console.error('Error:', error));
    }
    </script>






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
    // ========================== Range Slider Js Start =====================
    $(function() {
        $( "#slider-range" ).slider({
            range: true,
            min: 0,
            max: 480,
            values: [ 0, 240 ],
            slide: function( event, ui ) {
                $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
            }
        });
        $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
        " - $" + $( "#slider-range" ).slider( "values", 1 ) );
    });
    // ========================== Range Slider Js End =====================
    </script>
    </body>
</html>