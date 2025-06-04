<?php
function typeControleur($twig, $db){
 $form = array();
 $type = new type($db);

 // 1. Modifier un produit (via POST)
    if (isset($_POST['btModifier'])) {
        $id = $_POST['id'];
        $libelle = $_POST['libelle'];

        $exec = $type->update($id, $libelle);

        if ($exec) {
            $form['valide'] = true;
            $form['message'] = "Jeu modifié avec succès.";
        } else {
            $form['valide'] = false;
            $form['message'] = "Erreur lors de la modification.";
        }

        $form['type'] = $type->selectById($id);
    }





 $liste = $type->selectAll();

 echo $twig->render('type.html.twig', array('form'=>$form,'liste'=>$liste));
}
?>