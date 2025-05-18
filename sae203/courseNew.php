<?php
session_start();

require_once __DIR__ . '/admin/config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-css.php"; ?>
    <link rel="stylesheet" href="css/courseCreator.css">
    <title>Création de Cours | DevQuest</title>
</head>
<body>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/header-exemple.php"; ?>

<section class="crea">


    <form action="courseSave.php" method="POST" enctype="multipart/form-data">

        <h1>Création d'un nouveau cours</h1>

        <label>Titre :</label>
        <input type="text" name="title" required>

        <label>Slug (unique, sans espace) :</label>
        <input type="text" name="slug" required>

        <label>Description :</label>
        <textarea name="description" required></textarea>

        <label>Niveau :</label>
        <select name="level" required>
            <option value="Débutant">Débutant</option>
            <option value="Amateur">Amateur</option>
            <option value="Intermédiaire">Intermédiaire</option>
            <option value="Élevé">Élevé</option>
        </select>

        <label>Catégorie :</label>
        <select name="category" required>
            <option value="Parcours Complet">Parcours Complet</option>
            <option value="Parcours Annexe">Parcours Annexe</option>
        </select>


        <label>XP récompense :</label>
        <input type="number" name="xp_reward" value="0" required>

        <label>Image de bannière (optionnel) :</label>
        <input type="file" name="banner_image" accept="image/*">

        <label>Nombre d'étapes :</label>
        <input type="number" id="nb_steps" name="nb_steps" min="1" max="20" value="1" required>

        <button type="button" onclick="generateSteps()">Générer les étapes</button>

        <div id="steps-container"></div>

        <button type="submit">Créer le cours</button>
    </form>


</section>


<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/footer-exemple.php"; ?>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-js.php"; ?>
<script src="js/createCourses.js"></script>
</body>
</html>
