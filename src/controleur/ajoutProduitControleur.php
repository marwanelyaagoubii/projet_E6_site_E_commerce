<?php
function ajoutProduitControleur($twig, $db) {
    $jeuManager = new Jeu($db);

    // Ajouter un jeu + securité des champ
    if (isset($_POST['btAjouter'])) {
        $titre = htmlspecialchars($_POST['titre']);
        $plateforme = htmlspecialchars($_POST['plateforme']);
        $prix = floatval($_POST['prix']);
        $quantites = intval($_POST['quantites']);
        $genre = htmlspecialchars($_POST['genre']);
        $image = htmlspecialchars($_POST['image']);
        $jeuManager->insert($titre, $plateforme, $prix, $quantites, $genre, $image);
    }


    $liste = $jeuManager->selectAll();

    //ajouter  
    if(isset($_POST['btAjouter'])){
        $photo =null;
        $produit = new Produit($db);
        $designation = $_POST['inputDesignation'];
        $description = $_POST['inputDescription'];
        $prix = $_POST['inputPrix'];
        $idType = $_POST['inputType'];
        $upload = new Upload(array('png', 'gif', 'jpg', 'jpeg'), 'photo', 500000);
        $photo = $upload->enregistrer('photo');
        $exec=$produit->insert($designation, $description, $prix, $idType, $photo['nom']);

      if (!$exec){

        $form['valide'] = false;
        $form['message'] = 'Problème d\'insertion dans la table produit ';
        }else{
        $form['valide'] = true;
        }
        }


    echo $twig->render('ajoutProduit.html.twig', ['liste' => $liste]);
}
?>
