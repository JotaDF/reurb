<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
date_default_timezone_set('America/Sao_Paulo'); 

require_once('./dto/Usuario.php');

$usuario_logado = new Usuario;
if (!isset($_SESSION["usuario"])) {
    // Usuario nao logado! Redireciona para a pagina de login
    header('Location: form_login.php');
    //echo "Não Logou!!";
    exit;
} else {
    
    $usuario_logado = unserialize($_SESSION['usuario']);
}
