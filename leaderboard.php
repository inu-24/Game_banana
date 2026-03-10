<?php
session_start();
require_once("db.php");

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
</head>

<body>

<header class="header">
    <nav class="navbar">
        <a href="home.php">Home</a>
        <a href="levels.php">Levels</a>
        <a href="leaderboard.php">Leaderboard</a>
        <a href="profile.php">Profile</a>
        <a href="settings.php">Settings</a>
    </nav>
</header>

<section class="section">

    <h1 class="leaderboard-title">🏆 Leaderboard</h1>

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