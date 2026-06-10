<?php

require_once('./actions/ManterEquipe.php');
require_once('./dto/Equipe.php');

$db_equipe = new ManterEquipe();
$equipe = new Equipe();

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

if ($id > 0) {
    $db_equipe->excluir($id);
    header('Location: equipes.php');
} else {
    echo 'Falta de parâmetro!';
    header('Location: equipes.php');
}
