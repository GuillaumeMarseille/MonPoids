<?php
/**
 * TP3
 * Équipe 15
 * Frédéric Sheedy (908 378 744)
 * Guillaume Marseille-Pitre (536 857 347)
 * 
 * Cette page est appelée seulement par la page index.php
 */

// Empêcher l'accès direct à la page
if (!isset($securite)) {
    header('Location: index.php');
    die();
}

?>
<div>
<h3>Inscrivez à la p&eacute;riode d'essai de 30 jours!</h3>
<form class="formulaire_details" method="post" action="index.php">
	<label for="NOM_USAGER_UTILISATEUR">Nom d'utilisateur:</label>
    <input type="text" name="NOM_USAGER_UTILISATEUR" value="" maxlength="20"><br>
    <label for="MOT_DE_PASSE_UTILISATEUR">Mot de passe:</label>
    <input type="text" name="MOT_DE_PASSE_UTILISATEUR" value="" maxlength="12"><br>
    <label for="PRENOM_UTI">Pr&eacute;nom:</label>
    <input type="text" name="PRENOM_UTI" value="" maxlength="20"><br>
    <label for="NOM_UTI">Nom:</label>
    <input type="text" name="NOM_UTI" value="" maxlength="20"><br>
    <label for="COURRIEL_UTI">Courriel: </label>
    <input type="email" name="COURRIEL_UTI" value="" maxlength="30"><br>
    <label for="SEXE_UTI">Sexe:</label>
    <select size="1" name="SEXE_UTI">
    	<?php
    	$sexeOptions = array('H' => 'Homme', 'F' => 'Femme', 'N' => 'Non pr&eacute;cis&eacute;');
    	foreach ($sexeOptions as $key => $value) {
    	    echo '<option value="'.$key.'">'.$value.'</option>';
    	}
    	?>
    </select><br>
    <label for="DATE_NAISSANCE_UTI">Date de naissance:</label>
    <input type="date" name="DATE_NAISSANCE_UTI" value=""><br>
    <label for="POIDS_INITIAL_UTI">Poids initial:</label>
    <input type="number" name="POIDS_INITIAL_UTI" value="" min="1" max="500"><br>
    <label for="POIDS_DESIRE_UTI">Poids d&eacute;sir&eacute;:</label>
    <input type="number" name="POIDS_DESIRE_UTI" value="" min="1" max="500"><br>
    <label for="POIDS_UNITE_UTI">Unit&eacute; de poids</label>
	<input type="radio" id="lb" name="POIDS_UNITE_UTI" value="lb">
	<label class="etiquette-radio" for="POIDS_UNITE_UTI">lb</label><br>
	<input type="radio" id="kg" name="POIDS_UNITE_UTI" value="kg">
	<label class="etiquette-radio" for="POIDS_UNITE_UTI">kg</label><br>

    <div class="actions">
        <input class="boutton" type="submit" name="Inscription" value="M'inscrire">
	</div>
	</form>
</div>
