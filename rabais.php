<?php
/**
 * TP3
 * Équipe 15
 * Frédéric Sheedy (908 378 744)
 * Guillaume Marseille-Pitre (536 857 347)
 * 
 * Cette page est appelée seulement par une autre page
 */

// Empêcher l'accès direct à la page
if (!isset($securite)) {
    header('Location: index.php');
    die();
}

// On fait une requête des rabais en cours
$listeRabais = array();
$stid = oci_parse($conn, "select CODE_RABAIS
                              from TP2_RABAIS
                              where DATE_DEBUT_RAB <= sysdate and DATE_FIN_RAB > sysdate");

// On exécute le select
oci_execute($stid);

// On va populer le tableau des rabais possibles
$loop = false;
while (($rabais = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    $listeRabais[] = $rabais['CODE_RABAIS'];
    $loop = true;
}
// Libérer la requête Oracle
libere_requete($stid);

// Afficher un rabais aléatoire
if ($loop === true) {
    $aleatoir = rand(0, count($listeRabais)-1);
    $choix = $listeRabais[$aleatoir];
    $stid = oci_parse($conn, "select *
                              from TP2_RABAIS
                              where CODE_RABAIS = '$choix'");
    
    //on exécute le select
    oci_execute($stid);
    
    // Affiche le rabais
    if (($rabaisEnCours = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
        echo '<img class="rabais" src="img/'.$rabaisEnCours['CODE_RABAIS'].'.png" alt="Rabais '.$rabaisEnCours['CODE_RABAIS'].'">';
    }
    // Libérer la requête Oracle
    libere_requete($stid);
}
