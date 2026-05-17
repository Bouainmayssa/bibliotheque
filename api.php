<?php
// api/stats.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . "/../includes/db.php";

// Vérifier si admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    echo json_encode(["error" => "Accès non autorisé"]);
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

// 5 derniers livres ajoutés
$recentBooks = $pdo->query("
    SELECT id, title, author, image, date_ajout 
    FROM books 
    ORDER BY id DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Réservations par mois (pour graphique)
$reservationsByMonth = $pdo->query("
    SELECT 
        DATE_FORMAT(date, '%Y-%m') as month,
        COUNT(*) as count,
        SUM(CASE WHEN status = 'acceptee' THEN 1 ELSE 0 END) as accepted
    FROM reservations 
    WHERE date IS NOT NULL
    GROUP BY DATE_FORMAT(date, '%Y-%m')
    ORDER BY month DESC 
    LIMIT 6
")->fetchAll(PDO_FETCH_ASSOC);

// Top 5 des livres les plus réservés
$topBooks = $pdo->query("
    SELECT b.title, b.author, COUNT(r.id) as reservations_count
    FROM books b
    JOIN reservations r ON b.id = r.book_id
    GROUP BY b.id
    ORDER BY reservations_count DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Nombre de réservations aujourd'hui
$todayReservations = (int)$pdo->query("
    SELECT COUNT(*) FROM reservations 
    WHERE DATE(date) = CURDATE()
")->fetchColumn();

// Résultat final
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
    "top_books" => $topBooks,
    "last_update" => date("Y-m-d H:i:s")
];

echo json_encode($stats, JSON_PRETTY_PRINT);
?>