<?php
require 'db.php';

// Postavljanje zaglavlja za JSON odgovor
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
// Provjera da li je zahtjev POST metoda
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Učitavanje JSON podataka
    $data = json_decode(file_get_contents('php://input'), true);
    $korisnicko_ime = htmlspecialchars(trim($data['username'] ?? ''));
    $sifra = htmlspecialchars(trim($data['password'] ?? ''));
    // Validacija ulaznih podataka
    if (empty($korisnicko_ime) || empty($sifra)) {
        echo json_encode(['success' => false, 'message' => 'Korisničko ime i šifra su obavezni!']);
        exit();
    }

    // Provjera korisnika u bazi
    $stmt = $conn->prepare("SELECT id, sifra FROM korisnik WHERE korisnicko_ime = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'SQL greška: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("s", $korisnicko_ime);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Provjera šifre
        if (password_verify($sifra, $user['sifra'])) {
            echo json_encode(['success' => true, 'userId' => $user['id'], 'username' => $korisnicko_ime]);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Pogrešna šifra.']);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Korisnik ne postoji.']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Neispravan zahtjev.']);
    exit();
}
?>
