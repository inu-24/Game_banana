<!-- Source :- use AI Tool(claude) -->

<?php
include("db.php");

$success = "";
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);

    // Check email exists
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $error = "No account found with that email address.";
    } else {
        // Generate token and expiry
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Save token to database
        $sql = "UPDATE users SET reset_token=?, reset_expiry=? WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Build dynamic reset link (works on localhost AND live server)
        $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") 
                    . "://" . $_SERVER['HTTP_HOST'] 
                    . dirname($_SERVER['PHP_SELF']);
        $link = $base_url . "/reset_password.php?token=" . $token;

        $success = $link; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Banana Math Puzzle</title>
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
                <h2>Forgot Password</h2>

                <?php if ($error): ?>
                    <p style="color:red; text-align:center;"><?php echo $error; ?></p>
                <?php endif; ?>

                <?php if ($success): ?>
                    <p style="color:green; text-align:center;">Reset link generated! Click below:</p>
                    <p style="text-align:center; word-break:break-all;">
                        <a href="<?php echo $success; ?>"><?php echo $success; ?></a>
                    </p>
                <?php else: ?>
                    <form action="forgot_password.php" method="POST">

                        <div class="input-box">
                            <span class="icon"><i class="bx bx-envelope"></i></span>
                            <input type="email" name="email" required>
                            <label>Enter your Email</label>
                        </div>

                        <button type="submit" class="btn">Send Reset Link</button>

                        <div class="logreg-link">
                            <p>Remembered your password?
                                <a href="login.html">Login</a>
                            </p>
                        </div>

                    </form>
                <?php endif; ?>

            </div>
        </div>
    </div>

</section>

</body>
</html>