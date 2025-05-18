<?php
session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/config.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/php/parser.php";

try {
    $pdo = new PDO(DB, USER, PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$lesson_id = $_GET['lesson_id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM sae203_lesson WHERE id = ?");
$stmt->execute([$lesson_id]);
$lesson = $stmt->fetch(PDO::FETCH_ASSOC);

if ($lesson) {
    echo "<h1>" . htmlspecialchars($lesson['title']) . "</h1>";
    echo "<div id='lesson-parsed'>" . parseLIO($lesson['content']) . "</div>";

    if (isset($_SESSION["user"]["admin"]) && $_SESSION["user"]["admin"] == 1) {
        echo "<button class='adminBtn' id='edit-btn'>Modifier</button><br>";

        echo "<form id='edit-form' data-lesson-id='" . intval($lesson['id']) . "' style='display:none; margin-top:1em;'>
                <textarea id='edit-content' rows='15' style='width:100%;'>" . htmlspecialchars($lesson['content']) . "</textarea>
                <div style='margin-top:0.5em;'>
                    <button class='adminBtn' type='submit'>Sauvegarder</button>
                    <button class='adminBtn' type='button' id='cancel-btn'>Annuler</button>
                </div>
              </form>";
    }

    $userId = $_SESSION["user"]["id"] ?? null;

    $isValidated = false;
    if ($userId) {
        $stmt = $pdo->prepare("SELECT 1 FROM sae203_user_validated_lessons WHERE user_id = ? AND lesson_id = ?");
        $stmt->execute([$userId, $lesson['id']]);
        $isValidated = (bool)$stmt->fetchColumn();
    }

    $isLastOfStep = false;
    $stmt = $pdo->prepare("SELECT id FROM sae203_lesson WHERE step_id = ? ORDER BY order_index DESC LIMIT 1");
    $stmt->execute([$lesson['step_id']]);
    $lastLessonId = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT id FROM sae203_step ORDER BY order_index DESC LIMIT 1");
    $lastStepId = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT id FROM sae203_lesson WHERE step_id = ? ORDER BY order_index DESC LIMIT 1");
    $stmt->execute([$lastStepId]);
    $lastLessonOfLastStepId = $stmt->fetchColumn();

    $isUltimateLesson = ($lesson['id'] == $lastLessonOfLastStepId);


    if ($userId) {
        if ($isValidated) {
            echo "<button class='validate-btn validated' onclick='toggleLessonValidation({$lesson['id']}, false)'>Dévalider</button>";
        } else {
            echo "<button class='validate-btn' onclick='toggleLessonValidation({$lesson['id']}, true)'>Valider</button>";
        }
    }

    $nextStmt = $pdo->prepare("SELECT * FROM sae203_lesson WHERE step_id = ? AND order_index > ? ORDER BY order_index ASC LIMIT 1");
    $nextStmt->execute([$lesson['step_id'], $lesson['order_index']]);
    $nextLesson = $nextStmt->fetch(PDO::FETCH_ASSOC);

    if ($nextLesson) {
        echo "<button class='nextBtn' onclick='loadLessonContent({$nextLesson['id']})'>Chapitre suivant</button>";
    } else {
        echo "<p class='lastChap'><em>Dernier chapitre de cette étape.</em></p>";
    }

} else {
    echo "<p>Leçon non trouvée.</p>";
}
