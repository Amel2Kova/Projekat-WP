<?php
// db_connection.php

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'news_portal';

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>