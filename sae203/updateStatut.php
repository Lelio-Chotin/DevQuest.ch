<?php
session_start();
require_once __DIR__ . '/admin/config.php';

if (!isset($_POST['statut'], $_POST['user_id']) || !isset($_SESSION['user']) || $_SESSION['user']['id'] != $_POST['user_id']) {
    http_response_code(403);
    echo "Non autorisé.";
    exit;
}

$statut = trim($_POST['statut']);
$allowed = ['Étudiant', 'Développeur', 'Professeur', 'Admin', 'Autre'];

if (!in_array($statut, $allowed)) {
    http_response_code(400);
    echo "Statut non autorisé.";
    exit;
}

try {
    $pdo = new PDO(DB, USER, PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("UPDATE sae203_user SET statut = :statut WHERE id = :id");
    $stmt->execute(['statut' => $statut, 'id' => intval($_POST['user_id'])]);

    echo htmlspecialchars($statut);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur : " . $e->getMessage();
}
