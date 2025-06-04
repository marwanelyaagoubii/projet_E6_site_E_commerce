<?php
function ajoutjeuControleur($twig, $db) {
    $jeuManager = new Jeu($db);
    $form = [];

    //  AJOUTER UN NOUVEAU JEU
    if (isset($_POST['btAjouter'])) {
        $titre = htmlspecialchars($_POST['titre'] ?? '');
        $plateforme = htmlspecialchars($_POST['plateforme'] ?? '');
        $prix = floatval($_POST['prix'] ?? 0);
        $quantites = intval($_POST['quantites'] ?? 0);
        $genre = htmlspecialchars($_POST['genre'] ?? '');
        $image = htmlspecialchars($_POST['image'] ?? ''); 
        
        //  UPLOAD DE FICHIER
        $upload = new Upload(['jpg', 'jpeg', 'png'], 'public/images', 500000);
        $imageUpload = $upload->enregistrer('photo');
        $nomPhoto = (!empty($imageUpload['nom'])) ? $imageUpload['nom'] : 'default.jpg';

        // Insertion
        $jeuManager->insert($titre, $plateforme, $prix, $quantites, $genre, $image, $nomPhoto);
    }

    // SUPPRIMER
    if (isset($_GET['supprimer'])) {
        $id = intval($_GET['supprimer']);
        $jeuManager->delete($id);
    }

    // MODIFIER
    if (isset($_POST['btModifier'])) {
        $id = intval($_POST['id']);
        $titre = htmlspecialchars($_POST['titre']);
        $plateforme = htmlspecialchars($_POST['plateforme']);
        $prix = floatval($_POST['prix']);
        $quantites = intval($_POST['quantites']);
        $genre = htmlspecialchars($_POST['genre']);
        $image = htmlspecialchars($_POST['image']); // pour l’autre projet
        $photo = htmlspecialchars($_POST['photo'] ?? 'default.jpg'); // reste inchangé en modif

        $jeuManager->update($id, $titre, $plateforme, $prix, $quantites, $genre, $image, $photo);
    }

    $liste = $jeuManager->selectAll();
    echo $twig->render('ajouterJeu.html.twig', ['liste' => $liste, 'form' => $form]);
}
