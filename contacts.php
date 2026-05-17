<?php
require_once __DIR__ . "/../includes/db.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: /bibliothéque/login.php");
    exit;
}

// Supprimer un contact
if (isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: /bibliothéque/admin/contacts.php?success=deleted");
    exit;
}

// Marquer comme lu
if (isset($_GET["read"])) {
    $id = (int)$_GET["read"];
    $stmt = $pdo->prepare("UPDATE contacts SET lu = 1 WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: /bibliothéque/admin/contacts.php");
    exit;
}

$contacts = $pdo->query("SELECT * FROM contacts ORDER BY date DESC")->fetchAll();

include __DIR__ . "/../includes/admin_header.php";
?>

<h1>Gestion des contacts</h1>

<?php if (isset($_GET["success"]) && $_GET["success"] === "deleted"): ?>
    <div class="success-msg">Le message a été supprimé avec succès.</div>
<?php endif; ?>

<div class="admin-table-container">
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Sujet</th>
                <th>Message</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($contacts)): ?>
                <tr>
                    <td colspan="7">Aucun message reçu.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($contacts as $c): ?>
                    <tr class="<?= $c["lu"] ? "" : "unread-row" ?>">
                        <td><?= htmlspecialchars($c["nom"]) ?></td>
                        <td><?= htmlspecialchars($c["email"]) ?></td>
                        <td><?= htmlspecialchars($c["sujet"]) ?></td>
                        <td>
                            <div class="message-preview" title="<?= htmlspecialchars($c["message"]) ?>">
                                <?= nl2br(htmlspecialchars(substr($c["message"], 0, 100))) ?><?= strlen($c["message"]) > 100 ? "..." : "" ?>
                            </div>
                        </td>
                        <td><?= date("d/m/Y H:i", strtotime($c["date"])) ?></td>
                        <td>
                            <?php if ($c["lu"]): ?>
                                <span class="status-badge status-acceptee">Lu</span>
                            <?php else: ?>
                                <span class="status-badge status-en_attente">Nouveau</span>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions">
                            <?php if (!$c["lu"]): ?>
                                <a href="/bibliothéque/admin/contacts.php?read=<?= $c["id"] ?>" class="btn">Marquer lu</a>
                            <?php endif; ?>
                            <a href="/bibliothéque/admin/contacts.php?delete=<?= $c["id"] ?>" class="btn btn-danger" onclick="return confirm('Supprimer ce message ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . "/../includes/admin_footer.php"; ?>
