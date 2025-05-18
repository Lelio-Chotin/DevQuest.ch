<?php
session_start();

require_once __DIR__ . '/admin/config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pfp']) && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $file = $_FILES['pfp'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Erreur lors de l'upload du fichier.";
        exit();
    }


    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_extensions)) {
        echo "Format de fichier non autorisé. Seules les images sont acceptées.";
        exit();
    }

    $new_file_name = uniqid() . '.' . $file_extension;

    $target_dir = __DIR__ . '/uploads/pfp/';
    $target_file = $target_dir . $new_file_name;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        try {
            $pdo = new PDO(DB, USER, PWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE sae203_user SET pfp = :pfp WHERE id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':pfp', $new_file_name);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: profile.php");
            exit();

        } catch (PDOException $e) {
            die("Erreur lors de la mise à jour de la photo de profil : " . $e->getMessage());
        }
    } else {
        echo "Erreur lors de l'upload du fichier.";
    }
} else {
    echo "Erreur dans la soumission du formulaire.";
}
?>
