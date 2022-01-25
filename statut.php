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

if (isset($_SESSION['nom_usager_utilisateur'])) {
?>
	<div class="bienvenue">Bienvenue <?php echo $_SESSION['nom_complet_uti']; ?> <a class="boutton deconnexion" href="connexion.php?deconnexion=true">D&eacute;connecter</a></div>
<?php } else { ?>
	<div class="bienvenue"><a class="boutton connexion" href="connexion.php">Connexion membre</a></div>
<?php } ?>
