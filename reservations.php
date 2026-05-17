<?php
// api/reservations.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . "/../includes/db.php";

// Vérifier connexion
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Non authentifié"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$userId = $_SESSION["user_id"];
$isAdmin = ($_SESSION["role"] ?? '') === 'admin';

if ($method === 'GET') {
    if ($isAdmin && isset($_GET['all'])) {
        // Admin voit toutes les réservations
        $stmt = $pdo->query("
            SELECT r.*, u.name as user_name, b.title as book_title
            FROM reservations r
            JOIN users u ON u.id = r.user_id
            JOIN books b ON b.id = r.book_id
            ORDER BY r.date DESC
        ");
    } else {
        // Utilisateur voit ses réservations
        $stmt = $pdo->prepare("
            SELECT r.*, b.title, b.author, b.image
            FROM reservations r
            JOIN books b ON b.id = r.book_id
            WHERE r.user_id = ?
            ORDER BY r.date DESC
        ");
        $stmt->execute([$userId]);
    }
    
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reservations);
}

if ($method === 'POST' && !$isAdmin) {
    // Créer une réservation
    $data = json_decode(file_get_contents("php://input"), true);
    $bookId = (int)($data['book_id'] ?? 0);
    
    if ($bookId <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "ID du livre requis"]);
        exit;
    }
    
    // Vérifier si déjà réservé
    $check = $pdo->prepare("
        SELECT id FROM reservations 
        WHERE user_id = ? AND book_id = ? AND status IN ('en_attente', 'acceptee')
    ");
    $check->execute([$userId, $bookId]);
    
    if ($check->fetch()) {
        echo json_encode(["error" => "Vous avez déjà réservé ce livre"]);
        exit;
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO reservations (user_id, book_id, status) 
        VALUES (?, ?, 'en_attente')
    ");
    $stmt->execute([$userId, $bookId]);
    
    echo json_encode(["success" => true, "message" => "Réservation effectuée"]);
}
?>