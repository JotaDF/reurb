<?php

require_once('./actions/ManterPerfil.php');
require_once('./dto/Perfil.php');

$db_perfil = new ManterPerfil();
$p = new Perfil();

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$perfil = $_POST['perfil'];
$descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';

$p->id = $id;
$p->perfil = $perfil;
$p->descricao = $descricao;

$db_perfil->salvar($p);
header('Location: perfis.php');

