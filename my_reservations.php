<?php
require_once __DIR__ . "/includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: /bibliothéque/login.php");
    exit;
}

$stmt = $pdo->prepare(
    "SELECT r.date, r.status, b.title, b.author
     FROM reservations r
     INNER JOIN books b ON b.id = r.book_id
     WHERE r.user_id = ?
     ORDER BY r.date DESC"
);
$stmt->execute([$_SESSION["user_id"]]);
$reservations = $stmt->fetchAll();

include __DIR__ . "/includes/header.php";
?>

<h1>Mes reservations</h1>
<?php if (count($reservations) === 0): ?>
    <p>Aucune reservation.</p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Livre</th>
            <th>Auteur</th>
            <th>Statut</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $res): ?>
            <tr>
                <td><?= htmlspecialchars($res["title"]) ?></td>
                <td><?= htmlspecialchars($res["author"]) ?></td>
                <td><span class="status-badge status-<?= htmlspecialchars($res["status"]) ?>"><?= htmlspecialchars($res["status"]) ?></span></td>
                <td><?= htmlspecialchars($res["date"]) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>
