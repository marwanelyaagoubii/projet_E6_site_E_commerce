<?php
function typeModifControleur($twig, $db) {
    $form = [];
    $type = new type($db);


    //  Si on a un ID (modif à charger)
    if (isset($_GET['id'])) {
        $types = $type->selectById($_GET['id']);
        if ($types != null) {
            $form['type'] = $types;
        } else {
            $form['message'] = 'types introuvable';
        }
    }

    // Si on a soumis le formulaire
    if (isset($_POST['btModifier'])) {
        $id = $_POST['id'];
        $libelle = htmlspecialchars($_POST['libelle']);

        //  Update
        $exec = $type->update($id, $libelle);
        $liste = $type->selectAll();

        if ($exec) {
            $form['valide'] = true;
            $form['message'] = 'types modifié avec succès';
            $form['types'] = $type->selectById($id);
        } else {
            $form['valide'] = false;
            $form['message'] = 'Erreur lors de la modification';
        }
    }

    echo $twig->render('typeModif.html.twig', ['form' => $form]);
}
