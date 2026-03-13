<!-- Source :- use AI Tool(claude) -->
<?php
session_start();
require_once("db.php");

// Allow guests to view leaderboard (read-only)
$is_guest = isset($_SESSION['is_guest']) && $_SESSION['is_guest'];
$is_logged_in = isset($_SESSION['user_id']);

// Fetch top 10 users ordered by highest score
$sql = "SELECT fullname, total_score 
        FROM users 
        ORDER BY total_score DESC 
        LIMIT 10";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard - Banana Math Puzzle</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .guest-banner {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: linear-gradient(90deg, #f7971e, #ffd200);
            color: #1a1a1a;
            text-align: center;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 700;
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
        }
        .guest-banner a {
            background: #1a1a1a;
            color: #FFE135;
            padding: 3px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 12px;
        }
        body.has-guest-banner .header { top: 36px; }
        .guest-session-card {
            background: linear-gradient(135deg, rgba(255,220,50,0.12), rgba(255,150,0,0.08));
            border: 1px solid rgba(255,220,50,0.35);
            border-radius: 14px;
            padding: 16px 28px;
            margin: 0 auto 24px;
            max-width: 560px;
            text-align: center;
            color: #FFE135;
        }
        .guest-session-card h3 { margin: 0 0 8px; font-size: 16px; }
        .guest-session-card p  { margin: 4px 0; font-size: 13px; color: rgba(255,255,255,0.7); }
        .guest-session-card .reg-btn {
            display: inline-block;
            margin-top: 10px;
            background: linear-gradient(135deg, #f7971e, #ffd200);
            color: #1a1a1a;
            font-weight: 700;
            padding: 8px 20px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 13px;
        }
    </style>
</head>

<body<?php echo $is_guest ? ' class="has-guest-banner"' : ''; ?>>

<?php if ($is_guest): ?>
<div class="guest-banner">
    👤 You are playing as a Guest
    <a href="login.html">Register / Login to save scores</a>
    <a href="logout.php">Exit</a>
</div>
<?php endif; ?>

<header class="header">
    <nav class="navbar">
        <a href="home.php">Home</a>
        <a href="levels.php">Levels</a>
        <a href="leaderboard.php">Leaderboard</a>
        <?php if (!$is_guest && $is_logged_in): ?>
        <a href="profile.php">Profile</a>
        <a href="settings.php">Settings</a>
        <?php endif; ?>
        <?php if ($is_logged_in): ?>
        <a href="logout.php">Logout</a>
        <?php endif; ?>
    </nav>
</header>

<section class="section">

    <h1 class="leaderboard-title">🏆 Leaderboard</h1>

    <?php if ($is_guest):
        $guest_total  = $_SESSION['guest_total_score'] ?? 0;
        $guest_games  = count($_SESSION['guest_scores'] ?? []);
    ?>
    <div class="guest-session-card">
        <h3>👤 Your Guest Session</h3>
        <p>⭐ Session Score: <strong><?php echo $guest_total; ?></strong> &nbsp;|&nbsp; 🎮 Games Played: <strong><?php echo $guest_games; ?></strong></p>
        <p>Your scores are not saved to the leaderboard as a guest.</p>
        <a href="login.html" class="reg-btn">📝 Register to Save Your Scores!</a>
    </div>
    <?php endif; ?>

    <div class="leaderboard-container">
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Player</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>

                <?php
                if ($result && $result->num_rows > 0) {

                    $rank = 1;

                    while ($row = $result->fetch_assoc()) {

                        $medal = "";
                        if ($rank == 1) $medal = " 🥇";
                        elseif ($rank == 2) $medal = " 🥈";
                        elseif ($rank == 3) $medal = " 🥉";

                        echo "<tr>
                                <td>{$rank}{$medal}</td>
                                <td>{$row['fullname']}</td>
                                <td>{$row['total_score']}</td>
                              </tr>";

                        $rank++;
                    }

                } else {
                    echo "<tr>
                            <td colspan='3'>No scores yet. Play the game!</td>
                          </tr>";
                }
                ?>

            </tbody>
        </table>
    </div>

</section>

</body>
</html>