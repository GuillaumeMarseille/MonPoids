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

// Si l'usager fait une recherche
if (isset($_POST['Rechercher'])) {
    $detailsRecherche = strtolower($_POST['DETAILS_RECHERCHE']);
    
    // On fait la requête sur la table aliments
    $stid = oci_parse($conn, "select *
                              from TP2_ALIMENT
                              where lower(NOM_ALI)='$detailsRecherche' or lower(NUMERO_CODE_BARRE_ALI)='$detailsRecherche'
                              order by NOM_ALI
                              fetch first 5 rows only");
    
    // On exécute le select
    execute_requete($stid);
} else {
    // L'usager ne fait pas de recherche, afficher la liste complète
    // On fait la requête sur la table aliments
    $stid = oci_parse($conn, "select *
                              from TP2_ALIMENT
                              order by NOM_ALI");
    
    // On exécute le select
    execute_requete($stid);
}
?>
<h2>Liste aliments</h2>
<?php if (isset($_GET['modification']) && !isset($_SESSION['oracle_error'])) { ?>
<div class="information">Modification effectu&eacute;e</div>
<?php } // Fin info ?>

<?php if (isset($_SESSION['oracle_error'])) { ?>
<div class="erreur"><?php echo $_SESSION['oracle_error']; ?></div>
<?php unset($_SESSION['oracle_error']); } // Fin erreur ?>

<div class="recherche">
    <form method="post" action="liste_aliments.php">
      	<input type="text" name="DETAILS_RECHERCHE" value="" placeholder="Nom ou code barre de l'aliment"><br>
    	<input class="boutton" type="submit" name="Rechercher" value="Rechercher"><br><br>
    	<a href="aliment.php">Ajouter un aliment</a>
    </form>
</div>

<?php

// Affichage de la table des aliments
$loop = false;

$table_header = '<table>
<tr>
<th>Nom</th>
<th>Portion</th>
<th>Unit&eacute;</th>
<th>Cat&eacute;gorie</th>
<th>Points</th>
<th></th>
</tr>';

while (($aliment = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    // Afficher le header de la table avant les données
    if ($loop === false) {
        echo $table_header;
    }
    echo '<tr>';
    echo '    <td>'.$aliment['NOM_ALI'] . '</td>';
    echo '    <td>'.$aliment['PORTION_ALI'] . '</td>';
    echo '    <td>'.$aliment['UNITE_ALI'] . '</td>';
    echo '    <td>'.$aliment['CATEGORIE_ALI'] . '</td>';
    echo '    <td>'.$aliment['NB_POINTS_ALI'] . '</td>';
    echo '    <td><a href="aliment.php?NO='.$aliment['NO_ALIMENT'] .'">En savoir plus</a></td>';
    echo '</tr>';
    $loop = true;
}

// Aucun aliment dans la liste
if ($loop === false) {
    echo '<div class="avertissement">Aucun aliment trouv&eacute;!</div>';
}

// Libérer la requête Oracle
libere_requete($stid);
?>
</table>
<?php include('footer.php'); ?>
