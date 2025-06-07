<?php
session_start();

// 🚫 Redirect if not logged in or not active
if (!isset($_SESSION['user_id']) || $_SESSION['membership_status'] !== 'active') {
    header("Location: pricing-plan.php");
    exit();
}

// 🔒 Inject anti-inspect script if not admin
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
