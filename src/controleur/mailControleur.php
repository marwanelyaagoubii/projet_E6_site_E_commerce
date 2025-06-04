<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Charge l'autoloader de Composer
require_once 'C:/wamp64/www/shop-shopp/vendor/autoload.php';


function envoyerMailValidation($toEmail, $idgenere) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'marwanackerman@gmail.com';
        $mail->Password   = 'hwingpxvrrakoelo';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('marwanackerman@gmail.com', 'GameVault');
        $mail->addAddress($toEmail);

        $serveur = $_SERVER['HTTP_HOST'];
        $script = $_SERVER['SCRIPT_NAME'];
        $lien = "http://$serveur$script?page=validation&email=$toEmail&idgenere=$idgenere";

        $mail->isHTML(true);
        $mail->Subject = 'Validation de votre compte GameVault';
        $mail->Body    = "<html><body>
            Bonjour,<br><br>
            Merci de vous être inscrit !<br><br>
            Cliquez ici pour valider votre compte :<br>
            <a href='$lien'>$lien</a>
            </body></html>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur d'envoi du mail : " . $mail->ErrorInfo);
        return false;
    }
}

function envoyerMailReset($toEmail, $token) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'marwanackerman@gmail.com';
        $mail->Password   = 'hwingpxvrrakoelo';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('marwanackerman@gmail.com', 'GameVault');
        $mail->addAddress($toEmail);

        $serveur = $_SERVER['HTTP_HOST'];
        $script = $_SERVER['SCRIPT_NAME'];
        $lien = "http://$serveur$script?page=reset&email=$toEmail&token=$token";

        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation de mot de passe';
        $mail->Body    = "<html><body>
            Réinitialisation de votre mot de passe :<br><br>
            <a href='$lien'>Réinitialiser mon mot de passe</a>
            </body></html>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur mail reset : " . $mail->ErrorInfo);
        return false;
    }
}
