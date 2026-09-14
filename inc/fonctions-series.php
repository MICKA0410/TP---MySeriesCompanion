<?php


function getAllSeries($pdo) {
    $stmt = $pdo->query("SELECT * FROM serie ORDER BY date_sortie DESC");
    return $stmt->fetchAll();
}

function getSerieById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM serie WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

function addSerie($pdo, $nom, $resume, $vignette, $dateSortie) {
    $stmt = $pdo->prepare(
        "INSERT INTO serie (nom, resume, vignette, date_sortie) 
         VALUES (:nom, :resume, :vignette, :date_sortie)"
    );
    $stmt->execute([
        'nom' => $nom,
        'resume' => $resume,
        'vignette' => $vignette,
        'date_sortie' => $dateSortie,
    ]);
    return $pdo->lastInsertId();
}