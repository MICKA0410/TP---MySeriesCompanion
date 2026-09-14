<?php


function getSaisonsBySerieId($pdo, $serieId) {
    $stmt = $pdo->prepare("SELECT * FROM saison WHERE serie_id = :serie_id ORDER BY date_sortie ASC");
    $stmt->execute(['serie_id' => $serieId]);
    return $stmt->fetchAll();
}

function getSaisonById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM saison WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

function addSaison($pdo, $nom, $resume, $vignette, $dateSortie, $serieId) {
    $stmt = $pdo->prepare(
        "INSERT INTO saison (nom, resume, vignette, date_sortie, serie_id) 
         VALUES (:nom, :resume, :vignette, :date_sortie, :serie_id)"
    );
    $stmt->execute([
        'nom' => $nom,
        'resume' => $resume,
        'vignette' => $vignette,
        'date_sortie' => $dateSortie,
        'serie_id' => $serieId,
    ]);
    return $pdo->lastInsertId();
}
