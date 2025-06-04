<?php
function produitModifControleur($twig, $db) {
    $form = [];
    $jeu = new Jeu($db);

    //  Si on a un ID (modif à charger)
    if (isset($_GET['id'])) {
        $produit = $jeu->selectById($_GET['id']);
        if ($produit != null) {
            $form['produit'] = $produit;
        } else {
            $form['message'] = 'Produit introuvable';
        }
    }

    // Si on a soumis le formulaire
    if (isset($_POST['btModifier'])) {
        $id = $_POST['id'];
        $titre = htmlspecialchars($_POST['titre']);
        $plateforme = htmlspecialchars($_POST['plateforme']);
        $prix = floatval($_POST['prix']);
        $quantites = intval($_POST['quantites']);
        $genre = htmlspecialchars($_POST['genre']);
        $image = htmlspecialchars($_POST['image'] ?? '');

        // Gestion de la photo : nouvelle image ou garder l'ancienne
        $photoActuelle = $_POST['photo_actuelle'] ?? 'default.jpg';

        if (!empty($_FILES['photo']['name'])) {
            $upload = new Upload(['jpg', 'jpeg', 'png'], 'public/images', 500000);
            $result = $upload->enregistrer('photo');
            $photo = $result['nom'] ?? $photoActuelle;
        } else {
            $photo = $photoActuelle;
        }

        //  Update
        $exec = $jeu->update($id, $titre, $plateforme, $prix, $quantites, $genre, $image, $photo);

        if ($exec) {
            $form['valide'] = true;
            $form['message'] = 'Produit modifié avec succès';
            $form['produit'] = $jeu->selectById($id);
        } else {
            $form['valide'] = false;
            $form['message'] = 'Erreur lors de la modification';
        }
    }

    echo $twig->render('produitModif.html.twig', ['form' => $form]);
}
