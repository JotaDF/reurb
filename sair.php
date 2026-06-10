<?php
// Removendo usuario da sessao
session_start();

$_SESSION["usuario"]   = null;
session_unset();
session_destroy();

//Redireciona para login
echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=form_login.php\">";
exit;

?>

