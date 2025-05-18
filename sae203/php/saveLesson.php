<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/config.php";

header('Content-Type: text/plain'); // aide au debug

if (!isset($_SESSION["user"]) || $_SESSION["user"]["admin"] != 1) {
    http_response_code(403);
    echo "Accès refusé.";
    exit;
}

$id = $_POST['id'] ?? null;
$content = $_POST['content'] ?? null;

if (!$id || $content === null) {
    http_response_code(400);
    echo "ID ou contenu manquant.";
    exit;
}

try {
    $pdo = new PDO(DB, USER, PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("UPDATE sae203_lesson SET content = ? WHERE id = ?");
    $stmt->execute([$content, $id]);

    if ($stmt->rowCount() > 0) {
        echo "Contenu mis à jour avec succès.";
    } else {
        echo "Aucune mise à jour effectuée. Vérifiez l'ID : $id.";
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur SQL : " . $e->getMessage();
}
