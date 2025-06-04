<?php

function panierControleur($twig, $db) {

    $form = [];
    $liste = [];
    $montant = 0;

    $produits = new Jeu($db);

    //  AJOUTER AU PANIER
    if (isset($_GET['add']) && is_numeric($_GET['add'])) {
        $id = (int)$_GET['add'];

        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = [];
        }

        if (isset($_SESSION['panier'][$id])) {
            $_SESSION['panier'][$id]++; // existe deja mais on le rajoute ds le panier  
        } else {
            $_SESSION['panier'][$id] = 1; // existe pas donc on ajoute un nv ds le panier
        }

        header("Location:index.php?page=panier"); // redirection auto vers lepanier 
        exit;
    }

    // SUPPRIMER UN PRODUIT DU PANIER
    if (isset($_GET['remove'])) {
        unset($_SESSION['panier'][$_GET['remove']]);
    }

    //  METTRE À JOUR LES QUANTITÉS DU PANIER
    if (isset($_POST['update'])) {
        foreach ($_POST as $k => $v) { 
            if (strpos($k, 'q-') === 0) {  // q = quantité de l'id 
                $idProduit = (int)substr($k, 2);
                $_SESSION['panier'][$idProduit] = max(1, (int)$v); // emepcher la valeur sois de 0 ou negatif
            }
        }
        header("Location:index.php?page=panier");
        exit;
    }

    // PASSER COMMANDE
    if (isset($_POST['passerCommade'])) {
        if (!isset($_SESSION['login'])) {
            $_SESSION['alert'] = "⚠️ Vous devez être connecté pour passer commande.";
            header("Location: index.php?page=connexion");
            exit;
        }

        //  Calcul du montant depuis la BDD (pas depuis le formulaire)
        if (isset($_SESSION['panier']) && is_array($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $idJeu => $qte) { // chaque élément associe un ID de jeu ($idJeu) à une quantité ($qte).
                $jeu = $produits->selectById($idJeu);
                if ($jeu) {
                    $montant += $jeu['prix'] * $qte; // 0 = 0 + jeu x quantite
                }
            }
        }

        $date = (new DateTime('now', new DateTimeZone('Europe/Paris')))->format("Y-m-d H:i:s");
        $etat = 1;

        $utilisateur = new Utilisateur($db);
        $user = $utilisateur->selectByEmail($_SESSION['login']);
        $idUtilisateur = $user['id'];

        $commande = new Commande($db);
        $form['valide'] = $commande->insert($montant, $etat, $date, $idUtilisateur);

        if (!$form['valide']) {
            $form['message'] = "Erreur : commande non enregistrée.";
        } else {
            $maCommande = $commande->selectByDateCli($date, $idUtilisateur);
            $composer = new Composer($db);

            if (isset($_SESSION['panier']) && is_array($_SESSION['panier']) && !empty($_SESSION['panier'])) {
                foreach ($_SESSION['panier'] as $idJeu => $qte) {
                    $jeu = $produits->selectById($idJeu);

                    if ($jeu) {
                        $composer->insert($maCommande['id'], $idJeu, $qte);

                        // Mise à jour du stock en BDD
                        $nouvelleQuantite = max(0, $jeu['quantites'] - $qte);
                        $produits->updateQuantite($idJeu, $nouvelleQuantite);
                    }
                }

                $form['message'] = "Votre commande a été passée avec succès.";
                unset($_SESSION['panier']);
            } else {
                $form['valide'] = false;
                $form['message'] = "Votre panier est vide.";
            }
        }
    }

    // AFFICHAGE DU PANIER
    if (isset($_SESSION['panier']) && is_array($_SESSION['panier']) && !empty($_SESSION['panier'])) {
        $ids = array_keys($_SESSION['panier']);
        $liste = $produits->selectIn($ids);

        // Calculer le total pour affichage dans le panier (pas pour la commande)
        $montant = 0;
        foreach ($liste as $jeu) {
            $quantite = $_SESSION['panier'][$jeu['id']];
            $montant += $jeu['prix'] * $quantite;
        }
    }

    //  Passer le montant au template (pour l'affichage du total même après commande)
    $form['total'] = $montant;

    echo $twig->render('panier.html.twig', ['form' => $form, 'liste' => $liste]);
}

?>
