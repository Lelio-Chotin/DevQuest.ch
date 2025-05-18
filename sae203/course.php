<?php
session_start();

require_once __DIR__ . '/admin/config.php';

try {
    $pdo = new PDO(DB, USER, PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM sae203_course WHERE slug = ?");
$stmt->execute([$slug]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    die("Cours non trouvé.");
}

$stepsStmt = $pdo->prepare("SELECT * FROM sae203_step WHERE course_id = ? ORDER BY order_index ASC");
$stepsStmt->execute([$course['id']]);
$steps = $stepsStmt->fetchAll(PDO::FETCH_ASSOC);

$lessonsByStep = [];
$lessonsStmt = $pdo->prepare("SELECT * FROM sae203_lesson WHERE step_id = ? ORDER BY order_index ASC");


foreach ($steps as $step) {
    $lessonsStmt->execute([$step['id']]);
    $lessonsByStep[$step['id']] = $lessonsStmt->fetchAll(PDO::FETCH_ASSOC);
}

$firstLessonId = null;
foreach ($steps as $step) {
    if (!empty($lessonsByStep[$step['id']])) {
        $firstLessonId = $lessonsByStep[$step['id']][0]['id'];
        break;
    }
}

$userId = $_SESSION["user"]["id"] ?? null;
$validatedLessonIds = [];

if ($userId) {
    $stmt = $pdo->prepare("SELECT lesson_id FROM sae203_user_validated_lessons WHERE user_id = ?");
    $stmt->execute([$userId]);
    $validatedLessonIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
}


?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/course.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/vs2015.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <title><?= htmlspecialchars($course['title']) ?> | DevQuest</title>
    <style>

    </style>
</head>
<body>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/header-exemple.php"; ?>

<section class="list">
    <div class="dNav">
        <h2>Leçons:</h2>
        <nav class="lessonsNav">

            <?php foreach ($steps as $step): ?>
                <div class="step" style="width: calc(100% / <?= count($steps) ?> - 5px);">
                    <?php foreach ($lessonsByStep[$step['id']] as $lesson): ?>
                        <div class="lesson-button <?= in_array($lesson['id'], $validatedLessonIds) ? 'validated' : '' ?>"
                             data-lesson-id="<?= $lesson['id'] ?>"
                             onclick="loadLessonContent(<?= $lesson['id'] ?>)"
                             style="width: calc(100% / <?= count($lessonsByStep[$step['id']]) ?> )">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </nav>
    </div>


    <button id="toggleDrawer">Leçons</button>
    <div id="lessonDrawer">
        <?php foreach ($steps as $step): ?>
            <div class="step-drawer">
                <strong><?= htmlspecialchars($step['title'] ?? 'Étape') ?></strong>
                <ul>
                    <?php foreach ($lessonsByStep[$step['id']] as $lesson): ?>
                        <li class="lesson <?= in_array($lesson['id'], $validatedLessonIds) ? 'validated' : '' ?>"
                            data-lesson-id="<?= $lesson['id'] ?>"
                            onclick="loadLessonContent(<?= $lesson['id'] ?>)">
                            <?= htmlspecialchars($lesson['title']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="lessonOverlay"></div>



    <div id="lesson-content" class="lio">
        <p><em>Chargement de la première leçon...</em></p>
    </div>

</section>


<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/footer-exemple.php"; ?>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/pageTransition.php"; ?>

<script src="js/cours.js"></script>
<script>
    <?php if ($firstLessonId): ?>
    window.addEventListener('DOMContentLoaded', function () {
        loadLessonContent(<?= $firstLessonId ?>);
    });
    <?php endif; ?>
</script>

<script>
    const toggleBtn = document.getElementById('toggleDrawer');
    const drawer = document.getElementById('lessonDrawer');
    const overlay = document.getElementById('lessonOverlay');

    toggleBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        drawer.classList.toggle('active');
        overlay.classList.toggle('active');
    });

    document.addEventListener('click', (e) => {
        if (!drawer.contains(e.target) && e.target !== toggleBtn) {
            drawer.classList.remove('active');
            overlay.classList.remove('active');
        }
    });
</script>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-js.php"; ?>

</body>
</html>
