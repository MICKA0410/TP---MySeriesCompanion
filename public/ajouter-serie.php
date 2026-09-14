<?php

require '../inc/bdd.php';
require '../inc/fonctions-series.php';

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
        $newId = addSerie($pdo, $nom, $resume ?: null, $vignette ?: null, $dateSortie);

        header('Location: serie-detail.php?id=' . $newId);
        exit;
    }
}

include '../inc/entete.php';
?>

<h1>Ajouter une série</h1>

<?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="ajouter-serie.php">

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

    <button type="submit">Ajouter</button>

</form>

<?php include '../inc/pied.php'; ?>