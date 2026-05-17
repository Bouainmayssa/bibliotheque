<?php
require_once __DIR__ . "/includes/db.php";
include __DIR__ . "/includes/header_index.php";
?>

<section class="landing">
    <div class="landing-grid">
        <div>
            <h1>Bienvenue dans votre bibliotheque en ligne</h1>
            <p class="landing-text">
                Consultez les livres disponibles, reservez en quelques clics et suivez vos emprunts facilement.
            </p>

            <div class="landing-actions">
                <a class="btn" href="/bibliothéque/books.php">Explorer les livres</a>

                <?php if (!isset($_SESSION["user_id"])): ?>
                    <a class="btn btn-light" href="/bibliothéque/register.php">Creer un compte</a>
                <?php endif; ?>
            </div>
        </div>

        <aside class="landing-panel reveal">
            <img 
                class="landing-image"
                src="/bibliothéque/uploads/books.webp"
                alt="Image de lecture"
            >

            <p class="lottie-caption">
                Lecture, cinema et romans dans une interface elegante.
            </p>
        </aside>
    </div>
</section>

<section class="landing-features">
    <article class="feature-card reveal">
        <span class="feature-icon">01</span>
        <h3>Catalogue clair</h3>
        <p>Parcourez rapidement les livres et leurs details.</p>
    </article>

    <article class="feature-card reveal">
        <span class="feature-icon">02</span>
        <h3>Reservation rapide</h3>
        <p>Un clic suffit pour reserver un livre disponible.</p>
    </article>

    <article class="feature-card reveal">
        <span class="feature-icon">03</span>
        <h3>Espace admin</h3>
        <p>Ajoutez, supprimez et suivez les reservations facilement.</p>
    </article>
</section>

<section class="simple-slider reveal" id="slider">
    <button type="button" class="slider-arrow slider-arrow-left" data-slider="prev" aria-label="Slide precedent">&#10094;</button>
    <button type="button" class="slider-arrow slider-arrow-right" data-slider="next" aria-label="Slide suivant">&#10095;</button>

    <div class="slider-track">
        <article class="slide slide-1 active">
            <div class="slide-overlay">
                <h3>Decouvrez les livres les plus recents</h3>
                <p>Chaque semaine, notre equipe ajoute de nouvelles lectures pour tous les niveaux et tous les gouts.</p>
            </div>
        </article>

        <article class="slide slide-2">
            <div class="slide-overlay">
                <h3>Reservez en ligne en moins d'une minute</h3>
                <p>Connectez-vous, choisissez votre livre et validez votre reservation sans deplacement.</p>
            </div>
        </article>

        <article class="slide slide-3">
            <div class="slide-overlay">
                <h3>Pilotez votre bibliotheque simplement</h3>
                <p>Ajoutez des livres, suivez les demandes et gerez les utilisateurs depuis un espace unique.</p>
            </div>
        </article>
    </div>

    <div class="slider-dots">
        <button type="button" class="dot active" data-dot="0" aria-label="Slide 1"></button>
        <button type="button" class="dot" data-dot="1" aria-label="Slide 2"></button>
        <button type="button" class="dot" data-dot="2" aria-label="Slide 3"></button>
    </div>
</section>

<section class="about-section reveal" id="apropos">
    <h2>A propos</h2>

    <p>
        Notre bibliotheque en ligne est un espace moderne dedie aux lecteurs, etudiants et passionnes de livres.
        Elle simplifie la consultation du catalogue et la reservation des ouvrages en quelques clics.
    </p>

    <p>
        Le projet est construit en PHP, MySQL, HTML, CSS et JavaScript avec une approche legere, performante et facile a maintenir.
        Notre objectif est d'offrir une experience claire pour les membres et une gestion intuitive pour les administrateurs.
    </p>

    <div class="about-points">
        <span>Collection variee</span>
        <span>Reservation instantanee</span>
        <span>Gestion centralisee</span>
        <span>Interface responsive</span>
    </div>
</section>

<section class="contact-section reveal" id="contact">
    <h2>Contact</h2>

    <p>Une question, une suggestion ou besoin d'aide ? Notre equipe vous repond rapidement.</p>

    <div class="contact-details">
        <p><strong>Email:</strong> contact@bibliotheque.local</p>
        <p><strong>Telephone:</strong> +216 00 000 000</p>
        <p><strong>Adresse:</strong> Avenue des Livres, Tunis</p>
        <p><strong>Horaires:</strong> Lundi - Samedi, 9h00 a 18h00</p>
    </div>

    <form class="contact-form" action="#" method="post" onsubmit="return false;">
        <label for="contact-name">Nom</label>
        <input id="contact-name" type="text" placeholder="Votre nom" required>

        <label for="contact-email">Email</label>
        <input id="contact-email" type="email" placeholder="Votre email" required>

        <label for="contact-message">Message</label>
        <textarea id="contact-message" rows="4" placeholder="Votre message" required></textarea>

        <button class="btn btn-primary" type="submit">Envoyer</button>
    </form>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>