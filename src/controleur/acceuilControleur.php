<?php
function acceuilControleur($twig){
    echo $twig->render('acceuil.html.twig', array());
}

function maintenanceControleur($twig,$db) {
    echo $twig->render('maintenance.html.twig',array());
}

function aproposControleur($twig,$db) {
    echo $twig->render('apropos.html.twig');
}

function mentionsLegalesControleurs($twig,$db) {
    echo $twig->render('mentionsLegales.html.twig');
}
?>