<?php
require_once __DIR__ . "/includes/db.php";

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($name === "" || $email === "" || $password === "") {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            $error = "Cet email est deja utilise.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $insert->execute([$name, $email, $hash]);
            header("Location: /bibliothéque/login.php");
            exit;
        }
    }
}

include __DIR__ . "/includes/header.php";
?>

<h1>Inscription</h1>
<?php if ($error !== ""): ?><p class="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" class="form">
    <label>Nom</label>
    <input type="text" name="name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Mot de passe</label>
    <input type="password" name="password" required>

    <button type="submit" class="btn">Creer un compte</button>
</form>

<?php include __DIR__ . "/includes/footer.php"; ?>
