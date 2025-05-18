<?php
session_start();
require_once __DIR__ . '/admin/config.php';

if (!isset($_POST['age'], $_POST['user_id']) || !isset($_SESSION['user']) || $_SESSION['user']['id'] != $_POST['user_id']) {
    http_response_code(403);
    echo "Non autorisé.";
    exit;
}

$age = intval($_POST['age']);
if ($age < 1 || $age > 120) {
    $age = 120;
}

try {
    $pdo = new PDO(DB, USER, PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("UPDATE sae203_user SET age = :age WHERE id = :id");
    $stmt->execute(['age' => $age, 'id' => intval($_POST['user_id'])]);

    echo $age;
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur : " . $e->getMessage();
}
?>
