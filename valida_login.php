<?php
// Inicia sessao
session_start();

require_once('./actions/Login.php');
require_once('./dto/Usuario.php');

$db_login = new Login();
$usuario = new Usuario();

// Recupera o login
$cpf = isset($_POST["cpf"]) ? addslashes(trim($_POST["cpf"])) : FALSE;
// Recupera a senha
$senha = isset($_POST["senha"]) ? trim($_POST["senha"]) : FALSE;

;
if($usuario = $db_login->logar($cpf, $senha)){
    $_SESSION['usuario'] = serialize($usuario);
    header('Location: index.php');   
    exit; 
}  else {
    header('Location: form_login.php?error=1'); 
    exit;
}


