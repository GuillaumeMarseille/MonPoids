<?php
/**
 * TP3
 * Équipe 15
 * Frédéric Sheedy (908 378 744)
 * Guillaume Marseille-Pitre (536 857 347)
 */
session_start();  // Démarrer la session

// Rediriger si l'utilisateur n'est pas connecté et essaie d'accéder à une page sécurisé
if (!isset($_SESSION['nom_usager_utilisateur']) && $securite === true) {
    header('Location: connexion.php?erreur=interdit');
    die();
}

// Fonction pour vérifier que l'utilisateur est administrateur
function verifierAdministrateur() {
    if ($_SESSION['type_uti'] != 'Administrateur') {
        header('Location: index.php');
        die();
    }
}

// Inclure l'information pour accéder à la base de données
include('init.php');
?>
<html>
<head>
<title>Mon poids, &ccedil;a compte</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="header">
	<h1>Mon poids, &ccedil;a compte</h1>
	<?php include('statut.php'); ?>
</div>

<?php if (isset($_SESSION['nom_usager_utilisateur'])) { ?>
<div class="menu">
	<h3 class="menu-titre">Menu</h3>
    <a href="aliment.php">Ajouter un aliment</a>
    <a href="liste_aliments.php">Liste des aliments</a>
    <?php 
    if (isset($_SESSION['type_uti']) && $_SESSION['type_uti'] == 'Administrateur') {
    ?>
    <a href='liste_utilisateurs.php'>Liste des utilisateurs</a>
    <?php } ?>
</div>
<?php } ?>

<div id="main">
