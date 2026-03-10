<?php
session_start();

// If user not logged in → redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$fullname = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Banana Math Puzzle</title>
    <link rel="stylesheet" href="style.css">
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

<section class="section home-section">

    <h1 class="home-title">Banana Puzzle Game</h1>

    <div class="home-card">
        <h2>Welcome Back, <?php echo htmlspecialchars($fullname); ?>! 🍌</h2>
        <p>
            Train your brain with fun math challenges!
            Choose a section below to get started.
        </p>
        <div class="clock-box">
            🌍 Current Sri Lanka Time: <span id="worldClock">Loading...</span>
        </div>
        
        <div class="home-buttons">
            <button onclick="location.href='levels.php'">🎮 Levels</button>
            <button onclick="location.href='leaderboard.php'">🏆 Leaderboard</button>
            <button onclick="location.href='profile.php'">👤 Profile</button>
            <button onclick="location.href='settings.php'">⚙ Settings</button>
        </div>
    </div>

</section>

<script>
if(localStorage.getItem("darkMode") === "enabled"){
    document.body.classList.add("dark-mode");
}
function loadWorldClock(){

fetch("https://timeapi.io/api/Time/current/zone?timeZone=Asia/Colombo")
.then(response => response.json())
.then(data => {

let time = data.time;

document.getElementById("worldClock").innerText = time;

})
.catch(error => {
document.getElementById("worldClock").innerText = "Time unavailable";
});

}

loadWorldClock();
setInterval(loadWorldClock,10000);
</script>

</body>
</html>