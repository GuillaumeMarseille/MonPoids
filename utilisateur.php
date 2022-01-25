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

// Initialiser les variables pour les champs
$nomUsager        = '';
$typeUti          = '';
$nomCompletUti    = '';
$sexeUti          = '';
$dateNaissanceUti = '';
$courrielUti      = '';

if (isset($_POST['Deconnecte'])) {
    // Si l'usager veux se déconnecter
    header('Location: connexion.php?deconnexion=true');
    die();
} else if (isset($_POST['Annuler'])) {
    // Si l'usager veux annuler l'action
    header('Location: liste_utilisateurs.php');
    die();
} else if (isset($_POST['MiseAJour'])) {
    // Affichage de l'utilisateur - on vérifie que le nom et le mot de passe existe
    $rechercheUsager = $_POST['RECHERCHE_USAGER'];
    $rechercheUsager = explode('|', $rechercheUsager);
    $nomUsager       = $rechercheUsager[0];
    $typeUti         = $rechercheUsager[1];

    // Faire la requête en fonction du type d'utilisateur
    if ($typeUti === 'Administrateur') {
        $stid = oci_parse($conn, "select * 
                              from TP2_ADMINISTRATEUR
                              where NOM_USAGER_ADMINISTRATEUR='$nomUsager'
                              fetch first 1 rows only");
    } else {
        $stid = oci_parse($conn, "select PRENOM_MEM, NOM_MEM, COURRIEL_MEM, SEXE_MEM, TO_CHAR(DATE_NAISSANCE_MEM, 'YYYY-MM-DD') as DATE_NAISSANCE_MEM
                              from TP2_MEMBRE
                              where NOM_USAGER_MEM='$nomUsager'
                              fetch first 1 rows only");
    }
    
    
    // On exécute le select
    execute_requete($stid);
    
    // Stocker les valeurs si résultat
    if (($usager = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
        if ($typeUti === 'Administrateur') {
            // Initialiser les variables de l'administrateur
            $nomCompletUti = $usager['PRENOM_ADM'].' '.$usager['NOM_ADM'];
            $courrielUti   = $usager['COURRIEL_ADM'];
        } else {
            // Initialiser les variables du membre
            $nomCompletUti     = $usager['PRENOM_MEM'].' '.$usager['NOM_MEM'];
            $courrielUti       = $usager['COURRIEL_MEM'];
            $sexeUti           = $usager['SEXE_MEM'];
            $dateNaissanceUti  = $usager['DATE_NAISSANCE_MEM'];
        }
    } else {
        // Nom d'usager incorrect
        $erreur = "Nom d'usager incorrect.";
    }
    
    // Libérer la requête Oracle
    libere_requete($stid);
} else if (isset($_POST['OK'])) {
    // Si l'usager veux enregistrer les modifications
    
    $nomUsager   = $_POST['NOM_USAGER_UTILISATEUR'];
    $courrielUti = $_POST['COURRIEL_UTI'];
    $different   = $_POST['TYPE_ORIGINAL_UTILISATEUR'] != $_POST['TYPE_UTI'];
    
    // Requête d'update pour administrateur
    if ($different && $_POST['TYPE_UTI'] == 'Membre') {
        // Le type a changé de administrateur à  membre
        $stid = oci_parse($conn, "insert into TP2_MEMBRE(
                NO_MEMBRE, NOM_USAGER_MEM, MOT_DE_PASSE_MEM, PRENOM_MEM, NOM_MEM, COURRIEL_MEM, SEXE_MEM,
                DATE_NAISSANCE_MEM, POIDS_INITIAL_MEM, POIDS_DESIRE_MEM)
                values(TP2_NO_MEMBRE_SEQ.nextval,
                '${nomUsager}',(select MOT_DE_PASSE_ADM from TP2_ADMINISTRATEUR where NOM_USAGER_ADMINISTRATEUR = '$nomUsager'),
                (select PRENOM_ADM from TP2_ADMINISTRATEUR where NOM_USAGER_ADMINISTRATEUR = '$nomUsager'),
                (select NOM_ADM from TP2_ADMINISTRATEUR where NOM_USAGER_ADMINISTRATEUR = '$nomUsager'),
                '$courrielUti', 'N', to_date('2000-01-01','YYYY-MM-DD'), 1, 1)");
        execute_requete($stid);       
        libere_requete($stid);
        $stid = oci_parse($conn, "delete from TP2_ADMINISTRATEUR
            where NOM_USAGER_ADMINISTRATEUR = '$nomUsager'");
    } else if ($different && $_POST['TYPE_UTI'] == 'Administrateur') {
        // Requête d'update de membre à administrateur
        $stid = oci_parse($conn, " insert into TP2_ADMINISTRATEUR(NOM_USAGER_ADMINISTRATEUR,
                        MOT_DE_PASSE_ADM, PRENOM_ADM, NOM_ADM, COURRIEL_ADM)
                        values('$nomUsager',
                        (select MOT_DE_PASSE_MEM from TP2_MEMBRE where NOM_USAGER_MEM = '$nomUsager'),
                        (select PRENOM_MEM from TP2_MEMBRE where NOM_USAGER_MEM = '$nomUsager'),
                        (select NOM_MEM from TP2_MEMBRE where NOM_USAGER_MEM = '$nomUsager'),
                        '$courrielUti')");
        execute_requete($stid);
        libere_requete($stid);
        $stid = oci_parse($conn, "delete from TP2_MEMBRE
            where NOM_USAGER_MEM = '$nomUsager'");
        
    } else if ($_POST['TYPE_UTI'] === 'Administrateur') {
        // Faire la mise à jour standard administrateur
        $stid = oci_parse($conn, "update TP2_ADMINISTRATEUR
                            set COURRIEL_ADM = '$courrielUti'
                            where NOM_USAGER_ADMINISTRATEUR = '$nomUsager'");      
    } else {
        $sexeUti = $_POST['SEXE_UTI'];
        $dateNaissanceUti = $_POST['DATE_NAISSANCE_UTI'];
        
        // Faire la mise à jour standard membre
        $stid = oci_parse($conn, "update TP2_MEMBRE
                set SEXE_MEM = '$sexeUti',
                COURRIEL_MEM = '$courrielUti',
                DATE_NAISSANCE_MEM = TO_DATE('$dateNaissanceUti','YYYY-MM-DD')
                where NOM_USAGER_MEM = '$nomUsager'");
    }

    // Excéuter et libérer la requête Oracle
    execute_requete($stid);
    libere_requete($stid);
    
    // Rediriger l'utilisateur à la liste
    header('Location: liste_utilisateurs.php?modification=ok');
    die();
}

?>
<h2>Mise &agrave; jour utilisateur</h2>
<form class="formulaire_details" method="post" action="utilisateur.php">

    <label for="NOM_USAGER_UTILISATEUR">Nom d'utilisateur:</label>
    <input type="text" name="NOM_USAGER" value="<?php echo $nomUsager; ?>" disabled><br>
    <label for="TYPE_ADMINISTRATEUR2">Type d'utilisateur:</label>
    <input type="radio" id="TYPE_ADMINISTRATEUR" name="TYPE_UTI" value="Administrateur" <?php if ($typeUti === 'Administrateur') {echo 'Checked';} ?>>
    <label class="etiquette-radio" for="TYPE_ADMINISTRATEUR">Administrateur</label><br>
    <input type="radio" id="TYPE_MEMBRE" name="TYPE_UTI" value="Membre" <?php if ($typeUti === 'Membre') {echo 'Checked';}?>>
    <label class="etiquette-radio" style="display: inline-block;" for="TYPE_MEMBRE">Membre</label><br>
    <label for="NOM_COMPLET_UTI">Nom complet:</label>
    <input type="text" name="NOM_COMPLET_UTI" value="<?php echo $nomCompletUti; ?>" disabled><br>
    <label for="COURRIEL_UTI">Courriel: </label>
    <input type="email" name="COURRIEL_UTI" value="<?php echo $courrielUti; ?>"><br>
    <?php if ($typeUti != 'Administrateur') { ?>
    <label for="SEXE_UTI">Sexe:</label>
    <select size="3" name="SEXE_UTI">
    	<?php
    	$sexeOptions = array('H' => 'Homme', 'F' => 'Femme', 'N' => 'Non pr&eacute;cis&eacute;');
    	foreach ($sexeOptions as $key => $value) {
    	    $sexeSelection = '';
    	    if($key === $sexeUti) {
    	        $sexeSelection = ' selected="selected"';
    	    }
    	    echo '<option value="'.$key.'"'.$sexeSelection.'>'.$value.'</option>';
    	}
    	?>
    </select><br>
    <label for="DATE_NAISSANCE_UTI">Date de naissance:</label>
    <input type="date" name="DATE_NAISSANCE_UTI" value="<?php echo $dateNaissanceUti; ?>"><br>
    <?php } // Fin champs membre ?>
    <input type="text" name="NOM_USAGER_UTILISATEUR" value="<?php echo $nomUsager; ?>" hidden>
    <input type="text" name="TYPE_ORIGINAL_UTILISATEUR" value="<?php echo $typeUti; ?>" hidden>
    <div class="actions">
        <input class="boutton" type="submit" name="OK" value="OK">
        <input class="boutton" type="submit" name="Annuler" value="Annuler">
	</div>
</form>
<?php include('footer.php'); ?>
