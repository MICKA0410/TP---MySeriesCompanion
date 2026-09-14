<?php

require '../inc/bdd.php';
require '../inc/fonctions-saisons.php';
require '../inc/fonctions-episodes.php';
require '../inc/fonctions-series.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$saisonId = (int) $_GET['id'];

$saison = getSaisonById($pdo, $saisonId);

if (!$saison) {
    header('Location: index.php');
    exit;
}


$serie = getSerieById($pdo, $saison['serie_id']);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $resume = trim($_POST['resume'] ?? '');
    $vignette = trim($_POST['vignette'] ?? '');
    $dateSortie = $_POST['date_sortie'] ?? '';
    $duree = trim($_POST['duree'] ?? '');

    if ($nom === '') {
        $errors[] = "Le nom est obligatoire.";
    }
    if ($dateSortie === '') {
        $errors[] = "La date de sortie est obligatoire.";
    }
    if ($duree !== '' && !is_numeric($duree)) {
        $errors[] = "La durée doit être un nombre.";
    }

    if (empty($errors)) {
        addEpisode(
            $pdo,
            $nom,
            $resume ?: null,
            $vignette ?: null,
            $dateSortie,
            $duree !== '' ? (int) $duree : null,
            $saisonId
        );

        header('Location: saison-detail.php?id=' . $saisonId);
        exit;
    }
}

$episodes = getEpisodesBySaisonId($pdo, $saisonId);

include '../inc/entete.php';
?>

<a href="serie-detail.php?id=<?= $saison['serie_id'] ?>">&larr; Retour à <?= htmlspecialchars($serie['nom']) ?></a>

<h1><?= htmlspecialchars($saison['nom']) ?></h1>

<?php if (!empty($saison['vignette'])): ?>
    <img src="<?= htmlspecialchars($saison['vignette']) ?>" alt="<?= htmlspecialchars($saison['nom']) ?>">
<?php endif; ?>

<p>Sortie le : <?= htmlspecialchars($saison['date_sortie']) ?></p>

<?php if (!empty($saison['resume'])): ?>
    <p><?= htmlspecialchars($saison['resume']) ?></p>
<?php endif; ?>

<hr>

<h2>Épisodes</h2>

<?php if (empty($episodes)): ?>
    <p>Aucun épisode n'est encore disponible pour cette saison.</p>
<?php else: ?>
    <ul>
        <?php foreach ($episodes as $episode): ?>
            <li>
                <?= htmlspecialchars($episode['nom']) ?>
                (<?= htmlspecialchars($episode['date_sortie']) ?>)
                <?php if (!empty($episode['duree'])): ?>
                    — <?= (int) $episode['duree'] ?> min
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h3>Ajouter un épisode</h3>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="saison-detail.php?id=<?= $saisonId ?>">

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

    <div>
        <label for="duree">Durée (en minutes)</label><br>
        <input type="number" id="duree" name="duree" value="<?= htmlspecialchars($duree ?? '') ?>">
    </div>

    <button type="submit">Ajouter l'épisode</button>

</form>

<?php include '../inc/pied.php'; ?>