<?php
include("db.php");

$token = $_GET['token'] ?? '';

$sql = "SELECT * FROM users WHERE reset_token=? AND reset_expiry > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$token);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    die("Invalid or expired token");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Banana Math Puzzle</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body>

<header class="header">
    <nav class="navbar">
        <div class="nav-left">
            <a href="home.php">Home</a>
        </div>
    </nav>
</header>

<section class="section">

    <h1 class="game-title">Banana Math Puzzle Game</h1>

    <div class="wrapper active-popup">
        <div class="logreg-box">
            <div class="form-box login">
                <h2>Reset Password</h2>

                <form action="update_password.php" method="POST">

                    <input type="hidden" name="token" value="<?php echo $token;?>">

                    <div class="input-box">
                        <span class="icon"><i class="bx bx-lock-alt"></i></span>
                        <input type="password" name="password" required>
                        <label>New Password</label>
                    </div>

                    <button type="submit" class="btn">Reset Password</button>

                    <div class="logreg-link">
                        <p>Remembered your password?
                            <a href="login.html">Login</a>
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <img src="monkey.png" class="monkey-img" alt="Monkey">

</section>

</body>
</html>