<?php 
/**
 * TP3
 * Équipe 15
 * Frédéric Sheedy (908 378 744)
 * Guillaume Marseille-Pitre (536 857 347)
 * 
 * 
 * Suivi 2021-07-04:
 * [A FAIRE] Page d'accueil - Faire le formulaire de la période d'essai
 *   [OK]    Page de connexion
 *   [OK]    Page liste des aliments
 * [A FAIRE] Page aliment - continuer la logique et le formulaire
 *   [OK]    Page liste des utilisateurs
 * [A FAIRE] Page utilisateur - continuer la logique et le formulaire  
 *   [OK]    Fichier init
 *   [OK]    Fichier statut
 *   [OK]    Fichier rabais
 * [A FAIRE] Validations et gestion des erreurs 
 */

// Page non sécurisée et ajout du header
$securite = false;
include('header.php');
?>
<h2>Accueil</h2>
<?php

// Vérifier si inscription
if (isset($_POST['Inscription'])) {
    // Inscrire le nouveau membre
    $nomUsager        = $_POST['NOM_USAGER_UTILISATEUR'];
    $motDePasseUsager = $_POST['MOT_DE_PASSE_UTILISATEUR'];
    $prenomUti        = $_POST['PRENOM_UTI'];
    $nomUti           = $_POST['NOM_UTI'];
    $sexeUti          = $_POST['SEXE_UTI'];
    $dateNaissanceUti = $_POST['DATE_NAISSANCE_UTI'];
    $courrielUti      = $_POST['COURRIEL_UTI'];
    $poidsInitialUti  = $_POST['POIDS_INITIAL_UTI'];
    $poidsDesireUti   = $_POST['POIDS_DESIRE_UTI'];
    $poidsUniteUti    = $_POST['POIDS_UNITE_UTI'];
    // Requête pour ajouter un membre
    $stid = oci_parse($conn, "insert into TP2_MEMBRE(NO_MEMBRE, NOM_USAGER_MEM, MOT_DE_PASSE_MEM, PRENOM_MEM, 
                                                    NOM_MEM, COURRIEL_MEM, SEXE_MEM, DATE_NAISSANCE_MEM, 
                                                    POIDS_INITIAL_MEM, POIDS_DESIRE_MEM, UNITE_POIDS_MEM)
    values(TP2_NO_MEMBRE_SEQ.nextval, '$nomUsager', '$motDePasseUsager', '$prenomUti', '$nomUti', '$courrielUti', 
                                      '$sexeUti', to_date('$dateNaissanceUti', 'RRRR-MM-DD'), $poidsInitialUti, 
                                       $poidsDesireUti, '$poidsUniteUti')");
    
    oci_execute($stid);
    libere_requete($stid);
    
    
}

// Afficher un message si l'utilisateur est maintenant déconnecté
if (isset($_GET['affichage']) && $_GET['affichage'] === 'deconnexion') {
    echo '<div class="information">Vous &ecirc;tes maintenant d&eacute;connect&eacute;.</div>';
}

// Rediriger l'utilisateur sur la bonne page selon le type d'utilisateur
if (isset($_SESSION['nom_usager_utilisateur'])) {
    if ($_SESSION['type_uti'] === 'Administrateur') {
        header('Location: liste_utilisateurs.php');
    } else {
        header('Location: liste_aliments.php');
    }
    die();
} else {
    // Afficher l'inscription si non connecté
    include('inscription.php');
}
?>
<?php
// Inclure les fichiers rabais et footer
include('rabais.php');
include('footer.php');
?>
