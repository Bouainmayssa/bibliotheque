<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: /bibliothéque/login.php");
    exit;
}
$currentPage = basename($_SERVER["PHP_SELF"]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Bibliotheque</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bibliothéque/css/style.css">
</head>
<body>
<header class="admin-topbar">
    <div class="admin-topbar-inner">
        <a class="brand" href="/bibliothéque/admin/dashboard.php">Admin Bibliotheque</a>
        <div class="admin-topbar-links">
            <span class="admin-welcome">Bonjour, <?= htmlspecialchars($_SESSION["name"] ?? "Admin") ?></span>
            <a href="/bibliothéque/logout.php">Deconnexion</a>
        </div>
    </div>
</header>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <h3>Navigation</h3>
        <a class="<?= ($currentPage === "dashboard.php" || $currentPage === "edit_book.php") ? "active" : "" ?>" href="/bibliothéque/admin/dashboard.php">
            <span>&#9632;</span> Dashboard
        </a>
        <a class="<?= $currentPage === "add_book.php" ? "active" : "" ?>" href="/bibliothéque/admin/add_book.php">
            <span>&#43;</span> Gestion des livres
        </a>
        <a class="<?= $currentPage === "reservations.php" ? "active" : "" ?>" href="/bibliothéque/admin/reservations.php">
            <span>&#128196;</span> Reservations
        </a>
        <a class="<?= ($currentPage === "users.php" || $currentPage === "edit_user.php") ? "active" : "" ?>" href="/bibliothéque/admin/users.php">
            <span>&#128100;</span> Utilisateurs
        </a>
        <a class="<?= $currentPage === "contacts.php" ? "active" : "" ?>" href="/bibliothéque/admin/contacts.php">
            <span>&#9993;</span> Gestion des contacts
        </a>
    </aside>
    <main class="admin-content">
