<?php
//-------------------------------------------------------------------------------------------
$emailsender='no-replay@jotadf.com.br'; // Substitua essa linha pelo seu e-mail@seudominio
$nomeremetente = 'Jota';//nome do remetente
$emailremetente = 'jotakakashidf@gmail.com';//email do remetente
$emaildestinatario = 'j.wilson.df@gmail.com';//email destinatario
$comcopia = '';//com copia se precisar
$comcopiaoculta = '';//com copia oculta se precisar
$assunto = 'assunto';
$mensagem = 'mensagem';
$mensagemHTML = 'mensagem html do corpo';

//----------------------------
/* Montando o cabeçalho da mensagem */
$quebra_linha = '\n';
$headers = 'MIME-Version: 1.1' .$quebra_linha;
$headers .= 'Content-type: text/html; charset=UTF-8' .$quebra_linha;
// Perceba que a linha acima contem 'text/html', sem essa linha, a mensagem nao chegara formatada.
$headers .= 'From: ' . $emailsender.$quebra_linha;
$headers .= 'Cc: ' . $comcopia . $quebra_linha;
$headers .= 'Bcc: ' . $comcopiaoculta . $quebra_linha;
$headers .= 'Reply-To: ' . $emailremetente . $quebra_linha;
// Note que o e-mail do remetente sera usado no campo Reply-To (Responder Para)

/* Enviando a mensagem */

//obrigatorio o uso do parametro -r (concatenacao do 'From na linha de envio'), aqui na Locaweb:

if(!mail($emaildestinatario, $assunto, $mensagemHTML, $headers ,'-r'.$emailsender)){ // Se for Postfix
$headers .= 'Return-Path: ' . $emailsender . $quebra_linha; // Se 'nao for Postfix'
mail($emaildestinatario, $assunto, $mensagemHTML, $headers );
}

/* FIM */

// }