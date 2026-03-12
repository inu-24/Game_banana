<?php
session_start();

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

        /* Daily Challenge - fixed top right under navbar */
        .daily-challenge-corner {
            position: fixed;
            top: 90px;
            right: 30px;
            z-index: 998;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
        }

        .daily-label {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.7);
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .daily-btn {
            padding: 12px 22px;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            background: linear-gradient(135deg, #f7971e, #ffd200);
            color: #1a1a1a;
            transition: 0.3s ease;
            box-shadow: 0 4px 18px rgba(255,210,0,0.45);
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .daily-btn:hover {
            transform: scale(1.06);
            box-shadow: 0 6px 25px rgba(255,210,0,0.65);
        }

        .daily-btn:disabled {
            background: linear-gradient(135deg, #666, #999);
            color: #ddd;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        /* Pulsing glow animation to catch attention */
        @keyframes pulse-glow {
            0%   { box-shadow: 0 4px 18px rgba(255,210,0,0.45); }
            50%  { box-shadow: 0 4px 28px rgba(255,210,0,0.85); }
            100% { box-shadow: 0 4px 18px rgba(255,210,0,0.45); }
        }

        .daily-btn:not(:disabled) {
            animation: pulse-glow 2s ease-in-out infinite;
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

<div class="daily-challenge-corner">
    <span class="daily-label">Today's Challenge</span>
    <button class="daily-btn" id="dailyBtn" onclick="gotoDailyChallenge()">
        🌟 Daily Challenge
    </button>
</div>

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

// Check if daily challenge already completed today
function checkDailyChallenge() {
    let lastPlayed = localStorage.getItem("dailyChallengeDate");
    let today = new Date().toDateString();
    let btn = document.getElementById("dailyBtn");
    if (lastPlayed === today) {
        btn.disabled = true;
        btn.innerText = "✅ Done Today!";
    }
}

function gotoDailyChallenge() {
    location.href = "daily_challenge.php";
}

checkDailyChallenge();

// world clock 
function loadWorldClock(){
    fetch("https://timeapi.io/api/Time/current/zone?timeZone=Asia/Colombo")
    .then(response => response.json())
    .then(data => {
        document.getElementById("worldClock").innerText = data.time;
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