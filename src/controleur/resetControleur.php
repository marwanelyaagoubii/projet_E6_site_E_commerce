<?php

// reset du mdp oublie avc envoie d'email et token 
function resetControleur($twig, $db) {
    $form = [];
    $email = $_GET['email'] ?? null;
    $token = $_GET['token'] ?? null;

    $utilisateur = new Utilisateur($db);
    $user = $utilisateur->selectByEmail($email);

    if (!$user || $user['reset_token'] !== $token) {
        $form['erreur'] = "Lien invalide ou expiré.";
    } else {
        $now = new DateTime();
        $resetTime = new DateTime($user['reset_time']);
        $interval = $resetTime->diff($now);

        if ($interval->days >= 1 || $interval->h >= 24) {
            $form['erreur'] = "Lien expiré. Veuillez redemander une réinitialisation.";
        } elseif (isset($_POST['btReset'])) {
            $pass1 = $_POST['newPassword'];
            $pass2 = $_POST['confirmPassword'];

            if ($pass1 !== $pass2) {
                $form['erreur'] = "Les mots de passe ne correspondent pas.";
            } elseif (strlen($pass1) < 8) {
                $form['erreur'] = "Mot de passe trop court.";
            } else {
                $hash = password_hash($pass1, PASSWORD_DEFAULT);
                $utilisateur->resetPassword($email, $hash);
                $form['message'] = "✅ Mot de passe modifié avec succès. Vous pouvez maintenant vous connecter.";
            }
        }
    }

    echo $twig->render('reset.html.twig', ['form' => $form, 'email' => $email, 'token' => $token]);
}
