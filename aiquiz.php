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
        h1 {
  text-align: center;
  color: #2c3e50;
}

.form-section,
.quiz-section {
  max-width: 700px;
  margin: 30px auto;
  background: #ffffff;
  padding: 25px;
  border-radius: 16px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.quiz-box {
  background: white;
  border-radius: 16px;
  padding: 40px;
  width: 95%;
  max-width: 1000px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.question {
  font-weight: 600;
  margin-bottom: 20px;
  font-size: 18px;
  line-height: 1.7;
  white-space: pre-wrap;
  overflow-wrap: break-word;
  word-wrap: break-word;
  max-width: 100%;
}

.options label {
  display: block;
  margin: 8px 0;
  padding: 12px 16px;
  border: 1px solid #ddd;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
}

.options label:hover {
  background-color: #f0f8ff;
}

.options input {
  margin-right: 10px;
}

.hidden {
  display: none;
}

#loadingSpinner {
  display: none;
  justify-content: center;
  align-items: center;
  margin-top: 15px;
}

.spinner {
  border: 6px solid #f3f3f3;
  border-top: 6px solid #007bff;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  display: none;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: #fff;
  border-radius: 12px;
  padding: 25px;
  max-width: 550px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}
.quiz-box {
  background: white;
  border-radius: 16px;
  padding: 40px;
  width: 95%;
  max-width: 1000px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  margin-top: 40px; /* 👈 This moves it down */
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
                    <div class="container-fluid">
  <div class="card border border-gray-100 w-100" style="border-radius: 16px; overflow: hidden; margin-top: 20px;">
    <div class="card-body p-0 d-flex justify-content-center align-items-start" style="background-color: #eaf0ff; min-height: 100vh; padding: 60px 0;">

      <!-- Quiz UI -->
      <div class="quiz-box">
        <h1 style="text-align: center; font-size: 28px; margin-bottom: 20px;">AI Powered Quiz</h1>

        <!-- Form Section -->
        <div class="form-section" style="display: flex; flex-direction: column; gap: 15px;">
          <input type="text" id="topic" placeholder="Enter Topic" required class="form-control" />
          <select id="class" required class="form-select">
            <option value="">Select Class</option>
            <option value="6">Class 6</option>
            <option value="7">Class 7</option>
            <option value="8">Class 8</option>
            <option value="9">Class 9</option>
            <option value="10">Class 10</option>
            <option value="11">Class 11</option>
            <option value="12">Class 12</option>
          </select>
          <select id="difficulty" required class="form-select">
            <option value="">Select Difficulty</option>
            <option value="easy">Easy</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
          </select>
          <select id="type" required class="form-select">
            <option value="">Select Question Type</option>
            <option value="very short">Very Short (2 marks)</option>
            <option value="short">Short (3 marks)</option>
            <option value="long">Long (5 marks)</option>
            <option value="case study">Case Study (6 marks)</option>
            <option value="mcq">Multiple Choice (1 mark)</option>
          </select>
          <select id="count" required class="form-select">
            <option value="">How Many Questions?</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="5">5</option>
            <option value="10">10</option>
          </select>
          <button onclick="startQuiz()" class="btn btn-primary">Start Quiz</button>
          <div id="loadingSpinner" style="text-align: center;">
            <div class="spinner-border text-primary d-none" id="spinner" role="status"></div>
          </div>
        </div>

        <!-- Quiz Section -->
        <div class="quiz-section d-none mt-4" id="quizSection">
          <div id="questionBox" class="mb-3" style="font-weight: 600;"></div>
          <div id="optionsBox" class="mb-3"></div>
          <textarea id="answerBox" placeholder="Write your answer here..." class="form-control mb-3"></textarea>
          <div style="text-align: right;">
            <button onclick="submitAnswer()" class="btn btn-success">Submit Answer</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal (add this at end of body if not already) -->
<!-- Modal -->
<div id="modal" class="modal" onclick="closeModal(event)">
  <div class="modal-content" id="modalContent" onclick="event.stopPropagation()">
    <div id="modalMessage" style="text-align: left; max-height: 70vh; overflow-y: auto;"></div>
    <button onclick="closeModal()" style="margin-top: 20px;" class="btn btn-primary">OK</button>
  </div>
</div>


<script>
  const API_KEY = "AIzaSyDx_ByAyeTR1PIW7s4fZrqs_lb7yAP1Ts4";
  let currentQuiz = [], currentIndex = 0, currentMarks = 0, currentType = "very short";
  let totalScore = 0, awaitingNext = false, quizFinished = false;

  function showModal(msg) {
    const formatted = msg.replace(/\*\*(.*?)\*\*/g, "<b>$1</b>");
    document.getElementById("modalMessage").innerHTML = formatted;
    document.getElementById("modal").style.display = "flex";
  }

  function closeModal(event = null) {
    const modal = document.getElementById("modal");
    if (!event || event.target === modal || event.target.tagName === "BUTTON") {
      modal.style.display = "none";

      if (awaitingNext) {
        currentIndex++;
        showQuestion();
        awaitingNext = false;
      } else if (quizFinished) {
        showModal(`🎉 Quiz complete!<br>Your total score: <b>${totalScore}</b> out of <b>${currentQuiz.length * currentMarks}</b>`);
        document.getElementById("quizSection").classList.add("hidden");
        quizFinished = false;
        totalScore = 0;
      }
    }
  }

  async function startQuiz() {
    const topic = document.getElementById("topic").value.trim();
    const cls = document.getElementById("class").value;
    const difficulty = document.getElementById("difficulty").value;
    const type = document.getElementById("type").value;
    const count = document.getElementById("count").value;
    const spinner = document.getElementById("spinner");

    if (!topic || !cls || !difficulty || !type || !count) {
      showModal("Please fill all fields before starting the quiz.");
      return;
    }

    spinner.classList.remove("d-none");
    document.getElementById("quizSection").classList.add("d-none");

    currentType = type;
    currentMarks = { "very short": 2, short: 3, long: 5, "case study": 6, "mcq": 1 }[type];

    const prompt = type === "mcq"
      ? `Generate ${count} multiple choice questions for class ${cls} on topic '${topic}' with ${difficulty} difficulty. Format: JSON array like [{ "question": "...", "options": ["A. Acid", "B. Base", "C. Salt", "D. Water"], "answer": "B" }]`
      : `Generate ${count} ${type} type questions for class ${cls} students on topic '${topic}' with ${difficulty} difficulty. Format: JSON array [{ "question": "..." }]`;

    try {
      const res = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${API_KEY}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ contents: [{ parts: [{ text: prompt }] }] })
      });

      const data = await res.json();
      let text = data.candidates?.[0]?.content?.parts?.[0]?.text || "[]";

      console.log("📥 Gemini raw output:", text);

      text = text.replace(/\*\*/g, "").replace(/```json|```/g, "").trim();

      currentQuiz = JSON.parse(text);
      if (!Array.isArray(currentQuiz) || currentQuiz.length === 0 || !currentQuiz[0].question) {
        throw new Error("Invalid quiz format from Gemini");
      }

      currentIndex = 0;
      showQuestion();
      document.getElementById("quizSection").classList.remove("d-none");
    } catch (e) {
      console.error("🚫 Error generating quiz:", e);
      showModal("Could not load quiz. Try again later.");
    } finally {
      spinner.classList.add("d-none");
    }
  }

  function showQuestion() {
    const q = currentQuiz[currentIndex];
    if (!q || !q.question) {
      showModal("⚠️ No valid question found.");
      return;
    }

    document.getElementById("questionBox").innerHTML = `<div class="question">${currentIndex + 1}. ${q.question}</div>`;
    document.getElementById("optionsBox").innerHTML = "";
    document.getElementById("answerBox").value = "";

    if (currentType === "mcq" && q.options) {
      const optionsHTML = q.options.map(opt => `
        <label><input type="radio" name="mcqOption" value="${opt}"> ${opt}</label>
      `).join("");
      document.getElementById("optionsBox").innerHTML = optionsHTML;
      document.getElementById("answerBox").style.display = "none";
    } else {
      document.getElementById("answerBox").style.display = "block";
    }
  }

  async function submitAnswer() {
    let userAnswer = "";
    const q = currentQuiz[currentIndex];

    if (currentType === "mcq") {
      const selected = document.querySelector('input[name="mcqOption"]:checked');
      if (!selected) return showModal("Please select an answer.");
      userAnswer = selected.value;

      const selectedIndex = q.options.findIndex(opt => opt === userAnswer);
      const correctIndex = ["A", "B", "C", "D"].indexOf(q.answer);
      const isCorrect = selectedIndex === correctIndex;

      totalScore += isCorrect ? currentMarks : 0;
      awaitingNext = currentIndex < currentQuiz.length - 1;
      quizFinished = !awaitingNext;

      const feedback = isCorrect
        ? `✅ <b>Correct!</b><br><b>Score:</b> ${currentMarks}/${currentMarks}`
        : `❌ <b>Incorrect.</b><br>Your answer: <b>${userAnswer}</b><br>Correct answer: <b>${q.options[correctIndex]}</b><br><b>Score:</b> 0/${currentMarks}`;

      showModal(feedback);
    } else {
      userAnswer = document.getElementById("answerBox").value.trim();
      if (!userAnswer) return showModal("Please enter an answer.");

      const evalPrompt = `Evaluate this student answer:\nQ: "${q.question}"\nA: "${userAnswer}"\nGive score out of ${currentMarks} and feedback.`;

      try {
        const res = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${API_KEY}`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ contents: [{ parts: [{ text: evalPrompt }] }] })
        });

        const data = await res.json();
        const reply = data.candidates?.[0]?.content?.parts?.[0]?.text || "Evaluation failed.";
        const scoreMatch = reply.match(/score.*?(\d+)/i);
        const score = scoreMatch ? parseInt(scoreMatch[1]) : 0;
        totalScore += score;

        awaitingNext = currentIndex < currentQuiz.length - 1;
        quizFinished = !awaitingNext;

        showModal(reply);
      } catch (e) {
        console.error(e);
        showModal("Evaluation failed. Try again.");
      }
    }
  }
</script>


<style>
/* Improve modal width handling for long Gemini feedback */
#modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0; top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  justify-content: center;
  align-items: center;
}

#modalContent {
  background: #fff;
  max-width: 90vw;
  max-height: 90vh;
  overflow-x: auto;
  overflow-y: auto;
  padding: 20px;
  border-radius: 10px;
}

#modalMessage {
  white-space: pre-wrap;
}
</style>







                        
                        
                        
                       
                       
                        
                        
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