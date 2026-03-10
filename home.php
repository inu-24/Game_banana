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
    <style>
        /* Floating clock at bottom center */
        .clock-box {
            position: fixed;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, rgba(0,0,0,0.55), rgba(0,0,0,0.35));
            border: 2px solid rgba(255,255,255,0.25);
            border-radius: 50px;
            padding: 10px 35px;
            display: flex;
            align-items: center;
            gap: 14px;
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.4), 0 0 20px rgba(255,220,50,0.15);
            z-index: 999;
            white-space: nowrap;
        }

        .clock-label {
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,0.75);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .clock-divider {
            width: 1px;
            height: 28px;
            background: rgba(255,255,255,0.25);
        }

        .clock-time {
            font-size: 28px;
            font-weight: 700;
            color: #FFE135;
            letter-spacing: 3px;
            text-shadow: 0 0 15px rgba(255,225,53,0.6);
            font-variant-numeric: tabular-nums;
        }
    </style>
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

        <div class="home-buttons">
            <button onclick="location.href='levels.php'">🎮 Levels</button>
            <button onclick="location.href='leaderboard.php'">🏆 Leaderboard</button>
            <button onclick="location.href='profile.php'">👤 Profile</button>
            <button onclick="location.href='settings.php'">⚙ Settings</button>
        </div>
    </div>

</section>

<!-- Floating Clock -->
<div class="clock-box">
    <div class="clock-label">🌍 Sri Lanka Time</div>
    <div class="clock-divider"></div>
    <div class="clock-time" id="worldClock">Loading...</div>
</div>

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
        document.getElementById("worldClock").innerText = "Unavailable";
    });
}

loadWorldClock();
setInterval(loadWorldClock, 10000);
</script>

</body>
</html>