<?php
session_start();

// Protect page: only logged-in users can access
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

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

            <!-- SOUND -->
            <div class="setting-item">
                <span>🔊 Sound Effects</span>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>

            <!-- MUSIC -->
            <div class="setting-item">
                <span>🎵 Background Music</span>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>

            <!-- LOGOUT BUTTON -->
            <button class="logout-btn" onclick="logout()">
                🚪 Logout
            </button>

        </div>

    </div>

</section>

<script>
const toggle = document.getElementById("darkModeToggle");

// Set dark mode on page load based on localStorage or database
if(localStorage.getItem("darkMode") === "enabled" || "<?php echo $darkMode; ?>" === "enabled"){
    document.body.classList.add("dark-mode");
    toggle.checked = true;
}

// Toggle Dark Mode
toggle.addEventListener("change", function(){
    if(this.checked){
        document.body.classList.add("dark-mode");
        localStorage.setItem("darkMode", "enabled");
    } else {
        document.body.classList.remove("dark-mode");
        localStorage.setItem("darkMode", "disabled");
    }

    // Optional: save preference to database via AJAX
    fetch('save_preferences.php', {
        method: 'POST',
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "dark_mode=" + (this.checked ? "enabled" : "disabled")
    });
});

// Logout function
function logout() {
    fetch('logout.php')
        .then(() => {
            window.location.href = 'login.html';
        });
}
</script>

</body>
</html>