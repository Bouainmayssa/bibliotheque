<?php
require_once __DIR__ . "/../includes/db.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: /bibliothéque/login.php");
    exit;
}
$stats = [
    "books" => (int) $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn(),
    "users" => (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    "reservations" => (int) $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn(),
];
$search = trim($_GET["q"] ?? "");
if ($search !== "") {
    $stmt = $pdo->prepare("SELECT id, title, author, COALESCE(type, '') AS type FROM books WHERE title LIKE ? OR author LIKE ? OR COALESCE(type, '') LIKE ? ORDER BY id DESC");
    $like = "%" . $search . "%";
    $stmt->execute([$like, $like, $like]);
    $books = $stmt->fetchAll();
} else {
    $books = $pdo->query("SELECT id, title, author, COALESCE(type, '') AS type FROM books ORDER BY id DESC")->fetchAll();
}
include __DIR__ . "/../includes/admin_header.php";
?>

<h1>Tableau de bord admin</h1>
<div class="admin-stats">
    <article class="admin-stat-card">
        <h3><?= $stats["books"] ?></h3>
        <p>Livres</p>
    </article>
    <article class="admin-stat-card">
        <h3><?= $stats["users"] ?></h3>
        <p>Utilisateurs</p>
    </article>
    <article class="admin-stat-card">
        <h3><?= $stats["reservations"] ?></h3>
        <p>Reservations</p>
    </article>
</div>

<div class="admin-links">
    <a class="btn" href="/bibliothéque/admin/add_book.php">Gestion des livres</a>
    <a class="btn" href="/bibliothéque/admin/reservations.php">Voir les reservations</a>
    <a class="btn" href="/bibliothéque/admin/users.php">Gerer les utilisateurs</a>
</div>

<h2 class="admin-section-title">Gestion des livres</h2>
<form method="get" class="admin-search-bar">
    <input type="text" name="q" placeholder="Rechercher un livre, auteur ou type..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn">Rechercher</button>
    <?php if ($search !== ""): ?>
        <a class="btn btn-light" href="/bibliothéque/admin/dashboard.php">Reinitialiser</a>
    <?php endif; ?>
</form>

<table>
    <thead>
    <tr>
        <th>Titre</th>
        <th>Auteur</th>
        <th>Type</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (count($books) === 0): ?>
        <tr>
            <td colspan="4">Aucun livre pour le moment.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book["title"]) ?></td>
                <td><?= htmlspecialchars($book["author"]) ?></td>
                <td><?= htmlspecialchars($book["type"] !== "" ? $book["type"] : "Non defini") ?></td>
                <td class="table-actions">
                    <a class="btn" href="/bibliothéque/admin/edit_book.php?id=<?= (int) $book["id"] ?>">Modifier</a>
                    <a class="btn btn-danger" href="/bibliothéque/admin/delete_book.php?id=<?= (int) $book["id"] ?>" onclick="return confirm('Supprimer ce livre ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . "/../includes/admin_footer.php"; ?>
