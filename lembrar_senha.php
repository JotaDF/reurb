<?php

header("Content-type: text/html; charset=utf-8"); 

require_once('./actions/EnviarEmail.php');
require_once('./actions/ManterUsuario.php');
require_once('./dto/Usuario.php');

$db_usuario = new ManterUsuario();
$usuario = new Usuario();

$mail = new EnviarEmail();

$cpf    = $_POST['cpf'];

//print_r($usuario);
$usuario = $db_usuario->getUsuarioPorCPF($cpf);

if($usuario->ativo){
    $destinatario = $usuario->email;
    $assunto = "Recuperação de senha";
    $mensagem = "Olá <strong>" . $usuario->nome ."</strong>,<br/><br/>";
    $mensagem .= "Sua senha para acesso ao Gerente: <strong>" . $usuario->senha ."</strong><br/><br/>";
    $mensagem .= "<a href='http://www.jotadf.com.br/gerente'>Acesse sua conta</a>";
    $mail->enviarPHPMailer($assunto, $destinatario, $mensagem);
    header('Location: form_esqueci_senha.php?msg=1');
} else {
    header('Location: form_esqueci_senha.php?msg=0');
}


