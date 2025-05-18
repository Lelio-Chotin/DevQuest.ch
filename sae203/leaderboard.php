<?php
session_start();

require_once __DIR__ . '/admin/config.php';

$conn = new PDO(DB, USER, PWD);


$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'xp';
$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'DESC';


if ($sortBy == 'xp') {
    $orderClause = "ORDER BY xp $sortOrder";
} elseif ($sortBy == 'alphabetical') {
    $orderClause = "ORDER BY prenom ASC, nom ASC";
} else {
    $orderClause = "ORDER BY xp DESC";
}

$stmt = $conn->prepare("SELECT * FROM sae203_user $orderClause");
$stmt->execute();
$topUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['sortBy'])) {
    echo json_encode($topUsers);
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/leaderboard.css">
    <title>Classement | DevQuest</title>
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/pageTransition.php"; ?>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/header-exemple.php"; ?>


<div class="container">
    <section class="leaderboard">
        <div class="top">
            <h1>Le <span>Classement</span> des <br> Apprenants</h1>
            <div class="search-container">
                <input type="text" id="search" placeholder="Rechercher un apprenant...">
            </div>
        </div>
        <div class="leader">
            <?php for ($i = 0; $i < count($topUsers); $i++): ?>
                <div class="user">
                    <div class="left">
                        <img class="icon" src="./uploads/pfp/<?= $topUsers[$i]["pfp"] ?>" alt="profile pic">
                        <div class="text">
                            <span class="statut"><?= $topUsers[$i]["statut"] ?></span>
                            <div class="nom"><?= $topUsers[$i]["prenom"] . " " . $topUsers[$i]["nom"] . ", " . $topUsers[$i]["age"] ?></div>
                            <p class="xp">XP : <?= $topUsers[$i]["xp"] ?></p>
                        </div>
                    </div>
                    <div class="classement">
                        <span class="num"><?= $i + 1 ?></span>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </section>

</div>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/footer-exemple.php"; ?>

<script src="js/sortSearch.js"></script>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-js.php"; ?>
</body>
</html>