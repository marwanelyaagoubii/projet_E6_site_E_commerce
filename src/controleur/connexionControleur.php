<?php

function connexionControleur($twig, $db) {
    $form = [];

    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $utilisateur = new Utilisateur($db);
        $user = $utilisateur->selectByEmail($email);

        //  Assure que idgenere existe
        if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
            if (!$user['valider']) {
                // Vérifie que idgenere est bien défini
                if (!empty($user['idgenere'])) {
                    $idgenere = $user['idgenere'];
                    $serveur = $_SERVER['HTTP_HOST'];
                    $script = $_SERVER['SCRIPT_NAME'];

                    $message = "
                    <html><body>
                    Bonjour, voici un nouveau lien pour valider votre inscription :<br><br>
                    <a href='http://$serveur$script?page=validation&email=$email&idgenere=$idgenere'>
                        Valider mon compte
                    </a>
                    </body></html>";

                    $headers[] = 'MIME-Version: 1.0';
                    $headers[] = 'Content-type: text/html; charset=utf-8';

                    if ($_SERVER['SERVER_NAME'] != 'localhost') {
                            mail($email, 'Validation de votre compte GameVault', $message, implode("\n", $headers));
                    } else {
                    file_put_contents("log_mail.txt", "TO: $email\nSUBJECT: Validation de votre compte GameVault\n$message\n\n", FILE_APPEND);
                    }

                    $form['valide'] = false;
                    $form['message'] = "⛔ Votre compte n'est pas encore validé. Un lien vous a été renvoyé.";
                } else {
                    $form['valide'] = false;
                    $form['message'] = "Erreur : identifiant de validation manquant.";
                }
            } else {
                //  Connexion autorisée
                $_SESSION['login'] = $user['email'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['role'] = $user['idRole'];
                header("Location: index.php");
                exit;
            }
        } else {
            $form['valide'] = false;
            $form['message'] = "❌ Identifiants incorrects.";
        }
    }

    echo $twig->render('connexion.html.twig', ['form' => $form]);
}

?>
