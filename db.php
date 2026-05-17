<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$dbname = "bibliotheque";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        $pdo->exec("ALTER TABLE books ADD COLUMN type VARCHAR(100) NULL");
    } catch (PDOException $e) {
    }
    try {
        $pdo->exec("ALTER TABLE reservations ADD COLUMN status ENUM('en_attente','acceptee','refusee') NOT NULL DEFAULT 'en_attente'");
    } catch (PDOException $e) {
    }
} catch (PDOException $e) {
    die("Erreur de connexion a la base de donnees : " . $e->getMessage());
}
?>
