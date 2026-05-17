<?php
// cookies/prefs.php
header("Content-Type: application/json");

// Durée de vie des cookies (30 jours)
$cookieDuration = time() + 30 * 24 * 3600;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Sauvegarder le thème
    if (isset($data['theme'])) {
        setcookie("bibliotheque_theme", $data['theme'], $cookieDuration, "/");
    }
    
    // Sauvegarder le nombre d'éléments par page
    if (isset($data['items_per_page'])) {
        setcookie("bibliotheque_items_per_page", $data['items_per_page'], $cookieDuration, "/");
    }
    
    // Sauvegarder la langue
    if (isset($data['language'])) {
        setcookie("bibliotheque_lang", $data['language'], $cookieDuration, "/");
    }
    
    echo json_encode(["success" => true]);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $prefs = [
        "theme" => $_COOKIE['bibliotheque_theme'] ?? "light",
        "items_per_page" => (int)($_COOKIE['bibliotheque_items_per_page'] ?? 12),
        "language" => $_COOKIE['bibliotheque_lang'] ?? "fr"
    ];
    
    echo json_encode($prefs);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Supprimer les cookies
    setcookie("bibliotheque_theme", "", time() - 3600, "/");
    setcookie("bibliotheque_items_per_page", "", time() - 3600, "/");
    setcookie("bibliotheque_lang", "", time() - 3600, "/");
    
    echo json_encode(["success" => true, "message" => "Préférences réinitialisées"]);
}
?>