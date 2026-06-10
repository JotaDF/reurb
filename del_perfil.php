<?php

require_once('./actions/ManterPerfil.php');
require_once('./dto/Perfil.php');

$db_perfil = new ManterPerfil();
$perfil = new Perfil();

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

if ($id > 0) {
    $db_perfil->excluir($id);
    header('Location: perfis.php');
} else {
    echo 'Falta de parâmetro!';
    header('Location: perfis.php');
}
