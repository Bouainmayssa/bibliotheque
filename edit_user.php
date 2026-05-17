<?php
require_once __DIR__ . "/../includes/db.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: /bibliothéque/login.php");
    exit;
}

$id = (int) ($_GET["id"] ?? $_POST["id"] ?? 0);
if ($id <= 0) {
    header("Location: /bibliothéque/admin/users.php");
    exit;
}

$stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user) {
    header("Location: /bibliothéque/admin/users.php");
    exit;
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $role = $_POST["role"] ?? "user";
    $allowedRoles = ["user", "admin"];

    if ($name === "" || $email === "") {
        $error = "Nom et email sont obligatoires.";
    } elseif (!in_array($role, $allowedRoles, true)) {
        $error = "Role invalide.";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id <> ?");
        $check->execute([$email, $id]);
        if ($check->fetch()) {
            $error = "Cet email est deja utilise par un autre utilisateur.";
        } else {
            if ($id === (int) $_SESSION["user_id"]) {
                $role = "admin";
            }
            $update = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
            $update->execute([$name, $email, $role, $id]);

            if ($id === (int) $_SESSION["user_id"]) {
                $_SESSION["name"] = $name;
                $_SESSION["role"] = $role;
            }

            header("Location: /bibliothéque/admin/users.php");
            exit;
        }
    }
}

include __DIR__ . "/../includes/admin_header.php";
?>

<h1>Modifier utilisateur</h1>
<?php if ($error !== ""): ?><p class="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" class="form">
    <input type="hidden" name="id" value="<?= (int) $user["id"] ?>">

    <label>Nom</label>
    <input type="text" name="name" value="<?= htmlspecialchars($user["name"]) ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>" required>

    <label>Role</label>
    <select name="role" class="form-select">
        <option value="user" <?= $user["role"] === "user" ? "selected" : "" ?>>user</option>
        <option value="admin" <?= $user["role"] === "admin" ? "selected" : "" ?>>admin</option>
    </select>

    <button type="submit" class="btn">Enregistrer</button>
</form>

<?php include __DIR__ . "/../includes/admin_footer.php"; ?>
