<?php

$server = 'localhost';
$username = 'root';
$password = ''; 
$database = 'user_management';

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// charset for proper Unicode support
$conn->set_charset("utf8mb4");

