<?php
error_reporting(E_ALL & ~E_WARNING);
// send_verification_email.php
// Uses PHP built-in mail() — NO Composer needed.

function sendVerificationEmail(string $toEmail, string $toName, string $token): bool
{
    // ── CONFIG 
    $fromEmail = 'gunasekarainuli@gmail.com';    
    $baseUrl   = 'http://localhost/Game_SE';      
    

    $verifyLink = $baseUrl . '/verify_email.php?token=' . urlencode($token);

    // Email headers
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Banana Puzzle Game <" . $fromEmail . ">\r\n";
    $headers .= "Reply-To: " . $fromEmail . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    $subject = '=?UTF-8?B?' . base64_encode('Verify Your Banana Puzzle Game Account') . '?=';
    $body    = buildEmailHTML($toName, $verifyLink);

    $sent = mail($toEmail, $subject, $body, $headers);

    if (!$sent) {
        error_log("mail() failed sending verification to: $toEmail | Token: $token");
    }

    return $sent;
}

function buildEmailHTML(string $name, string $link): string
{
    return "
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset='UTF-8'>
      <style>
        body        { margin:0; padding:0; background:#1e3c72; font-family:Arial,sans-serif; }
        .container  { max-width:520px; margin:40px auto; background:rgba(255,255,255,0.08);
                      border:1px solid rgba(255,255,255,0.2); border-radius:18px; overflow:hidden; }
        .header     { background:linear-gradient(135deg,#f7971e,#ffd200); padding:30px 20px; text-align:center; }
        .header h1  { margin:0; font-size:26px; color:#1a1a1a; letter-spacing:1px; }
        .body       { padding:32px 36px; color:#fff; }
        .body h2    { margin:0 0 12px; font-size:20px; color:#FFE135; }
        .body p     { margin:0 0 18px; font-size:15px; line-height:1.6; color:rgba(255,255,255,0.85); }
        .btn        { display:inline-block; padding:14px 36px;
                      background:linear-gradient(135deg,#f7971e,#ffd200);
                      color:#1a1a1a; font-weight:700; font-size:16px;
                      border-radius:50px; text-decoration:none; letter-spacing:0.5px; }
        .note       { font-size:12px; color:rgba(255,255,255,0.45); margin-top:24px; }
        .footer     { background:rgba(0,0,0,0.2); padding:14px; text-align:center;
                      font-size:12px; color:rgba(255,255,255,0.4); }
      </style>
    </head>
    <body>
      <div class='container'>
        <div class='header'><h1>🍌 Banana Puzzle Game</h1></div>
        <div class='body'>
          <h2>Hi, $name! 👋</h2>
          <p>Thanks for registering! Please click the button below to <strong>verify your email address</strong>.</p>
          <p style='text-align:center;'><a href='$link' class='btn'>✅ Verify My Email</a></p>
          <p>If the button does not work, copy and paste this link into your browser:</p>
          <p style='word-break:break-all; font-size:13px; color:#FFE135;'>$link</p>
          <p class='note'>⏰ This link expires in <strong>24 hours</strong>.<br>
             If you did not register, ignore this email.</p>
        </div>
        <div class='footer'>© Banana Puzzle Game</div>
      </div>
    </body>
    </html>";
}
?>