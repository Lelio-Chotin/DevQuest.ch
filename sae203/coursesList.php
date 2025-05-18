<?php
session_start();

require_once __DIR__ . '/admin/config.php';

try {
    $pdo = new PDO(DB, USER, PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$query = $pdo->query("SELECT * FROM sae203_course");
$courses = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/courseList.css">
    <title>Cours | DevQuest</title>
</head>
<body>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/header-exemple.php"; ?>

<section class="list">

    <div class="mobile-controls">
        <input type="text" id="searchInput" placeholder="Rechercher un cours...">
        <div class="filter-buttons">
            <button class="active" onclick="applyFilter('Parcours Complet')">Parcours Web</button>
            <button onclick="applyFilter('Parcours Annexe')">Cours Annexes</button>
        </div>
    </div>

    <div class="carousel-container">
        <button class="prev">←</button>
        <div class="carouselOverflow">

            <h1>Parcours Web</h1>
            <ul class="carousel-list">
                <?php foreach ($courses as $course): ?>
                    <?php if ($course['category'] == "Parcours Complet"): ?>
                        <a href="course.php?slug=<?= $course['slug'] ?>">
                            <li class="coursElem"
                                data-category="<?= $course['category'] ?>"
                                data-title="<?= strtolower($course['title']) ?>">
                                <img src="uploads/banners/course/<?= $course['banner'] ?>" alt="banner">
                                <h2><?= htmlspecialchars($course['title']) ?></h2>
                                <p class="desc"><?= htmlspecialchars($course['description']) ?></p>
                                <div class="hideDesc"></div>
                                <p class="level"><?= $course['level'] ?></p>
                            </li>
                        </a>



                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <button class="next">→</button>
    </div>


    <h1 class="annex-title">Cours Annexes</h1>
    <ul class="annexes">
        <?php foreach ($courses as $course): ?>
            <?php if ($course['category'] == "Parcours Annexe"): ?>
                <a href="course.php?slug=<?= $course['slug'] ?>">
                    <li class="coursElem">
                        <img src="uploads/banners/course/<?= $course['banner'] ?>" alt="banner">
                        <h2><?= htmlspecialchars($course['title']) ?></h2>
                        <p class="desc"><?= htmlspecialchars($course['description']) ?></p>
                        <div class="hideDesc"></div>
                        <p class="level"><?= $course['level'] ?></p>
                    </li>
                </a>

            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

</section>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/footer-exemple.php"; ?>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/pageTransition.php"; ?>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-js.php"; ?>
<script src="js/carousel.js"></script>
<script src="js/coursList.js"></script>
</body>
</html>