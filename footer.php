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
?>
</div> <!-- /main -->
<div class="footer">
<p>TP3 - IFT-2004 E2021 - &Eacute;quipe 15</p>
</div>
</body>
</html>
<?php
// Fermer la connection avec le serveur Oracle
oci_close($conn);
?>
