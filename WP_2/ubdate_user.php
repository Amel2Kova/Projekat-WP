<?php
// Uključivanje fajla za konekciju s bazom
require 'db.php';

// Postavljanje HTTP zaglavlja za CORS
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// Obrada preflight zahtjeva
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Provjera metode zahtjeva
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "Metoda nije dopuštena"]);
    exit();
}

// Dobijanje podataka iz Angular zahtjeva
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'], $data['ime'], $data['prezime'], $data['email']))
// Priprema podataka
{
$id = intval($data['id']);
$ime = htmlspecialchars(trim($data['ime']));
$prezime = htmlspecialchars(trim($data['prezime']));
$email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
$admin = intval($data['admin']);


try {
    // Ažuriranje korisničkih podataka
    $stmt = $conn->prepare("
        UPDATE korisnik 
        SET ime = ?, prezime = ?, email = ?, administrator = ? 
        WHERE id = ?
    ");
    if (!$stmt) {
        throw new Exception("Greška u pripremi SQL-a: " . $conn->error);
    }

    $stmt->bind_param("sssii", $ime, $prezime, $email, $admin, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["message" => "Korisnički podaci uspješno ažurirani"]);
    } else {
        echo json_encode(["message" => "Nema promjena u podacima ili korisnik ne postoji"]);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Došlo je do greške", "error" => $e->getMessage()]);
}
}else  {
    http_response_code(400);
    echo json_encode(["message" => "Neki obavezni podaci nisu poslani"]);
    exit();
}

// Zatvaranje konekcije
$conn->close();
?>
