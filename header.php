<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliotheque en ligne</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bibliothéque/css/style.css">
</head>
<body>
<header>
    <div class="container nav-shell">
        <div class="nav-left">
            <a class="brand" href="/bibliothéque/index.php">Bibliotheque</a>
        </div>

        <nav class="nav-center">
            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="/bibliothéque/books.php">Livres</a>
                <a href="/bibliothéque/contact.php">Contact</a>
            <?php endif; ?>
        </nav>

        <div class="nav-right">
            <?php if (isset($_SESSION["user_id"])): ?>
                <div class="nav-dropdown" id="navDropdown">
                    <button class="nav-dropdown-toggle" onclick="toggleNavDropdown()" type="button">
                        <span class="nav-user-avatar"><?= strtoupper(substr($_SESSION["name"] ?? "U", 0, 1)) ?></span>
                        <?= htmlspecialchars($_SESSION["name"] ?? "Utilisateur") ?>
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="currentColor"><path d="M2 4l4 4 4-4"/></svg>
                    </button>
                    <div class="nav-dropdown-menu" id="navDropdownMenu">
                        <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                            <a class="nav-dropdown-item" href="/bibliothéque/admin/dashboard.php">Admin</a>
                        <?php endif; ?>
                        <a class="nav-dropdown-item" href="/bibliothéque/my_reservations.php">Mes reservations</a>
                        <div class="nav-dropdown-divider"></div>
                        <a class="nav-dropdown-item nav-dropdown-danger" href="/bibliothéque/logout.php">Deconnexion</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/bibliothéque/login.php">Connexion</a>
                <a href="/bibliothéque/register.php">Inscription</a>
            <?php endif; ?>
        </div>
        <script>
        function toggleNavDropdown() {
            document.getElementById('navDropdownMenu').classList.toggle('is-open');
        }
        document.addEventListener('click', function(e) {
            var dd = document.getElementById('navDropdown');
            if (dd && !dd.contains(e.target)) {
                document.getElementById('navDropdownMenu').classList.remove('is-open');
            }
        });
        </script>
    </div>
</header>
<main class="container">
