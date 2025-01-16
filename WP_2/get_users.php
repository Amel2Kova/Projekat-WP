<?php
require 'db.php';
header('Content-Type: application/json');
// Postavljanje zaglavlja za JSON odgovor
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
$sql = "SELECT * FROM korisnik";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $korisnici = [];
    while ($row = $result->fetch_assoc()) {
        $korisnici[] = $row;
    }
    echo json_encode($korisnici);
} else {
    echo json_encode([]);
}
?>
