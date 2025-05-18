<?php
session_start();
require_once __DIR__ . '/admin/config.php';

if (!isset($_SESSION['user'])) {
    die("Vous devez être connecté pour soumettre un défi.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $defi_id = $_POST['defi_id'];
    $github_url = trim($_POST['github_url']);
    $user_id = $_SESSION['user']['id'];

    if (filter_var($github_url, FILTER_VALIDATE_URL) && str_contains($github_url, 'github.com')) {
        try {
            $pdo = new PDO(DB, USER, PWD);
            $stmt = $pdo->prepare("INSERT INTO sae203_defi_submission (defi_id, user_id, github_url) VALUES (?, ?, ?)");
            $stmt->execute([$defi_id, $user_id, $github_url]);

            header("Location: defis.php?success=1");
            exit;
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    } else {
        die("Lien GitHub invalide.");
    }
}
