<?php

date_default_timezone_set('America/Sao_Paulo');
require 'phpmailer/PHPMailerAutoload.php';

class EnviarEmail {

    function enviar($assunto, $destinatario, $mensagem) {
        //-------------------------------------------------------------------------------------------
        $emailsender = 'nao_responda@jotadf.com.br'; // Substitua essa linha pelo seu e-mail@seudominio
//        $nomeremetente = 'Gerente'; //nome do remetente
//        $emailremetente = 'nao_responda@jotadf.com.br'; //email do remetente
        $emaildestinatario = $destinatario; //email destinatario
//        $comcopia = ''; //com copia se precisar
//        $comcopiaoculta = ''; //com copia oculta se precisar
//        $assunto = $assunto;
        $mensagemHTML = $mensagem;

//----------------------------
        /* Montando o cabeçalho da mensagem */
        $quebra_linha = '\n';
        $headers = 'MIME-Version: 1.1' . $quebra_linha;
        $headers .= "Content-type: text/html; charset=utf-8\n";
// Perceba que a linha acima contem 'text/html', sem essa linha, a mensagem nao chegara formatada.
        $headers .= 'From: ' . $emailsender . $quebra_linha;

        //$headers .= 'Cc: ' . $comcopia . $quebra_linha;
        //$headers .= 'Bcc: ' . $comcopiaoculta . $quebra_linha;
        //$headers .= 'Reply-To: ' . $emailremetente . $quebra_linha;
// Note que o e-mail do remetente sera usado no campo Reply-To (Responder Para)

        /* Enviando a mensagem */

//obrigatorio o uso do parametro -r (concatenacao do 'From na linha de envio'), aqui na Locaweb:

        if (!mail($emaildestinatario, $assunto, $mensagemHTML, $headers, '-r' . $emailsender)) { // Se for Postfix
            $headers .= 'Return-Path: Gerente ' . $emailsender . $quebra_linha; // Se 'nao for Postfix'
            mail($emaildestinatario, $assunto, $mensagemHTML, $headers);
        }
    }

    function enviarPHPMailer($assunto, $destinatario, $mensagem) {
        // Caminho da biblioteca PHPMailer
        // Instância do objeto PHPMailer
        $mail = new PHPMailer;

        // Configura para envio de e-mails usando SMTP
        $mail->isSMTP();

        // Servidor SMTP
        $mail->Host = 'email-ssl.com.br'; //'smtp.gmail.com';
        // Usar autenticação SMTP
        $mail->SMTPAuth = true;

        // Usuário da conta
        $mail->Username = 'gerente@jotadf.com.br'; //'jotakakashidf@gmail.com';
        // Senha da conta
        $mail->Password = 'Lord$wil2020';

        // Tipo de encriptação que será usado na conexão SMTP
        $mail->SMTPSecure = 'ssl';

        // Porta do servidor SMTP
        $mail->Port = 465;

        // Informa se vamos enviar mensagens usando HTML
        $mail->IsHTML(true);

        // Email do Remetente
        $mail->From = 'gerente@jotadf.com.br';

        // Nome do Remetente
        $mail->FromName = 'Gerente';
        $mail->addReplyTo('no-replay@jotadf.com.br');

        // Endereço do e-mail do destinatário
        $mail->addAddress($destinatario);

        // Assunto do e-mail
        $mail->Subject = $assunto;

        // Mensagem que vai no corpo do e-mail
        $mail->Body = $mensagem . "<br/><br/><br/><i>Mensagem automática, favor não responder!<i>";
        
        $mail->CharSet = 'UTF-8';

        // Envia o e-mail e captura o sucesso ou erro
        if ($mail->Send()):
            return TRUE;
        else:
            return FALSE; //. $mail->ErrorInfo;
        endif;
    }

}
