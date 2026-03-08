<?php
session_start();
require_once("db.php"); // uses your DB connection

// If not logged in → go to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user data
$stmt = $conn->prepare("SELECT fullname, email, total_score, current_level FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - Banana Math Puzzle</title>

    <link rel="stylesheet" href="style.css">

    <!-- Boxicons -->
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

    <!-- TITLE -->
    <h1 class="profile-title">👤 Profile</h1>

    <!-- PROFILE CARD -->
    <div class="profile-container">

        <div class="profile-card">

            <h2><?php echo htmlspecialchars($user['fullname']); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Total Score:</strong> <?php echo (int)$user['total_score']; ?></p>
            <p><strong>Current Level:</strong> <?php echo htmlspecialchars($user['current_level']); ?></p>

            <button onclick="window.location.href='edit_profile.php'">
                Edit Profile
            </button>

        </div>

    </div>

</section>

<script>
// Dark mode support
if(localStorage.getItem("darkMode") === "enabled"){
    document.body.classList.add("dark-mode");
}
</script>

</body>
</html>