<?php
function jeuxControleur($twig, $db) {
    $jeu = new Jeu($db);
    $q = $_GET['q'] ?? '';
    $tri = $_GET['tri'] ?? '';

    $liste = $jeu->selectAll();

    if (!empty($q)) {
        $liste = array_filter($liste, function ($j) use ($q) {
            return stripos($j['titre'], $q) !== false;
        });
        $liste = array_values($liste); 
    }

    if ($tri === 'prix_asc') {
        usort($liste, fn($a, $b) => $a['prix'] <=> $b['prix']);
    } elseif ($tri === 'prix_desc') {
        usort($liste, fn($a, $b) => $b['prix'] <=> $a['prix']);
    }


    echo $twig->render('jeux.html.twig', [
        'liste' => $liste,
        'q' => $q,
        'tri' => $tri
    ]);
    
}
