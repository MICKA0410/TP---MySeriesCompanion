<?php

require '../inc/bdd.php';
require '../inc/fonctions-series.php';
require '../inc/fonctions-saisons.php';

$serieId = $_GET['id'] ?? null;



if (!$serieId) {
    header('Location: index.php');
    exit;
}

$serie = getSerieById($pdo, $serieId);


if (!$serie) {
    header('Location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $resume = trim($_POST['resume'] ?? '');
    $vignette = trim($_POST['vignette'] ?? '');
    $dateSortie = $_POST['date_sortie'] ?? '';

    if ($nom === '') {
        $errors[] = "Le nom est obligatoire.";
    }
    if ($dateSortie === '') {
        $errors[] = "La date de sortie est obligatoire.";
    }

    if (empty($errors)) {
        addSaison($pdo, $nom, $resume ?: null, $vignette ?: null, $dateSortie, $serieId);

       
        header('Location: serie-detail.php?id=' . $serieId);
        exit;
    }
}

$saisons = getSaisonsBySerieId($pdo, $serieId);

include '../inc/entete.php';
?>

<a href="index.php">&larr; Retour à la liste des séries</a>

<h1><?= htmlspecialchars($serie['nom']) ?></h1>

<?php if (!empty($serie['vignette'])): ?>
    <img src="<?= htmlspecialchars($serie['vignette']) ?>" alt="<?= htmlspecialchars($serie['nom']) ?>">
<?php endif; ?>

<p>Sortie le : <?= htmlspecialchars($serie['date_sortie']) ?></p>

<?php if (!empty($serie['resume'])): ?>
    <p><?= htmlspecialchars($serie['resume']) ?></p>
<?php endif; ?>

<hr>

<h2>Saisons</h2>

<?php if (empty($saisons)): ?>
    <p>Aucune saison ajoutée pour le moment.</p>
<?php else: ?>
    <ul>
        <?php foreach ($saisons as $saison): ?>
            <li>
                <a href="saison-detail.php?id=<?= $saison['id'] ?>">
                    <?= htmlspecialchars($saison['nom']) ?>
                </a>
                (<?= htmlspecialchars($saison['date_sortie']) ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h3>Ajouter une saison</h3>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="serie-detail.php?id=<?= $serieId ?>">

    <div>
        <label for="nom">Nom *</label><br>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom ?? '') ?>">
    </div>

    <div>
        <label for="resume">Résumé</label><br>
        <textarea id="resume" name="resume"><?= htmlspecialchars($resume ?? '') ?></textarea>
    </div>

    <div>
        <label for="vignette">Vignette (URL de l'image)</label><br>
        <input type="text" id="vignette" name="vignette" value="<?= htmlspecialchars($vignette ?? '') ?>">
    </div>

    <div>
        <label for="date_sortie">Date de sortie *</label><br>
        <input type="date" id="date_sortie" name="date_sortie" value="<?= htmlspecialchars($dateSortie ?? '') ?>">
    </div>

    <button type="submit">Ajouter la saison</button>

</form>

<?php include '../inc/pied.php'; ?>