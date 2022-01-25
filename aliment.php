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

// Initialiser les variables pour les champs
$nomAli        = '';
$codeAli       = '';
$portionAli    = '';
$uniteAli      = '';
$nbLipideAli   = '';
$nbGlucideAli  = '';
$nbFibreAli    = '';
$nbProteineAli = '';
$nbPointAli    = '';

// Si l'usager veux annuler l'action
if (isset($_POST['Annuler'])) {
    header('Location: liste_aliments.php');
    die();
} else if (isset($_POST['Ajouter'])) {
    // Si l'usager veux enregistrer
    // On fait la requête sur la table aliments
    $nomAli        = $_POST['NOM_ALI'];
    $codeAli       = $_POST['NUMERO_CODE_BARRE_ALI'];
    $portionAli    = $_POST['PORTION_ALI'];
    $uniteAli      = $_POST['UNITE_ALI'];
    $nbLipideAli   = $_POST['NB_LIPIDE_ALI'];
    $nbGlucideAli  = $_POST['NB_GLUCIDE_ALI'];
    $nbFibreAli    = $_POST['NB_FIBRE_ALI'];
    $nbProteineAli = $_POST['NB_PROTEINE_ALI'];
    $nbPointAli    = $_POST['NB_POINTS_ALI'];
    //Statement pour ajouter un aliment. Fonctionnel
    $stid = oci_parse($conn, "insert into TP2_ALIMENT (NO_ALIMENT, NOM_ALI, NUMERO_CODE_BARRE_ALI, PORTION_ALI, UNITE_ALI, NB_LIPIDE_ALI, 
                                                       NB_GLUCIDE_ALI, NB_FIBRE_ALI, NB_PROTEINE_ALI, NB_POINTS_ALI)
    values (TP2_NO_ALIMENT_SEQ.nextval, '$nomAli', '$codeAli', $portionAli, '$uniteAli', 
            $nbLipideAli, $nbGlucideAli, $nbFibreAli, $nbProteineAli, (select FCT_POINTS_ALIMENT($nbLipideAli, $nbGlucideAli, 
                                                                                                 $nbFibreAli, $nbProteineAli) from DUAL))");

    // On exécute le select
    execute_requete($stid);
    header('Location: liste_aliments.php');
    die();
   }  else if (isset($_POST['Modifier'])){
       $nomAli        = $_POST['NOM_ALI'];
       $codeAli       = $_POST['NUMERO_CODE_BARRE_ALI'];
       $portionAli    = $_POST['PORTION_ALI'];
       $uniteAli      = $_POST['UNITE_ALI'];
       $nbLipideAli   = $_POST['NB_LIPIDE_ALI'];
       $nbGlucideAli  = $_POST['NB_GLUCIDE_ALI'];
       $nbFibreAli    = $_POST['NB_FIBRE_ALI'];
       $nbProteineAli = $_POST['NB_PROTEINE_ALI'];

       $stid = oci_parse($conn, "update TP2_ALIMENT
       set NOM_ALI = '$nomAli',
       NUMERO_CODE_BARRE_ALI = '$codeAli',
       PORTION_ALI = $portionAli,
       UNITE_ALI = '$uniteAli',
       NB_LIPIDE_ALI = $nbLipideAli,
       NB_GLUCIDE_ALI = $nbGlucideAli,
       NB_FIBRE_ALI = $nbFibreAli,
       NB_PROTEINE_ALI = $nbProteineAli,
       NB_POINTS_ALI = (select FCT_POINTS_ALIMENT($nbLipideAli , $nbGlucideAli, $nbFibreAli, $nbProteineAli) from DUAL)
       where NUMERO_CODE_BARRE_ALI = '$codeAli' or NOM_ALI = '$nomAli'"); 
       
       execute_requete($stid);
       header('Location: liste_aliments.php?modification=true');
       die();
    }


// Si l'usager veux afficher un aliment
if (isset($_GET['NO'])) {
    // TODO: vérifier pour un bind?
    // On vérifie que le nom et le mot de passe existe
    $noAliment = $_GET['NO'];
    $stid = oci_parse($conn, "select *
                              from TP2_ALIMENT
                              where NO_ALIMENT='$noAliment'
                              fetch first 1 rows only");
    
    //on exécute le select
    execute_requete($stid);
    
    // Stocker les valeurs si résultat
    if (($aliment = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
        $noAli         = $aliment['NO_ALIMENT'];
        $nomAli        = $aliment['NOM_ALI'];
        $codeAli       = $aliment['NUMERO_CODE_BARRE_ALI'];
        $portionAli    = $aliment['PORTION_ALI'];
        $uniteAli      = $aliment['UNITE_ALI'];
        $nbLipideAli   = $aliment['NB_LIPIDE_ALI'];
        $nbGlucideAli  = $aliment['NB_GLUCIDE_ALI'];
        $nbFibreAli    = $aliment['NB_FIBRE_ALI'];
        $nbProteineAli = $aliment['NB_PROTEINE_ALI'];
        $nbPointAli    = $aliment['NB_POINTS_ALI'];
    } else {
        // NoAliment incorrect
        $erreur = "Num&eacute;ro d'aliment incorrect.";
    }

    // Libérer la requête Oracle
    libere_requete($stid);
}
?>
<h2>Page aliment</h2>
<form class="formulaire_details" method="post" action="aliment.php">
    <label for="NOM_ALI">Nom:</label>
    <input type="text" name="NOM_ALI" value="<?php echo $nomAli;?>" maxlength="20"><br>
    <label for="NUMERO_CODE_BARRE_ALI">Code barre:</label>
    <input type="number" name="NUMERO_CODE_BARRE_ALI" value="<?php echo $codeAli;?>" max="999999999999" >
    <span class="aide">Maximum 12 caract&egrave;res (entre 0 et 9)</span><br>
    <label for="PORTION_ALI">Portion: </label>
    <select name="PORTION_ALI">
        <option value="0.125">1/8</option>
        <option value="0.25">1/4</option>
        <option value="0.5">1/4</option>
        <option value="1">1</option>
        <option value='2'>2</option>
        <option value="5">5</option>
        <option value="10">10</option>
        <option selected="<?php echo $portionAli;?>"><?php echo $portionAli;?></option>
        
    </select><br>
    <label for="UNITE_ALI">Unité: </label>
    <select name="UNITE_ALI">
         <option value="gramme">gramme</option>
         <option value="livre">livre</option>
         <option value="tasse">tasse</option>
         <option value="cuillerée à soupe">cuillerée à soupe</option>
         <option value='cuillerée à thé'>cuillerée à thé</option>
         <option value="oz">oz</option>
         <option value="ml">ml</option>
         <option value="tranche">tranche</option>
         <option value="morceau">morceau</option>
         <option selected="<?php echo $uniteAli;?>"><?php echo $uniteAli;?></option>
     </select><br>
    <label for="NB_LIPIDE_ALI">Lipides:</label>
    <input type="number" name="NB_LIPIDE_ALI" value="<?php echo $nbLipideAli;?>" step="0.01"><br>
    <label for="NB_GLUCIDE_ALI">Glucides:</label>
    <input type="number" name="NB_GLUCIDE_ALI" value="<?php echo $nbGlucideAli;?>" step="0.01"><br>
    <label for="NB_FIBRE_ALI">Fibres:</label>
    <input type="number" name="NB_FIBRE_ALI" value="<?php echo $nbFibreAli;?>" step="0.01"><br>
    <label for="NB_PROTEINE_ALI">Protéines:</label>
    <input type="number" name="NB_PROTEINE_ALI" value="<?php echo $nbProteineAli;?>" step="0.01"><br>
    <?php if (isset($_GET['NO'])){ // Afficher les points seulement à l'édition ?>
    <label for="NB_POINTS_ALI">Points:</label>
	<input type="number" name="NB_POINTS_ALI" value="<?php echo $nbPointAli;?>" disabled><br>
	<?php } ?>
    <div class="actions">
    	<?php if (isset($_GET['NO'])){
    	    $action = 'Modifier';}
    	    else {$action = 'Ajouter';
    	}?>
        <br><input class="boutton" type="submit" name="<?php echo $action;?>" value="<?php echo $action;?>">
        <input class="boutton" type="submit" name="Annuler" value="Annuler">
	</div>

</form>
<?php
// Inclure les fichiers rabais et footer
include('rabais.php');
include('footer.php');
?>
