<?php
session_start();
require_once __DIR__ . '/admin/config.php';

if (!isset($_POST['bio'], $_POST['user_id']) || !isset($_SESSION['user']) || $_SESSION['user']['id'] != $_POST['user_id']) {
    http_response_code(403);
    echo "Non autorisé.";
    exit;
}

$bio = trim($_POST['bio']);
$user_id = intval($_POST['user_id']);

try {
    $pdo = new PDO(DB, USER, PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("UPDATE sae203_user SET bio = :bio WHERE id = :id");
    $stmt->execute(['bio' => $bio, 'id' => $user_id]);

    echo htmlspecialchars($bio);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur : " . $e->getMessage();
}
?>
