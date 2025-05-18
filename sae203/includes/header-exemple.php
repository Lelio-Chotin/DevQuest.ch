<header>
    <a href="homepage.php" class="logo">./Dev<span>Quest</span></a>

    <button class="burger" aria-label="Menu">&#9776;</button>

    <ul class="nav">
        <li><a href="homepage.php">Accueil</a></li>
        <li><a href="coursesList.php">Cours</a></li>
        <li><a href="defis.php">Défis</a></li>
        <li><a href="leaderboard.php">Classement</a></li>
    </ul>

    <?php if (isset($_SESSION['user'])) { ?>
        <a class="connexion" href="profile.php"><?= htmlspecialchars($_SESSION['user']['prenom']) ?> <?= htmlspecialchars($_SESSION['user']['nom']) ?></a>
    <?php } else { ?>
        <a class="connexion" href="login.php">Connexion</a>
    <?php } ?>

</header>

<div class="mobile-menu">
    <button class="close-menu" aria-label="Fermer le menu">&times;</button>
    <ul>
        <li><a href="homepage.php">Accueil</a></li>
        <li><a href="coursesList.php">Cours</a></li>
        <li><a href="defis.php">Défis</a></li>
        <li><a href="leaderboard.php">Classement</a></li>
    </ul>
    <?php if (isset($_SESSION['user'])) { ?>
        <a class="connexion" href="profile.php"><?= htmlspecialchars($_SESSION['user']['prenom']) ?> <?= htmlspecialchars($_SESSION['user']['nom']) ?></a>
    <?php } else { ?>
        <a class="connexion" href="login.php">Connexion</a>
    <?php } ?>
</div>
