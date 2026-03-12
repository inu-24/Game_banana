<!-- Source :- use AI Tool(claude) -->

<?php
session_start();
require_once("db.php"); 

// If not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data from database (fullname, email, score, level)
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

$level_labels = ['Easy', 'Medium', 'Hard'];
$total_score  = (int)$user['total_score'];

// Determine level index purely based on score
if ($total_score >= 300) {
    $level_index = 2; // Hard
} elseif ($total_score >= 100) {
    $level_index = 1; // Medium
} else {
    $level_index = 0; // Easy
}

// Score boundaries for each level
$level_ranges = [
    0 => ['start' => 0,   'end' => 100],  // Easy:   0-99
    1 => ['start' => 100, 'end' => 300],  // Medium: 100-299
    2 => ['start' => 300, 'end' => 500],  // Hard:   300-499 (max range)
];

$level_start = $level_ranges[$level_index]['start'];
$level_end   = $level_ranges[$level_index]['end'];

// Name of next level (or 'MAX' if already at Hard)
$next_level = isset($level_labels[$level_index + 1])
              ? $level_labels[$level_index + 1]
              : 'MAX';

// Current level name (derived from score)
$current_level = $level_labels[$level_index];

// Calculate progress % within the current level range (0-100)
$progress_raw = ($total_score - $level_start) / ($level_end - $level_start) * 100;
$progress     = min(100, max(0, $progress_raw));

// Points still needed to reach the next level
$score_needed = max(0, $level_end - $total_score);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - Banana Math Puzzle</title>
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

    <!-- BACK ARROW: returns to home page -->
    <a href="home.php" class="back-arrow">
        <i class='bx bx-arrow-back'></i>
    </a>

    <h1 class="profile-title">👤 Profile</h1>

    <div class="profile-container">
        <div class="profile-card">

            <h2><?php echo htmlspecialchars($user['fullname']); ?></h2>

            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Total Score:</strong> <?php echo (int)$user['total_score']; ?></p>
            <p><strong>Current Level:</strong> <?php echo htmlspecialchars($current_level); ?></p>

            <button onclick="window.location.href='edit_profile.php'">
                Edit Profile
            </button>
            
            <div class="progress-section">

                <!-- Header: label on left, percentage on right -->
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

                <!-- Footer: points needed on left, % complete on right -->
                <div class="progress-footer">
                    <?php if ($next_level === 'MAX'): ?>
                        <span>🏆 Max Level Reached!</span>
                    <?php else: ?>
                        <span><?php echo $score_needed; ?> pts to <strong><?php echo $next_level; ?></strong></span>
                    <?php endif; ?>
                    <span><?php echo round($progress); ?>% complete</span>
                </div>

                <!-- Level milestone dots: Easy / Medium / Hard -->
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