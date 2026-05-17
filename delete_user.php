<?php
require_once __DIR__ . "/../includes/db.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: /bibliothéque/login.php");
    exit;
}

$id = (int) ($_GET["id"] ?? 0);
if ($id > 0 && $id !== (int) $_SESSION["user_id"]) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: /bibliothéque/admin/users.php");
exit;
?>
