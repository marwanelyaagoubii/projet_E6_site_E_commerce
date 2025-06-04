<?php

function produitControleur($twig, $db) {
    $form = [];
    $jeu = new Jeu($db);

    //  Modifier un produit (via POST)
    if (isset($_POST['btModifier'])) {
        $id = $_POST['id'];
        $titre = $_POST['titre'];
        $plateforme = $_POST['plateforme'];
        $prix = $_POST['prix'];
        $quantites = $_POST['quantites'];
        $genre = $_POST['genre'];
        $image = $_POST['image'];

        $exec = $jeu->update($id, $titre, $plateforme, $prix, $quantites, $genre, $image);

        if ($exec) {
            $form['valide'] = true;
            $form['message'] = "Jeu modifié avec succès.";
        } else {
            $form['valide'] = false;
            $form['message'] = "Erreur lors de la modification.";
        }

        $form['produit'] = $jeu->selectById($id);
    }

    //   Précharger un jeu pour édition
    if (isset($_GET['id']) && !isset($_GET['supprimer'])) {
        $id = $_GET['id'];
        $produit = $jeu->selectById($id);

        if ($produit != null) {
            $form['produit'] = $produit;
        } else {
            $form['message'] = "Jeu non trouvé.";
        }
    }

    //   Suppression multiple
    if (isset($_POST['btSupprimer'])) {
        $cocher = $_POST['cocher'] ?? [];
        $etat = true;

        foreach ($cocher as $id) {
            $exec = $jeu->delete($id);
            if (!$exec) {
                $etat = false;
            }
        }

        header('Location: index.php?page=produit&etat=' . ($etat ? 'true' : 'false'));
        exit;
    }

    //  Suppression individuelle
    if (isset($_GET['id']) && isset($_GET['supprimer'])) {
        $id = $_GET['id'];

        $composer = new Composer($db);
        $composer->deleteByJeu($id);

        $exec = $jeu->delete($id);
        header('Location: index.php?page=produit&etat=' . ($exec ? 'true' : 'false'));
        exit;
    }

    //   Afficher message si redirection après suppression
    if (isset($_GET['etat'])) {
        $form['etat'] = $_GET['etat'];
    }

    //   Afficher la liste des jeux
    $liste = $jeu->selectAll();

    echo $twig->render('produit.html.twig', ['form' => $form, 'liste' => $liste]);
}

require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// afficher la liste de produit dan sla page produit ADMIN 
function actionListeProduitPdf($twig, $db) {
    require_once 'C:/wamp64/www/shop-shopp/vendor/autoload.php';

    $jeu = new Jeu($db);
    $liste = $jeu->selectAll(); 

    $html = $twig->render('produit-liste-pdf.html.twig', ['liste' => $liste]);

    $options = new Options();
    $options->set('defaultFont', 'Arial');

    $dompdf = new Dompdf($options);
    ob_end_clean();

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("liste_jeux.pdf", ["Attachment" => false]);
    exit;
}


?>
