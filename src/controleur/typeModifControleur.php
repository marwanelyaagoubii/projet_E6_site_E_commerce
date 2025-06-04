<?php
function typeModifControleur($twig, $db) {
// MODIFICATION DES GENRES PEUT ETRE (Liée a typeControleur)
    echo $twig->render('typeModif.html.twig', ['form' => $form]);
}
