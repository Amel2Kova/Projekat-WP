<?php


header("Access-Control-Allow-Origin: http://localhost:4200"); // Dozvoljava zahtjeve samo s localhost:4200
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE"); // Navodite dozvoljene metode
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Navodite dozvoljene zaglavlja
header("Access-Control-Allow-Credentials: true"); // Ako koristite sesije ili autentifikaciju
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Vrati HTTP 200 OK status za OPTIONS
    exit();
}
require 'db.php'; // Uključite konekciju na bazu
ini_set('display_errors', 1);
// Proverite da li su podaci poslati
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preuzmite JSON payload
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Proverite da li su svi potrebni podaci prisutni
    if (
        isset($data['id']) &&
        isset($data['naslov']) &&
        isset($data['kratak_sadrzaj']) &&
        isset($data['tekst']) &&
        isset($data['slika']) 
    ) {
        $id = intval($data['id']);
        $naslov = htmlspecialchars(trim($data['naslov']));
        $kratak_sadrzaj = htmlspecialchars(trim($data['kratak_sadrzaj']));
        $tekst = htmlspecialchars(trim($data['tekst']));
        $slika = htmlspecialchars(trim($data['slika']));
  // Pripremite SQL upit za ažuriranje
  $stmt = $conn->prepare("UPDATE novosti SET naslov = ?, kratak_sadrzaj = ?, tekst = ?, slika = ? WHERE id = ?");
  if ($stmt) {
      $stmt->bind_param("ssssi", $naslov, $kratak_sadrzaj, $tekst, $slika, $id);
      if ($stmt->execute()) {
          echo json_encode(['success' => true, 'message' => 'Vijest je uspešno ažurirana!']);
      } else {
          http_response_code(500);
          echo json_encode(['success' => false, 'message' => 'Greška pri ažuriranju vijesti.']);
      }
      $stmt->close();
  } else {
      http_response_code(500);
      echo json_encode(['success' => false, 'message' => 'SQL greška: ' . $conn->error]);
  }
} else {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Nedostaju potrebni podaci.']);
}
} else {
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Metod nije dozvoljen.']);
}
    
    
    


