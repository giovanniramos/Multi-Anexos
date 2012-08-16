# Multi-Anexos

Esta classe pode ser usada para compor e enviar mensagens de e-mail com anexos.

É possível compor a mensagem indicando os e-mails de remetente, destinatário, cópia, cópia-oculta e resposta.

A classe suporta o envio de múltiplos arquivos anexos à mensagem.

A mensagem escrita é enviada com a função mail().



Introdução
==================================================

~~~ php
<?php

// Carregando a classe Multi-Anexos
require_once("classes/MultiAnexos.class.php");

?>
~~~ 


Configuração
--------------------------------------------------

~~~ php
<?php

// Iniciando a instância da classe
$email = new MultiAnexos();


// Configurando o corpo do email
$email->setHTML("
	<b>Telefone:</b> " . $_POST['telefone'] . "<br />
	<b>Mensagem:</b> " . $_POST['mensagem'] . "<br />
");


// SUBJECT: são duas as formas de se definir o assunto da mensagem

# Forma 1 - definindo durante a instância da classe
$email = new MultiAnexos('Assunto da mensagem'); 

# Forma 2 - definindo usando o método setSubject()
$email->setSubject('Assunto da mensagem');


// FROM: definindo o e-mail do remetente
$email->setMail('from', 'primeiro_email@google.com', 'Seu Nome'); // O 3º parâmetro é opcional e serve apenas para apelidar o e-mail
$email->setMail('from', 'segundo_email@google.com'); // O método setMail(), suporta adicionar múltiplos e-mails
$email->setMail('from', 'email_errado.com'); // Este e-mail está incorreto, portanto não será adicionado


// TO: definindo o e-mail do destinatário
$email->setMail('to', 'joao@google.com');
$email->setMail('to', 'maria@google.com', 'Maria'); 


// CC: definindo um e-mail que receberá a mensagem como cópia
$email->setMail('cc', 'pedro@google.com', 'Pedro');


// BCC: definindo um e-mail oculto que receberá a mensagem como cópia
$email->setMail('bcc', 'augusto@google.com', 'Augusto');


// REPLY-TO: definindo um e-mail de resposta
$email->setMail('replyto', 'joao@google.com', 'João');


// RETURN-PATH: é muito importante informar um e-mail de retorno
// Isso evita bloqueios anti-spam de servidores como Gmail ou Hotmail
// O valor padrão é TRUE e geralmente o return-path é definido como e-mail do remetente
// Troque para FALSE se desejar desativar, ou um outro e-mail para sobrescrever a do servidor
// Nota: sobrescrever não é permitido em alguns servidores de e-mail

# Forma 1 - sobrescrevendo o e-mail padrão
$email->setReturnPath('outro_email@google.com');

# Forma 2 - desativando o e-mail de retorno
$email->setReturnPath(false);


// E por fim, depois de toda a configuração, para enviar o formulário utilize o método send()
// Nota: Se você estiver testando o script em um servidor local (Ex.:Xampp), abra o php.ini,
// localize e descomente a linha com "sendmail_path", para usar a função mail()
$email->send();

?>
~~~ 


Outros métodos da classe
--------------------------------------------------

`MultiAnexos::showPOST()`: exibindo as variáveis logo após o envio do formulário

`MultiAnexos::showHTML()`: exibindo um preview da mensagem html formatada
