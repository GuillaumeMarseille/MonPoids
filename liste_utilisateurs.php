<?php 
/**
 * TP3
 * Équipe 15
 * Frédéric Sheedy (908 378 744)
 * Guillaume Marseille-Pitre (536 857 347)
 */

// Page sécurisée et ajout du header
$securite = true;
include('header.php');

// Bloquer si l'utilisateur n'est pas administrateur
verifierAdministrateur();

// On fait la requête sur la vue TP2_VUE_UTILISATEUR
$stid = oci_parse($conn, "select *
                          from TP2_VUE_UTILISATEUR
                          order by NOM_COMPLET_UTI");
    
// On exécute le select
execute_requete($stid);

?>
<h2>Liste des utilisateurs</h2>

<?php if (isset($_GET['modification']) && !isset($_SESSION['oracle_error'])) { ?>
<div class="information">Modification effectu&eacute;e</div>
<?php } // Fin info ?>

<?php if (isset($_SESSION['oracle_error'])) { ?>
<div class="erreur"><?php echo $_SESSION['oracle_error']; ?></div>
<?php unset($_SESSION['oracle_error']); } // Fin erreur ?>

<div class="recherche">
    <form method="post" action="utilisateur.php">
        <select size="10" name="RECHERCHE_USAGER"> 
        <?php
        // Affichage de la liste des utilisateurs
        while (($utilisateur = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            $affichage = $utilisateur['NOM_COMPLET_UTI'].', '
                        .$utilisateur['NOM_USAGER_UTILISATEUR'].', '
                        .$utilisateur['COURRIEL_UTI'].', '
                        .$utilisateur['TYPE_UTI'];
                        echo '<option value="'.$utilisateur['NOM_USAGER_UTILISATEUR'].'|'.$utilisateur['TYPE_UTI'].'">'.$affichage.'</option>';
        }
        
        
        libere_requete($stid);
        ?>
        </select><br>
    	<input class="boutton" type="submit" name="MiseAJour" value="Mettre &agrave; jour"><br>
    	<input class="boutton" type="submit" name="Deconnecte" value="D&eacute;connecter l'administrateur">
    </form>
</div>
<?php include('footer.php'); ?>
