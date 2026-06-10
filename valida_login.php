<?php


require_once('./actions/Login.php');
require_once('./dto/Usuario.php');

$db_login = new Login();
$usuario = new Usuario();


// Inicia sessao
session_start();

// Recupera o login
$cpf = isset($_POST["cpf"]) ? addslashes(trim($_POST["cpf"])) : FALSE;
// Recupera a senha
$senha = isset($_POST["senha"]) ? trim($_POST["senha"]) : FALSE;

;
if($usuario = $db_login->logar($cpf, $senha)){
    $_SESSION['usuario'] = serialize($usuario);
    $_SESSION['editar'] = 1;
    header('Location: index.php');    
}  else {
    header('Location: form_login.php?error=1'); 
}


