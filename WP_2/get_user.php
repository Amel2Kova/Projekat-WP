<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
require 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['id'];

    if (!$userId) {
        echo json_encode(['error' => 'ID korisnika nije poslan.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, ime, prezime, korisnicko_ime, UserPicture, email, administrator FROM korisnik WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'Korisnik nije pronađen.']);
    }
    exit;
} else {
    echo json_encode(['error' => 'Neispravan zahtjev.']);
    exit;
}
