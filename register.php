<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
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

    // Insert new user
    $sql = "INSERT INTO users (fullname, email, password, total_score, current_level, created_at) 
            VALUES (?, ?, ?, 0, 'Easy', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $fullname, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful! Please Login'); window.location='login.html';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>