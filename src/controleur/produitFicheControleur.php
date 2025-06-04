<?php
function produitFicheControleur($twig, $db) {

    $form = [];
    $produits = new Jeu($db);

    // 🔹 Si un produit est ajouté au panier
    if (isset($_POST['btAjoutP']) && isset($_POST['id'])) {
        $form['valideAjout'] = true;
        $unProduit = $produits->selectById($_POST['id']);

        if (!$unProduit) {
            $form['valideAjout'] = false;
            $form['message'] = "Le produit n'existe pas";
        } else {
            // Création du panier si nécessaire
            if (!isset($_SESSION['panier']) || !is_array($_SESSION['panier'])) {
                $_SESSION['panier'] = [];
            }

            // Ajout ou incrémentation dans le panier
            $id = $unProduit['id'];
            $_SESSION['panier'][$id] = ($_SESSION['panier'][$id] ?? 0) + 1;

            $form['message'] = "Le produit a bien été ajouté";
            $form['produit'] = $unProduit;
        }
    }

    // 🔹 Si on arrive sur la fiche d’un produit via l’URL
    if (isset($_GET['id'])) {
        $jeu = $produits->selectById($_GET['id']);
        if ($jeu) {
            $form['produit'] = $jeu;
        } else {
            $form['message'] = "Produit introuvable";
        }
    }

    echo $twig->render('produitFiche.html.twig', ['form' => $form]);
}
