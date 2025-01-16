<?php
// Uključivanje fajla za konekciju s bazom
require 'db.php';

// Postavljanje HTTP zaglavlja
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// SQL upit za izvlačenje podataka iz tabele novosti
$sql = "SELECT id, naslov, kratak_sadrzaj, tekst, slika, pozicija_slike, id_pisca, broj_lajkova, datum_kreiranja FROM novosti ORDER BY id";
$result = $conn->query($sql);

// Provjera da li ima rezultata
if ($result->num_rows > 0) {
    $novosti = array();

    // Iteracija kroz rezultate i dodavanje u niz
    while ($row = $result->fetch_assoc()) {
        $novosti[] = $row;
    }

    // Vraćanje rezultata kao JSON
    echo json_encode($novosti);
} else {
    // Ako nema rezultata, vraća se prazna lista
    echo json_encode([]);
}

// Zatvaranje konekcije
$conn->close();
?>
