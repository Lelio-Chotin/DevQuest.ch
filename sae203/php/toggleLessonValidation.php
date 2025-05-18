<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/config.php";

$lessonId = intval($_POST['lesson_id'] ?? 0);
$validate = $_POST['validate'] === 'true';
$userId = $_SESSION["user"]["id"] ?? null;

if (!$lessonId || !$userId) {
    http_response_code(400);
    echo "Paramètres manquants.";
    exit;
}

try {
    $pdo = new PDO(DB, USER, PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($validate) {
        $stmt = $pdo->prepare("REPLACE INTO sae203_user_validated_lessons (user_id, lesson_id) VALUES (?, ?)");
        $stmt->execute([$userId, $lessonId]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM sae203_user_validated_lessons WHERE user_id = ? AND lesson_id = ?");
        $stmt->execute([$userId, $lessonId]);
    }

    $stmt = $pdo->prepare("SELECT step_id FROM sae203_lesson WHERE id = ?");
    $stmt->execute([$lessonId]);
    $stepId = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM sae203_lesson WHERE step_id = ? AND id IN (SELECT lesson_id FROM sae203_user_validated_lessons WHERE user_id = ?)");
    $stmt->execute([$stepId, $userId]);
    $validatedCount = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM sae203_lesson WHERE step_id = ?");
    $stmt->execute([$stepId]);
    $totalLessons = $stmt->fetchColumn();

    if ($validate) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM sae203_lesson WHERE step_id = ?");
        $stmt->execute([$stepId]);
        $totalLessons = $stmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT xp_reward FROM sae203_step WHERE id = ?");
        $stmt->execute([$stepId]);
        $stepXpReward = $stmt->fetchColumn();

        $xpPerLesson = floor($stepXpReward / $totalLessons);

        $stmt = $pdo->prepare("SELECT 1 FROM sae203_user_lesson_xp WHERE user_id = ? AND lesson_id = ?");
        $stmt->execute([$userId, $lessonId]);

        if (!$stmt->fetchColumn()) {
            $stmt = $pdo->prepare("INSERT INTO sae203_user_lesson_xp (user_id, lesson_id, step_id, xp_reward) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $lessonId, $stepId, $xpPerLesson]);

            $stmt = $pdo->prepare("UPDATE sae203_user SET xp = xp + ? WHERE id = ?");
            $stmt->execute([$xpPerLesson, $userId]);

            echo "XP ajouté : " . $xpPerLesson;
        }
    }



    echo "validé";
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur : " . $e->getMessage();
}
?>
