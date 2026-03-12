<?php
// ============================================================
// settings.php - Game Settings Page
// Allows user to toggle Sound, Music, Dark Mode and Logout
// ============================================================

session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "banana_game");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - Banana Math Puzzle</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
<header class="header">
    <nav class="navbar">
        <b><b><a href="home.php">Home</a></b></b>
        <b><b><a href="levels.php">Levels</a></b></b>
        <b><b><a href="leaderboard.php">Leaderboard</a></b></b>
        <b><b><a href="profile.php">Profile</a></b></b>
        <b><b><a href="settings.php">Settings</a></b></b>
        <b><b><a href="logout.php">Logout</a></b></b>
    </nav>
</header>

<section class="section">

    <a href="home.php" class="back-arrow">
        <i class='bx bx-arrow-back'></i>
    </a>

    <h1 class="settings-title">⚙ Settings</h1>

    <div class="settings-container">
        <div class="settings-card">
            <div class="settings-section-label">🎧 Audio</div>

            <div class="setting-item">
                <span><i class='bx bx-volume-full'></i> Sound Effects</span>
                <label class="switch">
                    <input type="checkbox" id="soundToggle" checked>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <span><i class='bx bx-music'></i> Background Music</span>
                <label class="switch">
                    <input type="checkbox" id="musicToggle" checked>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="settings-divider"></div>

            <div class="settings-section-label">🎨 Appearance</div>

            <!-- Dark Mode toggle -->
            <div class="setting-item">
                <span><i class='bx bx-moon'></i> Dark Mode</span>
                <label class="switch">
                    <input type="checkbox" id="darkModeToggle">
                    <span class="slider"></span>
                </label>
            </div>

            <div class="settings-divider"></div>

            <button class="logout-btn" onclick="logout()">
                <i class='bx bx-log-out'></i> Logout
            </button>

        </div>
    </div>
   
</section>

<script>

const toggle = document.getElementById("darkModeToggle");

if (localStorage.getItem("darkMode") === "enabled") {
    document.body.classList.add("dark-mode");
    toggle.checked = true; 
}

// Listen for dark mode toggle changes
toggle.addEventListener("change", function () {
    if (this.checked) {
        document.body.classList.add("dark-mode");
        localStorage.setItem("darkMode", "enabled"); 
    } else {
        document.body.classList.remove("dark-mode");
        localStorage.setItem("darkMode", "disabled"); 
    }
});


const soundToggle = document.getElementById("soundToggle");
const musicToggle = document.getElementById("musicToggle");

soundToggle.checked = localStorage.getItem("soundEffects") !== "disabled";
musicToggle.checked = localStorage.getItem("bgMusic") !== "disabled";

// Save sound effects preference when toggled
soundToggle.addEventListener("change", function () {
    localStorage.setItem("soundEffects", this.checked ? "enabled" : "disabled");
});

// Save background music preference when toggled
musicToggle.addEventListener("change", function () {
    localStorage.setItem("bgMusic", this.checked ? "enabled" : "disabled");
});

function logout() {
    fetch('logout.php').then(() => {
        window.location.href = 'login.html';
    });
}
</script>

</body>
</html>