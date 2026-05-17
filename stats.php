<?php
// api/stats.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");

// FORCER le démarrage de session AVANT tout
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../includes/db.php";

// Debug temporaire - à retirer après
error_log("Session user_id: " . ($_SESSION["user_id"] ?? "non défini"));
error_log("Session role: " . ($_SESSION["role"] ?? "non défini"));

// Vérifier si admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    echo json_encode([
        "error" => "Accès non autorisé",
        "debug" => [
            "session_started" => session_status() === PHP_SESSION_ACTIVE,
            "user_id_exists" => isset($_SESSION["user_id"]),
            "role" => $_SESSION["role"] ?? "non défini"
        ]
    ]);
    exit;
}

// Statistiques de base
$totalBooks = (int)$pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
$totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalReservations = (int)$pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();

// Réservations par statut
$pendingReservations = (int)$pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'en_attente'")->fetchColumn();
$acceptedReservations = (int)$pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'acceptee'")->fetchColumn();
$refusedReservations = (int)$pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'refusee'")->fetchColumn();

// Messages non lus
$unreadMessages = (int)$pdo->query("SELECT COUNT(*) FROM contacts WHERE lu = 0")->fetchColumn();

// Réservations aujourd'hui
$todayReservations = (int)$pdo->query("
    SELECT COUNT(*) FROM reservations 
    WHERE DATE(date) = CURDATE()
")->fetchColumn();

// Derniers livres
$recentBooks = $pdo->query("
    SELECT id, title, author 
    FROM books 
    ORDER BY id DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Top livres
$topBooks = $pdo->query("
    SELECT b.title, COUNT(r.id) as reservations_count
    FROM books b
    LEFT JOIN reservations r ON b.id = r.book_id
    GROUP BY b.id
    ORDER BY reservations_count DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Réservations par mois
$reservationsByMonth = $pdo->query("
    SELECT 
        DATE_FORMAT(date, '%Y-%m') as month,
        COUNT(*) as count
    FROM reservations 
    WHERE date IS NOT NULL
    GROUP BY DATE_FORMAT(date, '%Y-%m')
    ORDER BY month DESC 
    LIMIT 6
")->fetchAll(PDO::FETCH_ASSOC);

$stats = [
    "total_books" => $totalBooks,
    "total_users" => $totalUsers,
    "total_reservations" => $totalReservations,
    "pending_reservations" => $pendingReservations,
    "accepted_reservations" => $acceptedReservations,
    "refused_reservations" => $refusedReservations,
    "unread_messages" => $unreadMessages,
    "today_reservations" => $todayReservations,
    "recent_books" => $recentBooks,
    "reservations_by_month" => $reservationsByMonth,
    "top_books" => $topBooks
];

echo json_encode($stats, JSON_PRETTY_PRINT);
?>