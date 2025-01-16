<?php
require 'db.php';
header('Content-Type: application/json');
// Postavljanje zaglavlja za JSON odgovor
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
$data = json_decode(file_get_contents('php://input'), true);
$table = $data['table'];
$id = $data['id'];

if ($table && $id) {
    $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Nedostaju podaci.']);
}
?>
