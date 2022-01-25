<?php
/**
 * TP3
 * Équipe 15
 * Frédéric Sheedy (908 378 744)
 * Guillaume Marseille-Pitre (536 857 347)
 *
 * Cette page est appelée seulement un autre page
 */

// Empêcher l'accès direct à la page
if (!isset($securite)) {
    header('Location: index.php');
    die();
}

// Initialiser la connexion Oracle
$conn = oci_connect('C##GUMAP4', 'bd536857347', 'ift-p-ora12c.fsg.ulaval.ca:1521/ora12c', 'AL32UTF8');

// Fonction pour exécuter une requête et gestion des erreurs
function execute_requete($requete) {
    if (@oci_execute($requete) === false) {
        // Récupérer le détails de l'erreur
        $erreur = oci_error($requete);
        if ($erreur['code'] === 1400) {
            $_SESSION['oracle_error'] = 'Erreur: il y a des champs obligatoire!';
        } else if ($erreur['code'] === 1) {
            $_SESSION['oracle_error'] = 'Erreur: duplication de clé primaire!';
        } else if ($erreur['code'] === 2291) {
            $_SESSION['oracle_error'] = 'Erreur: clé étrangère à vérifier!';
        } else {
            // Autre erreur: afficher l'erreur
            $_SESSION['oracle_error'] = 'Erreur SQL:'.htmlentities($erreur['message']);
        }
    }
}

// Fonction pour libérer les connexions Oracle
function libere_requete($stid) {
    oci_free_statement($stid);
}
