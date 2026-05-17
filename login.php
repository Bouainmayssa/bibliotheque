<?php
require_once __DIR__ . "/includes/db.php";

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["role"] = $user["role"];
        if ($user["role"] === "admin") {
            header("Location: /bibliothéque/admin/dashboard.php");
        } else {
            header("Location: /bibliothéque/books.php");
        }
        exit;
    }
    $error = "Identifiants invalides.";
}

include __DIR__ . "/includes/header.php";
?>

<h1>Connexion</h1>
<?php if ($error !== ""): ?><p class="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" class="form">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Mot de passe</label>
    <input type="password" name="password" required>

    <button type="submit" class="btn">Se connecter</button>
</form>

<?php include __DIR__ . "/includes/footer.php"; ?>
