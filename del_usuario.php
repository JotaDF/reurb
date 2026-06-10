<?php

require_once('./actions/ManterUsuario.php');
require_once('./dto/Usuario.php');

$db_usuario = new ManterUsuario();
$usuario = new Usuario();

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

$db_usuario->excluir($id);

header('Location: usuarios.php');

