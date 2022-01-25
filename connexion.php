<?php 
/**
 * TP3
 * Équipe 15
 * Frédéric Sheedy (908 378 744)
 * Guillaume Marseille-Pitre (536 857 347)
 */

// Page non sécurisée et ajout du header
$securite = false;
include('header.php');

// Initialiser les variables
$nomUsagerUtilisateur = '';
$motDePasse = '';

// Vérifier si l'usager veux se déconnecter
if (isset($_GET['deconnexion'])) {
    session_destroy();
    header('Location: index.php?affichage=deconnexion');
    die();
} else if (isset($_GET['erreur'])) {
    // Afficher une erreur si l'utilisateur a essayer d'accéder à une page sécurisée sans connexion
    $erreur = 'Vous devez &ecirc;tre connect&eacute; pour acc&eacute;der à la page.';
}

// Si l'usager a cliqué sur Connexion
if (isset($_POST['Connexion'])) {
    // Initialiser les variables
    $nomUsagerUtilisateur = $_POST['NOM_USAGER_UTILISATEUR'];
    $motDePasse = $_POST['MOT_DE_PASSE'];

    // On vérifie que le nom et le mot de passe existe
    $stid = oci_parse($conn, "select * 
                              from TP2_VUE_UTILISATEUR 
                              where NOM_USAGER_UTILISATEUR='$nomUsagerUtilisateur' and MOT_DE_PASSE_UTI='$motDePasse' 
                              fetch first 1 rows only");
    
    // On exécute le select
    oci_execute($stid);
    
    // Stocker les valeurs dans la session, si résultat
    if (($client = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
        $_SESSION['nom_usager_utilisateur'] = $client['NOM_USAGER_UTILISATEUR'];
        $_SESSION['nom_complet_uti'] = $client['NOM_COMPLET_UTI'];
        $_SESSION['courriel_uti'] = $client['COURRIEL_UTI'];
        $_SESSION['type_uti'] = $client['TYPE_UTI'];
        libere_requete($stid);
        
        // Faire une redirection pour afficher la page selon l'usager  
        header('Location: index.php');
        die();
    } else {
        // Informations incorrectes
        libere_requete($stid);
        $erreur = "Informations de connexion incorrectes: vérifier votre nom d'utilisateur et mot de passe.";
    }
    
    
} // Fin de la recherche


?>
<h2>Connexion</h2>

<?php if (isset($erreur)) { ?>
<div class="erreur"><?php echo $erreur; ?></div>
<?php } // Fin erreur ?>

<p>Veuillez entrer votre nom d'utilisateur et votre mot de passe.</p>

<form class="formulaire_details" method="post" action="connexion.php">
	<label for="NOM_USAGER_UTILISATEUR">Nom d'utilisateur:</label>
	<input type="text" name="NOM_USAGER_UTILISATEUR" value="<?php echo $nomUsagerUtilisateur; ?>"><br>
	<label for="MOT_DE_PASSE">Mot de passe:</label>
	<input type="text" name="MOT_DE_PASSE" value="<?php echo $motDePasse; ?>"><br>
	<div class="actions">
		<input class="boutton" type="submit" name="Connexion" value="Connexion">
	</div>
</form>
<?php include('footer.php'); ?>
