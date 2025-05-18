<?php
require_once __DIR__ . '/admin/config.php';

try {
    $pdo = new PDO(DB, USER, PWD);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$title = $_POST["title"];
$slug = $_POST["slug"];
$description = $_POST["description"];
$level = $_POST["level"];
$category = $_POST["category"];
$xp_reward = $_POST["xp_reward"];

$banner = "basic.jpg";

if (isset($_FILES["banner_image"]) && $_FILES["banner_image"]["error"] == 0) {
    $uploaded_file = $_FILES["banner_image"];
    $upload_dir = __DIR__ . "/uploads/banners/course/";
    $file_name = uniqid() . "-" . basename($uploaded_file["name"]);
    $target_file = $upload_dir . $file_name;

    $allowed_types = ["image/jpeg", "image/png", "image/gif", "image/jpg"];
    if (in_array($uploaded_file["type"], $allowed_types)) {

        if (move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
            $banner = $file_name;
        } else {
            die("Erreur lors de l'upload du fichier.");
        }
    } else {
        die("Type de fichier non autorisé.");
    }
}

$stmt = $pdo->prepare("INSERT INTO sae203_course (title, slug, description, level, category, xp_reward, banner) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$title, $slug, $description, $level, $category, $xp_reward, $banner]);

$course_id = $pdo->lastInsertId();

if (isset($_POST["steps"])) {
    foreach ($_POST["steps"] as $step_index => $step) {
        $step_title = $step["title"];
        $step_xp = $step["xp_reward"];
        $step_order = $step_index + 1;

        $stmt = $pdo->prepare("INSERT INTO sae203_step (course_id, title, xp_reward, order_index) VALUES (?, ?, ?, ?)");
        $stmt->execute([$course_id, $step_title, $step_xp, $step_order]);

        $step_id = $pdo->lastInsertId();

        if (isset($step["lessons"])) {
            foreach ($step["lessons"] as $lesson_index => $lesson) {
                $lesson_title = $lesson["title"];
                $lesson_content = $lesson["content"];
                $lesson_order = $lesson_index + 1;

                $stmt = $pdo->prepare("INSERT INTO sae203_lesson (step_id, title, content, order_index) VALUES (?, ?, ?, ?)");
                $stmt->execute([$step_id, $lesson_title, $lesson_content, $lesson_order]);
            }
        }
    }
}

header('Location: coursesList.php');