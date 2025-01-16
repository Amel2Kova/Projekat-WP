<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
// Povezivanje s bazom
require 'db.php';

// Provjera da li su svi podaci poslani
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $ime = htmlspecialchars(trim($_POST['ime']));
    $prezime = htmlspecialchars(trim($_POST['prezime']));
    $korisnicko_ime = htmlspecialchars(trim($_POST['korisnicko_ime']));
    $sifra = htmlspecialchars(trim($_POST['sifra']));

    // Validacija podataka
    if (empty($email) || empty($ime) || empty($prezime) || empty($korisnicko_ime) || empty($sifra)) {
        die("Sva polja su obavezna!");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Neispravan format email adrese!");
    }

    $stmt = $conn->prepare("SELECT ime FROM korisnik WHERE email = '".$email."' OR korisnicko_ime = '".$korisnicko_ime."'");
    if (!$stmt) {
        die("SQL greška prilikom pripreme: " . $conn->error);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    

    if ($result->num_rows > 0) {
        die("Korisničko ime ili email već postoje!");
    }

    // Hashiranje šifre
    $hashed_password = password_hash($sifra, PASSWORD_DEFAULT);

    // Ubacivanje korisnika u bazu
    $stmt = $conn->prepare("INSERT INTO korisnik (email, ime, prezime, korisnicko_ime, sifra) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $email, $ime, $prezime, $korisnicko_ime, $hashed_password);

    if ($stmt->execute()) {
        echo "Registracija uspješna!";
    } else {
        echo "Greška prilikom registracije: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Neispravan zahtjev!";
}
?>
