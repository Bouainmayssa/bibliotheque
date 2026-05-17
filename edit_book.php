<?php
require_once __DIR__ . "/../includes/db.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: /bibliothéque/login.php");
    exit;
}

$id = (int) ($_GET["id"] ?? $_POST["id"] ?? 0);
if ($id <= 0) {
    header("Location: /bibliothéque/admin/dashboard.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();
if (!$book) {
    header("Location: /bibliothéque/admin/dashboard.php");
    exit;
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $author = trim($_POST["author"] ?? "");
    $type = trim($_POST["type"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $imagePath = $book["image"];

    if ($title === "" || $author === "") {
        $error = "Titre et auteur sont obligatoires.";
    } else {
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $uploadsDir = __DIR__ . "/../uploads/";
            $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $allowed = ["jpg", "jpeg", "png", "webp", "gif"];
            if (!in_array($ext, $allowed, true)) {
                $error = "Format d'image non supporte.";
            } else {
                $fileName = uniqid("book_", true) . "." . $ext;
                $targetPath = $uploadsDir . $fileName;
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
                    $error = "Impossible d'enregistrer la nouvelle image.";
                } else {
                    $imagePath = "/bibliothéque/uploads/" . $fileName;
                }
            }
        }

        if ($error === "") {
            $update = $pdo->prepare("UPDATE books SET title = ?, author = ?, type = ?, image = ?, description = ? WHERE id = ?");
            $update->execute([$title, $author, $type, $imagePath, $description, $id]);
            header("Location: /bibliothéque/admin/add_book.php");
            exit;
        }
    }
}

include __DIR__ . "/../includes/admin_header.php";
?>

<h1>Modifier le livre</h1>
<p class="admin-page-intro">Mettez a jour les informations du livre. L'image est optionnelle.</p>
<?php if ($error !== ""): ?><p class="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="form">
    <input type="hidden" name="id" value="<?= (int) $book["id"] ?>">

    <label>Titre</label>
    <input type="text" name="title" value="<?= htmlspecialchars($book["title"]) ?>" required>

    <label>Auteur</label>
    <input type="text" name="author" value="<?= htmlspecialchars($book["author"]) ?>" required>

    <label>Type</label>
    <input type="text" name="type" value="<?= htmlspecialchars($book["type"] ?? "") ?>" placeholder="Roman, Science, Histoire...">

    <label>Description</label>
    <textarea name="description"><?= htmlspecialchars($book["description"] ?? "") ?></textarea>

    <label>Image (optionnelle)</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit" class="btn">Enregistrer</button>
</form>

<?php include __DIR__ . "/../includes/admin_footer.php"; ?>
