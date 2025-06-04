<?php

    function verifierMdp($mdp) {
    $nb1 = 0; $nb2 = 0; $nb3 = 0; $nb4 = 0;
    for ($i = 0; $i < strlen($mdp); $i++) { // Boucle test chaque caractere du mdp 
        $c = $mdp[$i];
        if (ctype_upper($c)) $nb1++; // Maj
        elseif (ctype_lower($c)) $nb2++; // Minuscule
        elseif (is_numeric($c)) $nb3++; // cchiffre
        elseif (preg_match('/[^\w\d\s]/', $c)) $nb4++; // caractére special
    }
    return (strlen($mdp) >= 12 && $nb1 >= 1 && $nb2 >= 4 && $nb3 >= 3 && $nb4 >= 1);
}


function inscrireControleur($twig, $db) {
require_once __DIR__ . '/mailControleur.php';

    $form = [];

    if (isset($_POST['btInscrire'])) {
        $inputNom = $_POST['inputNom'] ?? null;
        $inputPrenom = $_POST['inputPrenom'] ?? null;
        $inputEmail = $_POST['inputEmail'] ?? null;
        $inputPassword = $_POST['inputPassword'] ?? null;
        $inputPassword2 = $_POST['inputPassword2'] ?? null;

        if (!$inputNom || !$inputPrenom || !$inputEmail || !$inputPassword || !$inputPassword2) {
            $form['valide'] = false;
            $form['message'] = 'Tous les champs doivent être remplis.';
        } elseif ($inputPassword !== $inputPassword2) {
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe ne correspondent pas.';
        } elseif (!verifierMdp($inputPassword)) {
            $form['valide'] = false;
            $form['message'] = 'Le mot de passe est trop faible.';
        } else {
            $utilisateur = new Utilisateur($db);

            if ($utilisateur->emailExiste($inputEmail)) {
                $form['valide'] = false;
                $form['message'] = 'Cet email est déjà utilisé.';
            } else {
                $hashedPassword = password_hash($inputPassword, PASSWORD_DEFAULT);
                $role = 2;
                $valider = false;
                $idgenere = uniqid();

                $exec = $utilisateur->insert($inputEmail, $hashedPassword, $role, $inputNom, $inputPrenom, $valider, $idgenere);

                if ($exec) {
                    // Envoi du mail de validation
                    $mailOk = envoyerMailValidation($inputEmail, $idgenere);

                    $form['valide'] = true;
                    $form['message'] = $mailOk
                        ? "Inscription réussie. Un email vous a été envoyé pour activer votre compte."
                        : "Inscription réussie, mais échec de l'envoi de l'email.";
                    $form['email'] = $inputEmail;
                } else {
                    $form['valide'] = false;
                    $form['message'] = "Erreur lors de l'enregistrement.";
                }
            }
        }
    }

    echo $twig->render('inscrire.html.twig', ['form' => $form]);
}


