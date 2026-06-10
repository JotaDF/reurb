<?php


require_once('./actions/ManterUsuario.php');
require_once('./dto/Usuario.php');

$db_usuario = new ManterUsuario();
$usuario = new Usuario();

$id     = isset($_POST['id']) ? $_POST['id'] : 0;
$senha  = $_POST['senha'];

$usuario->id      = $id;
$usuario->senha   = $senha;   

//print_r($usuario);
$db_usuario->alterarSenha($usuario);

header('Location: index.php');

