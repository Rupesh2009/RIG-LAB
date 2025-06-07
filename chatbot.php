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

              
                    
                <div>
    <div class="dashboard-body">
        <div class="chart-wrapper d-flex flex-wrap gap-24">
                <div class="card chat-list">
                    <div class="card-header py-16 border-bottom border-gray-100">
                       <form class="position-relative d-flex align-items-center gap-2" onsubmit="handleCIDForm(event)">
                        <input
                          id="cid-input"
                          type="text"
                          class="form-control ps-44 h-44 border-gray-100 focus-border-main-600 rounded-pill placeholder-15"
                          placeholder="Enter CID to load..."
                          required
                        />
                        <button
                          type="submit"
                          class="btn btn-icon text-xl text-gray-600 position-absolute top-50 start-0 translate-middle-y ms-3"
                          aria-label="Load CID"
                        >
                          <i class="ph ph-magnifying-glass"></i>
                        </button>
                      </form>


                    </div>
                    <div class="card-body p-0">
                        <div class="chat-list-wrapper p-24 overflow-y-auto scroll-sm">
                            <div class="chat-list__item flex-between gap-8 cursor-pointer">
                                <div class="d-flex align-items-start gap-16">
                                   
                                   
                                </div>
    
                                <div class="dropdown flex-shrink-0">
                                   
                                    <div class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0">
                                        <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                                            <div class="card-body p-12">
                                              
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                            <div class="chat-list__item flex-between gap-8 cursor-pointer">
                                <div class="d-flex align-items-start gap-16">
                                    <div class="position-relative flex-shrink-0">
                                        <img src="asser/images/thumbs/avatar-img10.png" alt="" class="w-44 h-44 rounded-circle object-fit-cover flex-shrink-0">
                                        <span class="activation-badge w-12 h-12 border-2 position-absolute inset-block-end-0 inset-inline-end-0"></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="text-line-1 text-15 text-gray-400 fw-bold mb-0">CHATBOT</h6>
                                        <span class="text-line-1 text-13 text-gray-200">GEMINI POWERED CHATBOT</span>
                                    </div>
                                </div>
    
                              
                            </div>
                        </div>
                    </div>
                </div>
         
                <div class="card chat-box">
                    <div class="card-header py-16 border-bottom border-gray-100">
                        <div class="chat-list__item flex-between gap-8 cursor-pointer">
                            <div class="d-flex align-items-start gap-16">
                                <div class="position-relative flex-shrink-0">
                                    <img src="asser/images/thumbs/avatar-img1.png" alt="" class="w-40 h-40 rounded-circle object-fit-cover flex-shrink-0">
                                    <span class="activation-badge w-12 h-12 border-2 position-absolute inset-block-end-0 inset-inline-end-0"></span>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="text-line-1 text-15 text-gray-400 fw-bold mb-0">CHATBOT</h6>
                                    <span class="text-line-1 text-13 text-gray-200">Online</span>
                                </div>
                            </div>

                            <div class="flex-align gap-16">
                            
                                <div class="dropdown flex-shrink-0">
                                    <button class="text-gray-400 text-xl d-flex rounded-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ph-fill ph-dots-three-outline-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0">
                                        <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                                            <div class="card-body p-12">
                                                <div class="max-h-200 overflow-y-auto scroll-sm pe-8">
                                                    <ul>
                                                        <li class="mb-0">
                                                            <button id="clear-chat" type="button" class="delete-item-btn py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 w-100 rounded-8 fw-normal text-xs d-block text-start">
                                                            <span class="text"><i class="ph ph-x-circle"></i> All Clear</span>
                                                            </button>

                                                        </li>
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="chat-box" class="chat-box-item-wrapper overflow-y-auto scroll-sm p-24"></div>
                    </div>
                    <div class="card-footer border-top border-gray-100">
                       <form id="chat-form" class="flex-align gap-8 chat-box-bottom" onsubmit="handleFormSubmit(event)">
                        

                        <input id="user-input" type="text"
                            class="form-control h-48 border-transparent px-20 focus-border-main-600 bg-main-50 rounded-pill placeholder-15"
                            placeholder="Type your message..." />

                        

                        <button type="submit"
                            class="flex-shrink-0 submit-btn btn btn-main rounded-pill flex-align gap-4 py-15">
                            Submit <span class="d-flex text-md d-sm-flex d-none"><i class="ph-fill ph-paper-plane-tilt"></i></span>
                        </button>
                        </form>

                    </div>
                </div>
           </div>
             
        </div>
      
    </div>
        
        <!-- Jquery js -->
     <script>
  const API_KEY = "AIzaSyDx_ByAyeTR1PIW7s4fZrqs_lb7yAP1Ts4";
  const PINATA_JWT = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySW5mb3JtYXRpb24iOnsiaWQiOiI5ZmYzZjcyZC05NzdkLTQxNDYtYjA4Zi03YTQyYjQ5MzdhMGQiLCJlbWFpbCI6InJqaGE1OTUwMEBnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwicGluX3BvbGljeSI6eyJyZWdpb25zIjpbeyJkZXNpcmVkUmVwbGljYXRpb25Db3VudCI6MSwiaWQiOiJGUkExIn0seyJkZXNpcmVkUmVwbGljYXRpb25Db3VudCI6MSwiaWQiOiJOWUMxIn1dLCJ2ZXJzaW9uIjoxfSwibWZhX2VuYWJsZWQiOmZhbHNlLCJzdGF0dXMiOiJBQ1RJVkUifSwiYXV0aGVudGljYXRpb25UeXBlIjoic2NvcGVkS2V5Iiwic2NvcGVkS2V5S2V5IjoiZDhiYjQ0MDEyNzAwZjAyMDBkZjgiLCJzY29wZWRLZXlTZWNyZXQiOiJhN2IzY2IyN2EyODU5OWI2NmZkNDYyYmUyMGJiNDMzMjNkMzI2YjE1ZjcwNWYxYjkxMjYwYTJjMTYxM2U5NTZlIiwiZXhwIjoxNzgwNzMyNTYzfQ.M0OXAqcEq8tAyIu_BtEAt1RVya_XcT74D5CdaluCVCY";

  const chatBox = document.getElementById("chat-box");
  const userInput = document.getElementById("user-input");

  let recording = false;
  const recordedChats = [];

  document.addEventListener("DOMContentLoaded", () => {
    const saved = localStorage.getItem("chatMessages");
    if (saved) chatBox.innerHTML = saved;

    const clearBtn = document.getElementById("clear-chat");
    if (clearBtn) {
      clearBtn.addEventListener("click", () => {
        chatBox.innerHTML = "";
        localStorage.removeItem("chatMessages");
      });
    }
  });

  function handleFormSubmit(event) {
    event.preventDefault();
    sendMessage();
  }

  function handleCIDForm(event) {
    event.preventDefault();
    loadFromCID();
  }

  async function sendMessage() {
    const rawText = userInput.value.trim();
    userInput.value = "";
    if (!rawText) return;

    if (rawText === "/rec") {
      recording = true;
      appendMessage("System", "<b>🔴 Recording started.</b>", "bot-message");
      return;
    }

    if (rawText === "?rec") {
      recording = false;
      appendMessage("System", "<b>🟢 Recording stopped. Uploading...</b>", "bot-message");
      const cid = await saveLogToPinata();
      appendMessage("📦", `Saved to IPFS: <a href="https://ipfs.io/ipfs/${cid}" target="_blank" class="monospace">${cid}</a>`, "bot-message");
      return;
    }

    appendMessage("You", rawText, "user-message");
    appendMessage("Bot", "Typing...", "bot-message", "typing");

    try {
      const response = await fetch(
        `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${API_KEY}`,
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            contents: [{ parts: [{ text: rawText }] }]
          })
        }
      );

      const data = await response.json();
      let botReply = data.candidates?.[0]?.content?.parts?.[0]?.text || "No response.";
      botReply = formatText(botReply);

      removeTypingIndicator();
      appendMessage("Bot", botReply, "bot-message");

      if (recording) {
        recordedChats.push({
          question: rawText,
          answer: botReply.replace(/<[^>]+>/g, ""),
          timestamp: new Date().toISOString()
        });
      }
    } catch (error) {
      removeTypingIndicator();
      appendMessage("Bot", "❌ Error: " + error.message, "bot-message");
    }
  }

  function appendMessage(sender, message, className, id = "") {
    const isUser = className === "user-message";
    const msgWrapper = document.createElement("div");
    msgWrapper.className = `chat-box-item d-flex align-items-end gap-8${isUser ? " right" : ""}`;
    if (id) msgWrapper.id = id;

    msgWrapper.innerHTML = `
      <img src="asser/images/thumbs/avatar-img1.png" alt="" class="w-40 h-40 rounded-circle object-fit-cover flex-shrink-0">
      <div class="chat-box-item__content">
        <div class="chat-box-item__text py-16 px-16 px-lg-4">${message.replace(/\n/g, "<br><br>")}</div>
        <span class="text-gray-200 text-13 mt-2 d-block">${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
      </div>
    `;

    chatBox.appendChild(msgWrapper);
    chatBox.scrollTop = chatBox.scrollHeight;
    saveMessagesToLocalStorage();
  }

  function removeTypingIndicator() {
    const typing = document.getElementById("typing");
    if (typing) typing.remove();
  }

  function saveMessagesToLocalStorage() {
    localStorage.setItem("chatMessages", chatBox.innerHTML);
  }

  async function saveLogToPinata() {
    const blob = new Blob([JSON.stringify(recordedChats, null, 2)], { type: "application/json" });
    const file = new File([blob], "chatlog.json");

    const formData = new FormData();
    formData.append("file", file);

    const res = await fetch("https://api.pinata.cloud/pinning/pinFileToIPFS", {
      method: "POST",
      headers: {
        Authorization: `Bearer ${PINATA_JWT}`
      },
      body: formData
    });

    const result = await res.json();
    return result.IpfsHash;
  }

  async function loadFromCID() {
    const cidInput = document.getElementById("cid-input");
    const cid = cidInput.value.trim();

    if (!cid) {
      alert("Please enter a CID");
      return;
    }

    try {
      const res = await fetch(`https://ipfs.io/ipfs/${cid}`);
      const data = await res.json();

      if (!Array.isArray(data)) {
        throw new Error("Invalid CID format. Expected an array of chat entries.");
      }

      data.forEach(entry => {
        appendMessage("Loaded", `<b>Q:</b> ${entry.question}<br><b>A:</b> <span class="monospace">${entry.answer}</span>`, "bot-message");
      });

      chatBox.scrollTop = chatBox.scrollHeight;
    } catch (error) {
      appendMessage("Error", "❌ Could not load from CID: " + error.message, "bot-message");
    }
  }

  function formatText(text) {
  return text
    .replace(/\*+/g, "")                        // remove markdown *
    .replace(/\(pause\)/gi, "")                 // remove pause markers
    .replace(/\r\n/g, "\n")                     // normalize line breaks
    .replace(/\n{2,}/g, "<br><br>")             // paragraph breaks
    .replace(/\n/g, " ")                        // remove single line breaks
    .replace(/ {2,}/g, " ")                     // remove multiple spaces
    .trim();
}

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