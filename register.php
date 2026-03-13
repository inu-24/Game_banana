<!-- Source :- use AI Tool(claude) -->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");
include("send_verification_email.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname         = trim($_POST['fullname']);
    $email            = trim($_POST['email']);
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location='login.html';</script>";
        exit();
    }

    // Check password strength (minimum 6 characters)
    if (strlen($password) < 6) {
        echo "<script>alert('Password must be at least 6 characters long!'); window.location='login.html';</script>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.location='login.html';</script>";
        exit();
    }
    $stmt->close();

    // Generate a unique verification token
    $token        = bin2hex(random_bytes(32)); // 64-char secure token
    $token_expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

    // Insert new user — is_verified = 0 (not verified yet)
    $sql  = "INSERT INTO users (fullname, email, password, total_score, current_level, created_at,
                                is_verified, verification_token, token_expiry)
             VALUES (?, ?, ?, 0, 'Easy', NOW(), 0, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $fullname, $email, $hashed_password, $token, $token_expiry);

    if ($stmt->execute()) {

        // Try to send the verification email
        $sent = sendVerificationEmail($email, $fullname, $token);

        if ($sent) {
            // Email sent successfully
            echo "<script>
                    alert('Registration Successful! ✅\\nA verification email has been sent to: $email\\n\\nPlease check your inbox (and spam folder) and click the link to verify your account before logging in.');
                    window.location='login.html';
                  </script>";
        } else {
            // Email failed (SMTP not configured) — show the verify link directly on screen
            // so the user can still verify during development/testing
            $verifyLink = 'http://localhost/Game_SE/verify_email.php?token=' . urlencode($token);
            echo "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <title>Verify Your Account</title>
                <link rel='stylesheet' href='style.css'>
                <style>
                    .msg-wrapper { display:flex; justify-content:center; align-items:center; min-height:100vh; }
                    .msg-card    { background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2);
                                   backdrop-filter:blur(12px); border-radius:20px; padding:40px 48px;
                                   text-align:center; max-width:500px; width:90%; color:#fff; }
                    .msg-card h2 { color:#FFE135; margin-bottom:14px; }
                    .msg-card p  { color:rgba(255,255,255,0.85); font-size:15px; line-height:1.6; }
                    .verify-btn  { display:inline-block; margin-top:20px; padding:13px 36px;
                                   background:linear-gradient(135deg,#f7971e,#ffd200);
                                   color:#1a1a1a; font-weight:700; border-radius:50px;
                                   text-decoration:none; font-size:15px; }
                    .link-box    { background:rgba(0,0,0,0.2); border-radius:10px; padding:12px;
                                   word-break:break-all; font-size:12px; color:#FFE135;
                                   margin-top:16px; text-align:left; }
                </style>
            </head>
            <body>
            <div class='msg-wrapper'>
                <div class='msg-card'>
                    <div style='font-size:52px;'>🍌</div>
                    <h2>Account Created!</h2>
                    <p>Hi <strong>$fullname</strong>, your account was created successfully!</p>
                    <p>⚠️ The verification email could not be sent automatically.<br>
                       Please click the button below to verify your account directly:</p>
                    <a href='$verifyLink' class='verify-btn'>✅ Verify My Account Now</a>
                    <p style='margin-top:20px; font-size:13px; color:rgba(255,255,255,0.5);'>
                        Or copy this link into your browser:
                    </p>
                    <div class='link-box'>$verifyLink</div>
                </div>
            </div>
            </body>
            </html>";
        }

    } else {
        echo "Database Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>