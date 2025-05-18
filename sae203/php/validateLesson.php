<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/config.php";

if (!isset($_POST['lesson_id'])) {
    http_response_code(400);
    echo "Paramètre manquant.";
    exit;
}

$lessonId = intval($_POST['lesson_id']);
$userId = $_SESSION["user"]["id"] ?? null;

if (!$userId) {
    http_response_code(403);
    echo "Non autorisé.";
    exit;
}

try {
    $pdo = new PDO(DB, USER, PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("REPLACE INTO sae203_user_validated_lessons (user_id, lesson_id) VALUES (?, ?)");
    $stmt->execute([$userId, $lessonId]);

    echo "Validé.";
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur : " . $e->getMessage();
}
?>
