<?php

function getEpisodesBySaisonId($pdo, $saisonId) {
    $stmt = $pdo->prepare("SELECT * FROM episode WHERE saison_id = :saison_id ORDER BY date_sortie ASC");
    $stmt->execute(['saison_id' => $saisonId]);
    return $stmt->fetchAll();
}

function getEpisodeById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM episode WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

function addEpisode($pdo, $nom, $resume, $vignette, $dateSortie, $duree, $saisonId) {
    $stmt = $pdo->prepare(
        "INSERT INTO episode (nom, resume, vignette, date_sortie, duree, saison_id) 
         VALUES (:nom, :resume, :vignette, :date_sortie, :duree, :saison_id)"
    );
    $stmt->execute([
        'nom' => $nom,
        'resume' => $resume,
        'vignette' => $vignette,
        'date_sortie' => $dateSortie,
        'duree' => $duree,
        'saison_id' => $saisonId,
    ]);
    return $pdo->lastInsertId();
}