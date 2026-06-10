<?php

// Caminho da biblioteca PHPMailer
require 'phpmailer/PHPMailerAutoload.php';
 
// Instância do objeto PHPMailer
$mail = new PHPMailer;
 
// Configura para envio de e-mails usando SMTP
$mail->isSMTP();
 
// Servidor SMTP
$mail->Host = 'email-ssl.com.br'; //'smtp.gmail.com';
 
// Usar autenticação SMTP
$mail->SMTPAuth = true;
 
// Usuário da conta
$mail->Username = 'nao_responda@jotadf.com.br';//'jotakakashidf@gmail.com';
 
// Senha da conta
$mail->Password = 'Lord$wil2020';
 
// Tipo de encriptação que será usado na conexão SMTP
$mail->SMTPSecure = 'ssl';
 
// Porta do servidor SMTP
$mail->Port = 465;
 
// Informa se vamos enviar mensagens usando HTML
$mail->IsHTML(true);
 
// Email do Remetente
$mail->From = 'nao_responda@jotadf.com.br';
 
// Nome do Remetente
$mail->FromName = 'Gerente';
 
// Endereço do e-mail do destinatário
$mail->addAddress('j.wilson.df@gmail.com');
 
// Assunto do e-mail
$mail->Subject = 'Gerente';
 
// Mensagem que vai no corpo do e-mail
$mail->Body = '<h1>Mensagem enviada via PHPMailer</h1><br/><a href="http://www.jootadf.com.br/gerente">Acesse sua conta</a>';
 
// Envia o e-mail e captura o sucesso ou erro
if($mail->Send()):
    echo 'Enviado com sucesso !';
else:
    echo 'Erro ao enviar Email:' . $mail->ErrorInfo;
endif;