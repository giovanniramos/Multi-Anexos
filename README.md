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
$multianexo = new MultiAnexos();


// SUBJECT: são duas as formas de se definir o assunto da mensagem
# 1 - definindo durante a instância da classe
$multianexo = new MultiAnexos('Assunto da mensagem'); 

# 2 - definindo através do método setSubject()
$multianexo->setSubject('Assunto da mensagem');


// Definindo o título da mensagem de e-mail
$multianexo->setTitle('MENSAGEM');


// Formatando o corpo da mensagem de e-mail
$mensagem = "Nome do cliente: " . $_POST['nome'] . "<br />";
$mensagem.= "E-mail de contato: " . $_POST['email'] . "<br />";
$mensagem.= "Mensagem: " . $_POST['mensagem'] . "<br />";

$multianexo->setHTML($mensagem);


// FROM: definindo o e-mail do remetente
$multianexo->setMail('from', 'primeiro_email@google.com', 'Seu Nome'); // O 3º parâmetro é opcional
$multianexo->setMail('from', 'segundo_email@google.com'); // O método setMail(), suporta adicionar múltiplos e-mails
$multianexo->setMail('from', 'email_incorreto.com'); // Este e-mail foi definido incorretamente e portanto não será adicionado


// TO: definindo o e-mail do destinatário
$multianexo->setMail('to', 'joao@google.com');
$multianexo->setMail('to', 'maria@google.com', 'Maria'); 


// CC: definindo um e-mail que receberá a mensagem como cópia
$multianexo->setMail('cc', 'pedro@google.com', 'Pedro');


// BCC: definindo um e-mail oculto que receberá a mensagem como cópia
$multianexo->setMail('bcc', 'augusto@google.com', 'Augusto');


// REPLY-TO: definindo um e-mail de resposta
$multianexo->setMail('replyto', 'joao@google.com', 'João');


// RETURN-PATH: é muito importante informar um e-mail de retorno
// Isso evita bloqueios anti-spam de servidores como Gmail ou Hotmail
// O valor padrão é TRUE e geralmente o return-path é definido como e-mail do remetente
// Troque para FALSE se desejar desativar, ou um outro e-mail para sobrescrever a do servidor
// Nota: sobrescrever não é permitido em alguns servidores de e-mail

# 1 - sobrescrevendo o e-mail padrão
$multianexo->setReturnPath('outro_email@google.com');

# 2 - desativando o e-mail de retorno
$multianexo->setReturnPath(false);


// Definindo a estilização da mensagem de e-mail
$multianexo->setStyleBody('background:#eee;');
$multianexo->setStyleTable('margin:auto;');
$multianexo->setStyleTableTr('font-size:12px;');
$multianexo->setStyleTableTh('color:#fff;background-color:#222;');
$multianexo->setStyleTableTd('color:#222;background-color:#fff;');


// E por fim, depois de toda a configuração, para enviar o formulário utilize o método send()
// Nota: Se você estiver testando o script em um servidor local (Ex.:Xampp), abra o php.ini,
// localize e descomente a linha com "sendmail_path", para usar a função mail()
$multianexo->send();

?>
~~~ 


Outros métodos da classe
--------------------------------------------------

`MultiAnexos::showPOST()`: exibindo as variáveis logo após o envio do formulário

`MultiAnexos::showHTML()`: exibindo um preview da mensagem html formatada
