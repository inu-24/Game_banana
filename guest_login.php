<!-- Source: Own Work  -->
<?php
session_start();

// Generate a unique guest name like "Guest_A3F2"
$guestId   = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
$guestName = "Guest_" . $guestId;

// Mark session as guest
$_SESSION['is_guest']  = true;
$_SESSION['user_id']   = 0;          
$_SESSION['fullname']  = $guestName;

// Redirect to home
header("Location: home.php");
exit();
?>