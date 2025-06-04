<?php

function getPage($db) {
    $lesPages['acceuil'] = "acceuilControleur;0"; 
    $lesPages['contact'] = "contactControleur;0";
    $lesPages['inscrire'] = "inscrireControleur;0";
    $lesPages['mentions'] = "mentionsLegalesControleurs;0";
    $lesPages['connexion'] = "connexionControleur;0";
    $lesPages['apropos'] = "aproposControleur;0";
    $lesPages['maintenance'] = "maintenanceControleur;0";
    $lesPages['deconnexion'] = "deconnexionControleur;0";
    $lesPages['produit'] = "produitControleur;0";
    $lesPages['jeux'] = "jeuxControleur;0";
    $lesPages['produitFiche'] = "produitFicheControleur;0";
    $lesPages['panier'] = "panierControleur;0";
    $lesPages['validation'] = "validationControleur;0";
    $lesPages['oublie'] = "oublieControleur;0";
    $lesPages['reset'] = "resetControleur;0";
    $lesPages['mail'] = "mailControleur;0";




    // ADMIN uniquement
    $lesPages['utilisateur'] = "utilisateurControleur;1";
    $lesPages['ajouterJeu'] = "ajoutjeuControleur;1";
    $lesPages['type'] = "typeControleur;1";
    $lesPages['utilisateurModif'] = "utilisateurModifControleur;1";
    $lesPages['typeModif'] = "typeModifControleur;1";
    $lesPages['produitModif'] = "produitModifControleur;1";
    $lesPages['ajoutProduit'] = "ajoutProduitControleur;1";
    $lesPages['listeproduitpdf'] = "actionListeProduitPdf;1";


    if ($db != null) {
        $page = $_GET['page'] ?? 'acceuil';

        if (!isset($lesPages[$page])) {
            $page = 'acceuil';
        }

        $explode = explode(";", $lesPages[$page]);
        $controleur = $explode[0];
        $role = $explode[1];

        if ($role != 0) {
            if (isset($_SESSION['login']) && isset($_SESSION['role'])) {
                if ($_SESSION['role'] == $role) {
                    $contenu = $controleur;
                } else {
                    $_SESSION['message'] = "⛔ Accès refusé : rôle insuffisant.";
                    $contenu = "acceuilControleur";
                }
            } else {
                $_SESSION['message'] = "⛔ Veuillez vous connecter.";
                $contenu = "acceuilControleur";
            }
        } else {
            $contenu = $controleur;
        }
    } else {
        $contenu = "maintenanceControleur";
    }

    return $contenu;
}
?>
