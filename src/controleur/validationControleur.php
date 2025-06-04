<?php

function validationControleur($twig, $db) {
    $email = $_GET['email'] ?? null;
    $idgenere = $_GET['idgenere'] ?? null;
    $message = "";

    if ($email && $idgenere) {
        $utilisateur = new Utilisateur($db);
        $user = $utilisateur->selectByEmail($email);

        if ($user && $user['idgenere'] === $idgenere) {
            // Mettre à jour le champ 'valider'
            $exec = $utilisateur->validerCompte($email);
            if ($exec) {
                $message = " Votre inscription a bien été validée ! Vous pouvez maintenant vous connecter.";
            } else {
                $message = " Une erreur est survenue lors de la validation de votre compte.";
            }
        } else {
            $message = " Lien invalide ou déjà utilisé.";
        }
    } else {
        $message = "Paramètres manquants dans l'URL.";
    }

    echo $twig->render('validation.html.twig', ['message' => $message]);
}
