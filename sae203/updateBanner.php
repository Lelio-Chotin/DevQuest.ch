<?php
session_start();

require_once __DIR__ . '/admin/config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['banner_image']) && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $file = $_FILES['banner_image'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Erreur lors de l'upload du fichier. Code d'erreur: " . $file['error']);
    }

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_extensions)) {
        die("Format de fichier non autorisé. Seules les images sont acceptées.");
    }

    $new_file_name = uniqid() . '.' . $file_extension;

    $target_dir = __DIR__ . '/uploads/banners/course/';
    $target_file = $target_dir . $new_file_name;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        try {
            $pdo = new PDO(DB, USER, PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE sae203_user SET banner = :banner WHERE id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':banner', $new_file_name);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: profile.php");
            exit();

        } catch (PDOException $e) {
            die("Erreur lors de la mise à jour de la bannière : " . $e->getMessage());
        }
    } else {
        die("Une erreur est survenue lors de l'upload du fichier.");
    }
} else {
    die("Erreur dans la soumission du formulaire.");
}
?>
