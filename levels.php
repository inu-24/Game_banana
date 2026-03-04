<?php
session_start();

// Protect page: if user not logged in → redirect to login page
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
    <title>Levels - Banana Math Puzzle</title>

    <!-- Main CSS -->
    <link rel="stylesheet" href="style.css">

    <!-- Boxicons for back arrow -->
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

    <!-- BACK ARROW -->
    <a href="home.php" class="back-arrow">
        <i class='bx bx-arrow-back'></i>
    </a>

    <!-- PAGE TITLE -->
    <h1 class="levels-title">Choose Your Level, <?php echo htmlspecialchars($fullname); ?>!</h1>

    <!-- LEVEL CARDS -->
    <div class="levels-container">

        <!-- EASY -->
        <div class="level-card">
            <h2>🍌 Easy</h2>
            <p>60 seconds to play</p>
            <p>Simple math equations</p>
            <p>Beginner friendly</p>
            <button onclick="location.href='game_easy.php?level=easy'">Play</button>
        </div>

        <!-- MEDIUM --> 
        <div class="level-card">
            <h2>🍌🍌 Medium</h2>
            <p>45 seconds to play</p>
            <p>Moderate math equations</p>
            <p>Faster timer</p>
            <button onclick="location.href='game_medium.php?level=medium'">Play</button>
        </div>

        <!-- HARD -->
        <div class="level-card">
            <h2>🍌🍌🍌 Hard</h2>
            <p>30 seconds to play</p>
            <p>Challenging math equations</p>
            <p>Very fast timer</p>
            <button onclick="location.href='game_hard.php?level=hard'">Play</button>
        </div>

    </div>

</section>

<script>
if(localStorage.getItem("darkMode") === "enabled"){
    document.body.classList.add("dark-mode");
}
</script>

</body>
</html>