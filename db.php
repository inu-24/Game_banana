<?php
$servername = "localhost";
$username = "root";        // default for XAMPP
$password = "";            // default empty in XAMPP
$database = "banana_game";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>