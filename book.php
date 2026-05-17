<?php
require_once __DIR__ . "/includes/db.php";

function getBookImageUrl($imageName) {
    $imageName = trim($imageName ?? "");
    $fileName = basename($imageName);
    $baseName = pathinfo($fileName, PATHINFO_FILENAME);

    $extensions = ["png", "jpg", "jpeg", "webp"];

    if ($fileName !== "" && file_exists(__DIR__ . "/uploads/" . $fileName)) {
        return "/bibliothéque/uploads/" . rawurlencode($fileName);
    }

    foreach ($extensions as $ext) {
        $candidate = $baseName . "." . $ext;

        if (file_exists(__DIR__ . "/uploads/" . $candidate)) {
            return "/bibliothéque/uploads/" . rawurlencode($candidate);
        }
    }

    return "/bibliothéque/uploads/books.png";
}

$id = (int) ($_GET["id"] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

include __DIR__ . "/includes/header.php";
?>

<?php if (!$book): ?>

    <p>Livre introuvable.</p>

<?php else: ?>

    <?php $imageUrl = getBookImageUrl($book["image"]); ?>

    <section class="book-details">
        <img 
            src="<?= htmlspecialchars($imageUrl) ?>"
            alt="Couverture"
            style="width:300px; max-height:420px; object-fit:cover;"
        >

        <div class="book-info">
            <h1><?= htmlspecialchars($book["title"]) ?></h1>

            <p>
                <strong>Auteur :</strong>
                <?= htmlspecialchars($book["author"]) ?>
            </p>

            <?php if (!empty($book["description"])): ?>
                <p>
                    <strong>Description :</strong>
                    <?= nl2br(htmlspecialchars($book["description"])) ?>
                </p>
            <?php endif; ?>

            <?php if (isset($_SESSION["user_id"])): ?>
                <a class="btn" href="/bibliothéque/reserve.php?book_id=<?= (int) $book["id"] ?>">
                    Reserver
                </a>
            <?php else: ?>
                <p>Connectez-vous pour reserver ce livre.</p>
            <?php endif; ?>
        </div>
    </section>

<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>