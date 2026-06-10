<?php

require_once('./actions/ManterEquipe.php');
require_once('./dto/Equipe.php');

$db_equipe = new ManterEquipe();
$e = new Equipe();

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$equipe = $_POST['equipe'];
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';

$e->id = $id;
$e->equipe = $equipe;
$e->descricao = $descricao;

$db_equipe->salvar($e);
header('Location: equipes.php');

