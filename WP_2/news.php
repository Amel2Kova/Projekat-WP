<?php
// news.php

header('Content-Type: application/json');

// Include the database connection
require 'db.php';

// Fetch the news from the database
$sql = "SELECT id, title, content, image_url, created_at FROM news ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);

if ($result) {
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
} else {
    echo json_encode(['error' => 'Error fetching news: ' . $conn->error]);
}
?>
