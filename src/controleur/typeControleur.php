<?php
function typeControleur($twig, $db){
// PAS ENCORE DE TYPE (peut etre rajouter les different genre des jeux ici "action" "RPG"...)

 echo $twig->render('type.html.twig', array('form'=>$form,'liste'=>$liste));
}
?>
