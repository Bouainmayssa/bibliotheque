<?php
require_once __DIR__ . "/../includes/db.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: /bibliothéque/login.php");
    exit;
}

$id = (int) ($_GET["id"] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: /bibliothéque/admin/add_book.php");
exit;
?>
