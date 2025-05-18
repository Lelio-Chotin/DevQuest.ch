<?php
session_start();

require_once __DIR__ . '/admin/config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

try {
    $pdo = new PDO(DB, USER, PWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$user_id = $_SESSION['user']['id'];

$sql = "SELECT * FROM sae203_user WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo "Utilisateur non trouvé.";
    exit();
}

$sql_total_xp = "SELECT SUM(xp_reward) AS total_xp FROM sae203_course";
$stmt_total_xp = $pdo->prepare($sql_total_xp);
$stmt_total_xp->execute();
$total_xp = $stmt_total_xp->fetch(PDO::FETCH_ASSOC)['total_xp'];
$percentage = ($total_xp > 0) ? ($user['xp'] / $total_xp) * 100 : 0;
if ($percentage > 100) {
    $percentage = 100;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-css.php"; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/profile.css">
    <title>Accueil | DevQuest</title>
</head>
<body>
<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/pageTransition.php"; ?>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/header-exemple.php"; ?>

<div class="profile">
    <div class="banner-container">
        <img src="uploads/banners/course/<?= htmlspecialchars($user['banner']) ?>" alt="Bannière" id="bannerImage"
             class="banner">
        <i class="fas fa-pen edit-icon"></i>
    </div>
    <form action="updateBanner.php" method="POST" enctype="multipart/form-data" id="bannerForm">
        <input type="file" id="bannerInput" name="banner_image" style="display: none;" accept="image/*"
               onchange="submitBannerForm();">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
    </form>
    <div class="profile-info">
        <div class="pfp">
            <div class="pfp-container">
                <img src="uploads/pfp/<?= htmlspecialchars($user['pfp']) ?>" alt="Photo de profil" id="profileImage">
                <i class="fas fa-pen edit-icon"></i>
            </div>
            <form action="updateProfilePicture.php" method="POST" enctype="multipart/form-data" id="uploadForm">
                <input type="file" id="fileInput" name="pfp" style="display: none;" accept="image/*"
                       onchange="submitForm();">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
            </form>
        </div>
        <div class="texts">
            <p>
                <span id="statut-text"><?= htmlspecialchars($user['statut']) ?></span>
                <form id="statut-form" style="display: none;">
                    <select id="statut-select" name="statut">
                        <?php if (isset($_SESSION["user"]) and $_SESSION["user"]["admin"] == 1) { ?>
                            <option value="Admin">Admin</option>
                        <?php } ?>
                        <option value="Étudiant">Étudiant</option>
                        <option value="Professeur">Professeur</option>
                        <option value="Développeur">Développeur</option>
                        <option value="Autre">Autre</option>
                    </select>
                </form>
            </p>

            <div class="namexp">
                <h2>
                    <?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?>,
                    <span id="age-text"><?= htmlspecialchars($user['age']) ?><span> ans</span></span>
                    <form id="age-form" style="display: none;">
                        <input type="number" id="age-input" name="age" value="<?= (int)$user['age'] ?>" min="1"
                               max="120" style="width: 60px;">
                    </form>

                </h2>
                <p class="xp"><strong>XP : <?= $user['xp'] ?></strong></p>
            </div>
            <div class="xp-progress-container">
                <div class="xp-progress-bar">
                    <div class="xp-progress" style="width: <?= $percentage ?>%;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="bio">
        <h2>Bio :</h2>
        <p id="bio-text"><?= htmlspecialchars($user['bio']) ?></p>
        <form id="bio-form" action="updateBio.php" method="POST" style="display: none;">
            <textarea name="bio" id="bio-input" rows="3"
                      style="width: 100%;"><?= htmlspecialchars($user['bio']) ?></textarea>
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
        </form>
    </div>


    <div class="card">
        <div class="compte">
            <a href="logout.php" class="leave">Déconnexion</a>
            <form action="deleteAccount.php" method="POST">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                <button type="submit" class="leave">Supprimer le compte</button>
            </form>
        </div>
        <?php if (isset($_SESSION["user"]) and $_SESSION["user"]["admin"] == 1) { ?>
            <a href="courseNew.php" class="leave">Créer un cours</a>
        <?php } ?>
    </div>

</div>

<script type="text/javascript">
    document.querySelector("#bannerImage").addEventListener("click", function () {
        document.getElementById('bannerInput').click();
    });

    function submitBannerForm() {
        document.getElementById('bannerForm').submit();
    }

    document.querySelector("#profileImage").addEventListener("click", function () {
        document.getElementById('fileInput').click();
    });

    function submitForm() {
        document.getElementById('uploadForm').submit();
    }


    const bioText = document.getElementById('bio-text');
    const bioForm = document.getElementById('bio-form');
    const bioInput = document.getElementById('bio-input');

    bioText.addEventListener('click', () => {
        bioText.style.display = 'none';
        bioForm.style.display = 'block';
        bioInput.focus();
    });

    bioInput.addEventListener('blur', submitBio);
    bioInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            submitBio();
        }
    });

    function submitBio() {
        fetch('updateBio.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'bio=' + encodeURIComponent(bioInput.value) + '&user_id=' + <?= json_encode($user['id']) ?>
        })
            .then(response => response.text())
            .then(newBio => {
                bioText.textContent = newBio;
                bioText.style.display = 'block';
                bioForm.style.display = 'none';
            });
    }


    const ageText = document.getElementById('age-text');
    const ageForm = document.getElementById('age-form');
    const ageInput = document.getElementById('age-input');

    ageText.addEventListener('click', () => {
        ageText.style.display = 'none';
        ageForm.style.display = 'inline';
        ageInput.focus();
    });

    ageInput.addEventListener('blur', submitAge);
    ageInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitAge();
        }
    });

    function submitAge() {
        fetch('updateAge.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'age=' + encodeURIComponent(ageInput.value) + '&user_id=' + <?= json_encode($user['id']) ?>
        })
            .then(response => response.text())
            .then(newAge => {
                ageText.textContent = newAge + " ans";
                ageText.style.display = 'inline';
                ageForm.style.display = 'none';
            });
    }




    const statutText = document.getElementById('statut-text');
    const statutForm = document.getElementById('statut-form');
    const statutSelect = document.getElementById('statut-select');

    statutText.addEventListener('click', () => {
        statutText.style.display = 'none';
        statutForm.style.display = 'inline';
        statutSelect.value = statutText.textContent.trim();
        statutSelect.focus();
    });

    statutSelect.addEventListener('blur', submitStatut);
    statutSelect.addEventListener('change', submitStatut);

    function submitStatut() {
        fetch('updateStatut.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'statut=' + encodeURIComponent(statutSelect.value) + '&user_id=' + <?= json_encode($user['id']) ?>
        })
            .then(response => response.text())
            .then(newStatut => {
                statutText.textContent = newStatut;
                statutText.style.display = 'inline';
                statutForm.style.display = 'none';
            });
    }

</script>

<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/includes/load-js.php"; ?>
</body>
</html>
