<?php
require_once __DIR__ . "/../includes/db.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: /bibliothéque/login.php");
    exit;
}

$allowed = ["acceptee", "refusee"];

// Accepter via GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $id     = (int) ($_GET["id"] ?? 0);
    $status = $_GET["status"] ?? "";

    if ($id > 0 && in_array($status, $allowed, true) && $status === "acceptee") {
        $stmt = $pdo->prepare("UPDATE reservations SET status = ?, motif_refus = NULL WHERE id = ?");
        $stmt->execute([$status, $id]);
    }
}

// Refuser via POST (avec motif)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id     = (int) ($_POST["id"] ?? 0);
    $status = $_POST["status"] ?? "";
    $motif  = trim($_POST["motif_refus"] ?? "");

    if ($id > 0 && $status === "refusee" && $motif !== "") {
        $stmt = $pdo->prepare("UPDATE reservations SET status = ?, motif_refus = ? WHERE id = ?");
        $stmt->execute([$status, $motif, $id]);
    }
}

header("Location: /bibliothéque/admin/reservations.php");
exit;
?>
