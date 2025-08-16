<?php
$host = "localhost";
$user = "hadiyahadmin";         // Replace with your MySQL username
$password = "Hadiyahlovescrochet@123";     // Replace with your MySQL password
$database = "nexora_db";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

