<?php
// Uključivanje fajla za konekciju s bazom
require 'db.php';

// Postavljanje HTTP zaglavlja
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Provjera metode
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $Novost ;
    // Provjera da li je `id` postavljen
    if (isset($data['id'])) {
        $Id = $data['id'];

        // Priprema SQL upita
        $stmt = $conn->prepare("SELECT id, naslov, kratak_sadrzaj, tekst, slika, pozicija_slike, id_pisca, broj_lajkova, datum_kreiranja FROM novosti WHERE id = ?");
        $stmt->bind_param("i", $Id);

 
 $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $Novost = $result->fetch_assoc();
            echo json_encode($Novost);
        } else {
                
            echo json_encode(['error' => 'Novost nije pronađea.']);
        }
        exit;
    } else {
        echo json_encode(['error' => 'Neispravan zahtjev.']);
        exit;
    }
} 
    $conn->close();
?>

        // Izvršavanje upita
 /*       
        // Provjera da li ima rezultata
        if ($result->num_rows > 0) {
            $novosti = array();

            // Iteracija kroz rezultate
            while ($row = $result->fetch_assoc()) {
                $novosti[] = $row;
            }

            // Vraćanje rezultata kao JSON
            echo json_encode($novosti);
        } else {
            // Ako nema rezultata, vraća se prazna lista
            echo json_encode([]);
        }
        $stmt->close();
    } else {
        // Ako ID nije poslan
        echo json_encode(["error" => "ID nije poslan."]);
    }
} else {
    // Neispravna metoda zahtjeva
    echo json_encode(["error" => "Neispravan HTTP zahtjev."]);
}
*/
// Zatvaranje konekcije
