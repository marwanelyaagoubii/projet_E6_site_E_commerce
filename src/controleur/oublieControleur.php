<?php
function oublieControleur($twig, $db) {
    require_once __DIR__ . '/mailControleur.php'; // ton helper PHPMailer

    $form = [];

    if (isset($_POST['btOublie'])) {
        $email = $_POST['email'] ?? null;
        if ($email) {
            $utilisateur = new Utilisateur($db);
            $user = $utilisateur->selectByEmail($email);

            if ($user) {
                $token = uniqid();
                $time = date("Y-m-d H:i:s");
                $utilisateur->setResetToken($email, $token, $time);

                $serveur = $_SERVER['HTTP_HOST'];
                $script = $_SERVER['SCRIPT_NAME'];

                $message = "
                <html><body>
                Réinitialisation de votre mot de passe :<br><br>
                <a href='http://$serveur$script?page=reset&email=$email&token=$token'>
                    Réinitialiser mon mot de passe
                </a>
                </body></html>";

                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=utf-8';

envoyerMailReset($email, $token); 

                $form['message'] = "Un lien vous a été envoyé par email.";
            } else {
                $form['message'] = "Adresse inconnue.";
            }
        }
    }

    echo $twig->render('oublie.html.twig', ['form' => $form]);
}
