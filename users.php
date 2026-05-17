<?php
require_once __DIR__ . "/../includes/db.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: /bibliothéque/login.php");
    exit;
}
$search = trim($_GET["q"] ?? "");
if ($search !== "") {
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE name LIKE ? OR email LIKE ? ORDER BY id DESC");
    $like = "%" . $search . "%";
    $stmt->execute([$like, $like]);
    $users = $stmt->fetchAll();
} else {
    $users = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id DESC")->fetchAll();
}
include __DIR__ . "/../includes/admin_header.php";
?>

<h1>Utilisateurs</h1>
<form method="get" class="admin-search-bar">
    <input type="text" name="q" placeholder="Rechercher un utilisateur..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn">Rechercher</button>
    <?php if ($search !== ""): ?>
        <a class="btn btn-light" href="/bibliothéque/admin/users.php">Reinitialiser</a>
    <?php endif; ?>
</form>
<table>
    <thead>
    <tr>
        <th>Nom</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user["name"]) ?></td>
            <td><?= htmlspecialchars($user["email"]) ?></td>
            <td><?= htmlspecialchars($user["role"]) ?></td>
            <td class="table-actions">
                <a class="btn" href="/bibliothéque/admin/edit_user.php?id=<?= (int) $user["id"] ?>">Modifier</a>
                <?php if ($user["id"] !== $_SESSION["user_id"]): ?>
                    <a class="btn btn-danger" href="/bibliothéque/admin/delete_user.php?id=<?= (int) $user["id"] ?>" onclick="return confirm('Supprimer cet utilisateur ?');">Supprimer</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . "/../includes/admin_footer.php"; ?>
