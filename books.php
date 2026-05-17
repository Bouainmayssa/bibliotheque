<?php
require_once __DIR__ . "/includes/db.php";

function getBookImageUrl($imageName) {
    $imageName = trim($imageName ?? "");
    $fileName = basename($imageName);
    $baseName = pathinfo($fileName, PATHINFO_FILENAME);

    $extensions = ["png", "jpg", "jpeg", "webp"];

    // نجرب الاسم كما هو في database
    if ($fileName !== "" && file_exists(__DIR__ . "/uploads/" . $fileName)) {
        return "/bibliothéque/uploads/" . rawurlencode($fileName);
    }

    // نجرب نفس الاسم مع extensions أخرى
    foreach ($extensions as $ext) {
        $candidate = $baseName . "." . $ext;

        if ($baseName !== "" && file_exists(__DIR__ . "/uploads/" . $candidate)) {
            return "/bibliothéque/uploads/" . rawurlencode($candidate);
        }
    }

    // إذا ما لقيناش صورة
    return "";
}

$stmt = $pdo->query("SELECT id, title, author, image FROM books ORDER BY id DESC");
$books = $stmt->fetchAll();

include __DIR__ . "/includes/header.php";
?>

<h1>Liste des livres</h1>

<div class="grid">

    <?php foreach ($books as $book): ?>
        <?php
        $imageUrl = getBookImageUrl($book["image"]);

        // إذا ما فماش صورة، ما نعرضوش الكتاب
        if ($imageUrl === "") {
            continue;
        }
        ?>

        <article class="card">
            <img 
                src="<?= htmlspecialchars($imageUrl) ?>"
                alt="Couverture"
                style="width:100%; height:220px; object-fit:cover;"
            >

            <h3><?= htmlspecialchars($book["title"]) ?></h3>
            <p><?= htmlspecialchars($book["author"]) ?></p>

            <a class="btn" href="/bibliothéque/book.php?id=<?= (int) $book["id"] ?>">
                Voir details
            </a>
        </article>

    <?php endforeach; ?>

</div>

<?php include __DIR__ . "/includes/footer.php"; ?>