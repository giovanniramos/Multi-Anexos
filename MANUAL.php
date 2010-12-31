<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Multi-Anexos - Manual</title>
</head>


<body>

<?php
highlight_string('
<?php 

// Importando a classe Multi-Anexos.
include "_inc/MultiAnexos.class.php";


// Criamos o objeto instanciando a classe. 
$email = new MultiAnexos();


// SUBJECT: Opcionalmente informamos o assunto da mensagem durante a instância da classe
// Se nenhum assunto for atribuído à mensagem, a mesma assumirá o padrão "Sem assunto".

# Modo 1: Assunto definido na instância
$email = new MultiAnexos("Assunto da mensagem"); 

# Modo 2: Outra forma de definir o assunto
$email->setSubject("Assunto da mensagem inline");


// FROM: Informando o E-mail do remetente.
$email->setMail("From", "seu_email@dominio_com", "Seu Nome"); // O 3º parâmetro é opcional. Serve para nomear o e-mail.
$email->setMail("From", "outro_email@dominio.com"); // A classe suporta adicionar múltiplos emails.
$email->setMail("From", "email_errado.com.br"); // Este e-mail está incorreto, portanto não será adicionado.


// TO: Informando o E-mail do destinatário.
$email->setMail("To", "email_destino1@dominio.com", "Destino");
$email->setMail("To", "email_destino2@dominio.com"); 


// CC: E-mail que receberá uma cópia da mensagem
$email->setMail("Cc", "email_copia1@dominio.com", "Cópia 1");


// BCC: E-mail oculto que receberá uma cópia da mensagem
$email->setMail("Bcc", "email_copia_oculta1@dominio.com", "Cópia oculta 1");


// REPLY-TO: E-mail de resposta
$email->setMail("Replyto", "email_resposta@dominio.com", "Nome opcional");



// RETURN-PATH: Importante informar um e-mail de retorno. Isso evita bloqueios anti-spam de servidores como Gmail ou Hotmail
// Por padrão é TRUE e geralmente o Return-Path é o e-mail do remetente.
// Altere para FALSE se deseja desativar, ou um E-MAIL para sobrescrever a do servidor. Sobrescrever não é permitido em alguns servidores de email.

# Modo 1
$email->setReturnPath("email_retorno@dominio.com"); // Sobrescrevendo

# Modo 2
$email->setReturnPath(false); // Desativando o e-mail de retorno



// Configurando manualmente o corpo do email
$email->setHTML("
	<b>Telefone:</b> {$_POST["telefone"]}<br />
	<b>Mensagem:</b> {$_POST["mensagem"]}<br />
");


// Submetendo o formulário
$email->send();

?>


<html>

	<head></head>
	
	<body>

		<?php

		// Exibindo as variáveis post, após submissão do formulário
		MultiAnexos::showPOST();

		// Exibindo um preview da mensagem html formatada
		MultiAnexos::showHTML();

		// Exibindo uma mensagem de status do envio da mensagem
		if($_POST):
			echo defined("SEND_RETURN") ?
			"<h2>".SEND_RETURN."</h2>" :
			"<h2>Informe seu nome e um e-mail válido.</h2>" ;
		endif;

		?>

	</body>

</html>

');
?>

</body>
</html>