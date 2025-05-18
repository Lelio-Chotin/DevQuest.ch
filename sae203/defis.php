<?php
session_start();

require_once __DIR__ . '/admin/config.php';

try {
    $pdo = new PDO(DB, USER, PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$query = $pdo->query("SELECT * FROM sae203_defi");
$defis = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="fr">
<head>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-css.php"; ?>
    <link rel="stylesheet" href="./css/defis.css">
    <title>Défis | DevQuest</title>
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/pageTransition.php"; ?>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/header-exemple.php"; ?>


<section class="list">
    <h1>Defis Dev</h1>
    <ul class="annexes">
        <?php foreach ($defis as $defi): ?>
            <?php if ($defi['type'] == "Constant"): ?>
                <div class="def">
                    <li class="coursElem"
                        data-title="<?= htmlspecialchars($defi['title']) ?>"
                        data-description="<?= htmlspecialchars($defi['description']) ?>"
                        data-banner="uploads/banners/course/<?= $defi['banner'] ?>"
                        data-level="<?= $defi['level'] ?>"
                        data-xp="<?= $defi['xp_reward'] ?>"
                        data-deadline="<?= $defi['deadline'] ?>"
                        data-id="<?= $defi['id'] ?>"
                    >
                        <img src="uploads/banners/course/<?= $defi['banner'] ?>" alt="banner">
                        <h2><?= htmlspecialchars($defi['title']) ?></h2>
                        <p class="desc"><?= htmlspecialchars($defi['description']) ?></p>
                        <div class="hideDesc"></div>
                        <p class="level"><?= $defi['level'] ?></p>
                    </li>
                </div>

            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <div id="defiModal" class="modal">
        <div class="modal-content">
            <img id="modalBanner" src="" alt="Banner"/>
            <h2 id="modalTitle"></h2>
            <p id="modalDescription"></p>
            <p><strong>Niveau :</strong> <span id="modalLevel"></span></p>
            <p><strong>XP :</strong> <span id="modalXP"></span></p>
            <p id="deadlineP"><strong>Date limite :</strong> <span id="modalDeadline"></span></p>
            <form id="githubSubmitForm" method="POST" action="submit_defi.php">
                <input type="hidden" name="defi_id" id="defiIdInput">
                <label for="github_url"><p><strong>Lien vers ton dépôt GitHub :</strong></p></label>
                <input type="url" name="github_url" id="github_url" placeholder="https://github.com/..." required>
                <button type="submit">Soumettre le défi</button>
            </form>
        </div>
    </div>
</section>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/footer-exemple.php"; ?>

<script src="js/modal.js"></script>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-js.php"; ?>
</body>
</html>