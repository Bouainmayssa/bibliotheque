<?php
require_once __DIR__ . "/includes/db.php";
$success = false;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $sujet = trim($_POST["sujet"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if ($nom === "")
        $errors[] = "Le nom est requis.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Email invalide.";
    if ($sujet === "")
        $errors[] = "Le sujet est requis.";
    if ($message === "")
        $errors[] = "Le message est requis.";

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "INSERT INTO contacts (nom, email, sujet, message) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$nom, $email, $sujet, $message]);
        $success = true;
    }
}

include __DIR__ . "/includes/header.php";
?>

<div class="contact-page">
    <div class="contact-hero">
        <h1>Contactez-nous</h1>
        <p>Une question ? Une suggestion ? Remplissez le formulaire ci-dessous et nous vous répondrons.</p>
    </div>

    <div class="contact-grid">
        <!-- Lottie Animation -->
        <div class="contact-lottie-container">
            <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
            <lottie-player src="https://assets2.lottiefiles.com/packages/lf20_u25cckyh.json" background="transparent" speed="1" style="width: 100%; height: 100%;" loop autoplay></lottie-player>
        </div>

        <!-- Formulaire -->
        <div class="contact-form-panel">
            <?php if ($success): ?>
                <div class="contact-success">
                    <span>&#10003;</span>
                    <div>
                        <strong>Message envoyé !</strong>
                        <p>Nous avons bien reçu votre message et vous répondrons rapidement.</p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert">
                    <?php foreach ($errors as $e): ?>
                        <div><?= htmlspecialchars($e) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!$success): ?>
                <form method="post" class="contact-main-form" novalidate>
                    <div class="contact-form-row">
                        <div class="contact-form-group">
                            <label for="nom">Nom complet</label>
                            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                                placeholder="Votre nom" required>
                        </div>
                        <div class="contact-form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="votre@email.com"
                                required>
                        </div>
                    </div>
                    <div class="contact-form-group">
                        <label for="sujet">Sujet</label>
                        <input type="text" id="sujet" name="sujet" value="<?= htmlspecialchars($_POST['sujet'] ?? '') ?>"
                            placeholder="Objet de votre message" required>
                    </div>
                    <div class="contact-form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="4" placeholder="Écrivez votre message..."
                            required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-contact">Envoyer le message</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>