<?php
function utilisateurModifControleur($twig, $db){
    $form = array();

    if (isset($_GET['id'])) {
        $utilisateur = new Utilisateur($db);
        $unUtilisateur = $utilisateur->selectById($_GET['id']);

        if ($unUtilisateur != null) {
            $form['utilisateur'] = $unUtilisateur;
            $role = new Role($db);
            $liste = $role->select();
            $form['roles'] = $liste;
        } else {
            $form['message'] = 'Utilisateur incorrect';
        }
    } else {
        if (isset($_POST['btModifier'])) {
            $utilisateur = new Utilisateur($db);
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $id = $_POST['id'];

            $exec = $utilisateur->update($id, $email, $nom, $prenom, $role);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Échec de la modification';
            } else {
                // Vérifie s'il faut modifier le mot de passe
                if (!empty($_POST['inputPassword'])) {
                    if ($_POST['inputPassword'] == $_POST['inputPassword2']) {
                        $utilisateur->updateMdp($id, $_POST['inputPassword']);
                        $form['message'] = 'Modification réussie avec mot de passe';
                        $form['valide'] = true;
                    } else {
                        $form['valide'] = false;
                        $form['message'] = 'Les mots de passe ne correspondent pas';
                    }
                } else {
                    $form['valide'] = true;
                    $form['message'] = 'Modification réussie (hors mot de passe)';
                }
            }

            // Recharge les données pour le formulaire après modification
            $form['utilisateur'] = $utilisateur->selectById($id);
            $role = new Role($db);
            $form['roles'] = $role->select();
        } else {
            $form['message'] = 'Utilisateur non précisé';
        }
    }

    echo $twig->render('utilisateurModif.html.twig', array('form' => $form));
}
?>
