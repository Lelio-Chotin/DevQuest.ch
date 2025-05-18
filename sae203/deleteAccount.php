<?php
session_start();

require_once __DIR__ . '/admin/config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id']) && $_POST['user_id'] == $_SESSION['user']['id']) {
        $user_id = $_POST['user_id'];

        try {
            $pdo = new PDO(DB, USER, PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql_courses = "DELETE FROM sae203_user_validated_lessons WHERE user_id = :user_id";
            $stmt_courses = $pdo->prepare($sql_courses);
            $stmt_courses->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_courses->execute();

            $sql_user = "DELETE FROM sae203_user WHERE id = :user_id";
            $stmt_user = $pdo->prepare($sql_user);
            $stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_user->execute();

            session_destroy();
            header("Location: login.php?message=compte-supprime");
            exit();

        } catch (PDOException $e) {
            die("Erreur lors de la suppression du compte : " . $e->getMessage());
        }
    } else {
        echo "Vous n'êtes pas autorisé à supprimer ce compte.";
        exit();
    }
} else {
    echo "Requête invalide.";
    exit();
}
