<?php
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: /bibliothéque/login.php");
    exit;
}

$bookId = (int) ($_GET["book_id"] ?? 0);
if ($bookId > 0) {
    $stmt = $pdo->prepare("INSERT INTO reservations (user_id, book_id, status) VALUES (?, ?, 'en_attente')");
    $stmt->execute([$_SESSION["user_id"], $bookId]);
}

header("Location: /bibliothéque/my_reservations.php");
exit;
?>
