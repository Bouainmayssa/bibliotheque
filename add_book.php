<?php
require_once __DIR__ . "/../includes/db.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: /bibliothéque/login.php");
    exit;
}

$error = "";
$success = isset($_GET["success"]) ? "Livre ajoute avec succes." : "";
$openModal = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $author = trim($_POST["author"] ?? "");
    $type = trim($_POST["type"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($title === "" || $author === "" || !isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        $error = "Titre, auteur et image sont obligatoires.";
    } else {
        $uploadsDir = __DIR__ . "/../uploads/";
        $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "webp", "gif"];
        if (!in_array($ext, $allowed, true)) {
            $error = "Format d'image non supporte.";
        } else {
            $fileName = uniqid("book_", true) . "." . $ext;
            $targetPath = $uploadsDir . $fileName;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
                $imagePath = "/bibliothéque/uploads/" . $fileName;
                $stmt = $pdo->prepare("INSERT INTO books (title, author, type, image, description) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $author, $type, $imagePath, $description]);
                header("Location: /bibliothéque/admin/add_book.php?success=1");
                exit;
            } else {
                $error = "Impossible d'enregistrer l'image.";
            }
        }
    }
    if ($error !== "") {
        $openModal = true;
    }
}

$books = $pdo->query("SELECT id, title, author, COALESCE(type, '') AS type, image FROM books ORDER BY id DESC")->fetchAll();

include __DIR__ . "/../includes/admin_header.php";
?>

<h1>Gestion des livres</h1>
<p class="admin-page-intro">Consultez la liste des livres puis ajoutez un nouveau livre avec son type.</p>
<?php if ($error !== ""): ?><p class="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<?php if ($success !== ""): ?><p class="success-msg"><?= htmlspecialchars($success) ?></p><?php endif; ?>

<div class="admin-books-toolbar">
    <button type="button" class="btn" id="openAddBookModal">Ajouter un livre</button>
</div>

<div class="admin-books-grid">
    <?php if (count($books) === 0): ?>
        <p>Aucun livre pour le moment.</p>
    <?php else: ?>
        <?php foreach ($books as $book): ?>
            <article class="admin-book-card">
                <img src="<?= htmlspecialchars($book["image"]) ?>" alt="Couverture">
                <div class="admin-book-card-content">
                    <h3><?= htmlspecialchars($book["title"]) ?></h3>
                    <p><strong>Auteur:</strong> <?= htmlspecialchars($book["author"]) ?></p>
                    <p><strong>Type:</strong> <?= htmlspecialchars($book["type"] !== "" ? $book["type"] : "Non defini") ?></p>
                </div>
                <div class="table-actions admin-book-actions">
                    <a class="btn" href="/bibliothéque/admin/edit_book.php?id=<?= (int) $book["id"] ?>">Modifier</a>
                    <a class="btn btn-danger" href="/bibliothéque/admin/delete_book.php?id=<?= (int) $book["id"] ?>" onclick="return confirm('Supprimer ce livre ?');">Supprimer</a>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="admin-modal-overlay <?= $openModal ? "is-open" : "" ?>" id="addBookModal">
    <div class="admin-modal">
        <div class="admin-modal-header">
            <h2>Ajouter un livre</h2>
            <button type="button" class="btn btn-danger admin-modal-close" id="closeAddBookModal">X</button>
        </div>
        <form method="post" enctype="multipart/form-data" class="form admin-modal-form">
            <label>Titre</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($title ?? "") ?>">

            <label>Auteur</label>
            <input type="text" name="author" required value="<?= htmlspecialchars($author ?? "") ?>">

            <label>Type</label>
            <input type="text" name="type" placeholder="Roman, Science, Histoire..." value="<?= htmlspecialchars($type ?? "") ?>">

            <label>Description</label>
            <textarea name="description"><?= htmlspecialchars($description ?? "") ?></textarea>

            <label>Image</label>
            <input type="file" name="image" accept="image/*" required>

            <button type="submit" class="btn">Confirmer l'ajout</button>
        </form>
    </div>
</div>

<script>
(() => {
    const modal = document.getElementById("addBookModal");
    const openBtn = document.getElementById("openAddBookModal");
    const closeBtn = document.getElementById("closeAddBookModal");
    if (!modal || !openBtn || !closeBtn) return;

    openBtn.addEventListener("click", () => modal.classList.add("is-open"));
    closeBtn.addEventListener("click", () => modal.classList.remove("is-open"));
    modal.addEventListener("click", (event) => {
        if (event.target === modal) modal.classList.remove("is-open");
    });
})();
</script>

<?php include __DIR__ . "/../includes/admin_footer.php"; ?>
