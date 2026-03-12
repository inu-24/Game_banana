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

// Define level thresholds and calculate progress
$levels = ['Easy' => 0, 'Medium' => 100, 'Hard' => 300];
$level_labels = ['Easy', 'Medium', 'Hard'];
$current_level = $user['current_level'] ?? 'Easy';
$total_score = (int)$user['total_score'];

// Determine progress within current level
$level_index = array_search($current_level, $level_labels);
if ($level_index === false) $level_index = 0;

$level_start = array_values($levels)[$level_index];
$level_end = isset(array_values($levels)[$level_index + 1]) ? array_values($levels)[$level_index + 1] : $level_start + 200;
$next_level = isset($level_labels[$level_index + 1]) ? $level_labels[$level_index + 1] : 'MAX';

$progress_raw = ($total_score - $level_start) / ($level_end - $level_start) * 100;
$progress = min(100, max(0, $progress_raw));
$score_needed = max(0, $level_end - $total_score);

// Stats: count wins per level from scores table (if it exists)
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
    <h1 class="profile-title">👤 Profile</h1>

    <!-- PROFILE CARD -->
    <div class="profile-container">

        <div class="profile-card">

            <h2><?php echo htmlspecialchars($user['fullname']); ?></h2>

            <!-- Info rows (matching original style) -->
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Total Score:</strong> <?php echo (int)$user['total_score']; ?></p>
            <p><strong>Current Level:</strong> <?php echo htmlspecialchars($current_level); ?></p>

            <button onclick="window.location.href='edit_profile.php'">
                Edit Profile
            </button>

            <!-- Progress Bar (below button) -->
            <div class="progress-section">
                <div class="progress-header">
                    <span class="progress-label">
                        <i class='bx bx-trending-up'></i>
                        Level Progress
                    </span>
                    <span class="progress-percent"><?php echo round($progress); ?>%</span>
                </div>

                <div class="progress-bar-track">
                    <div class="progress-bar-fill" style="width: <?php echo $progress; ?>%">
                        <div class="progress-shine"></div>
                    </div>
                </div>

                <div class="progress-footer">
                    <?php if ($next_level === 'MAX'): ?>
                        <span>🏆 Max Level Reached!</span>
                    <?php else: ?>
                        <span><?php echo $score_needed; ?> pts to <strong><?php echo $next_level; ?></strong></span>
                    <?php endif; ?>
                    <span><?php echo round($progress); ?>% complete</span>
                </div>

                <!-- Level milestones -->
                <div class="level-milestones">
                    <?php foreach ($level_labels as $i => $lbl): ?>
                        <div class="milestone <?php echo ($i <= $level_index) ? 'reached' : ''; ?>">
                            <div class="milestone-dot"></div>
                            <span><?php echo $lbl; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

    </div>

</section>

</body>
</html>