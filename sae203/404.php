<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/404.css">
    <title>404 | DevQuest</title>
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/header-exemple.php"; ?>

<div class="container">
    <div class="left"></div>
    <div class="right">
        <h1>Oops!</h1>
        <p>On dirait que la page que vous cherchez <br>n'existe pas ou a été déplacée.</p>
        <a href="/"><div class="btn">Retour à l’accueil</div></a>
    </div>
</div>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-js.php"; ?>
</body>
</html>
