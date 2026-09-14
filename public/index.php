<?php
// public/index.php
require '../inc/bdd.php';
require '../inc/fonctions-series.php';

$series = getAllSeries($pdo);

include '../inc/entete.php';
?>

<h1>Mes séries</h1>

<a href="ajouter-serie.php">+ Ajouter une série</a>

<?php if (empty($series)): ?>
    <p>Aucune série ajoutée pour le moment.</p>
<?php else: ?>
    <div class="series-list">
        <?php foreach ($series as $serie): ?>
            <div class="serie-card">
                <?php if (!empty($serie['vignette'])): ?>
                    <img src="<?= htmlspecialchars($serie['vignette']) ?>" alt="<?= htmlspecialchars($serie['nom']) ?>">
                <?php endif; ?>

                <h2><?= htmlspecialchars($serie['nom']) ?></h2>
                <p>Sortie le : <?= htmlspecialchars($serie['date_sortie']) ?></p>

                <?php if (!empty($serie['resume'])): ?>
                    <p><?= htmlspecialchars($serie['resume']) ?></p>
                <?php endif; ?>

                <a href="serie-detail.php?id=<?= $serie['id'] ?>">Voir le détail</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include '../inc/pied.php'; ?>