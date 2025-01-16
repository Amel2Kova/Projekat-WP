<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Pristup zabranjen. Molimo vas da se prijavite.");
}

echo "Dobrodošli! Vaš korisnički ID je: " . $_SESSION['user_id'];
?>
