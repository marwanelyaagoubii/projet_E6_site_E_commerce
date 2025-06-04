<?php
function deconnexionControleur($twig, $db){
    session_unset(); // destruction de la seesion apres deconnexion (secruiter)
    session_destroy();
    header("Location:index.php");
}
