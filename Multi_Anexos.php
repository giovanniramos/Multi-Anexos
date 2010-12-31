<?php

require "_inc/MultiAnexos.class.php";

if( $_POST && MultiAnexos::is_mail($_POST['email']) ):

	// Instânciamos a classe, sem assunto
	$email = new MultiAnexos();
	$email->setMail('From', $_POST['email'], $_POST['nome']);
	$email->setMail('To', 'name@domain.com');
	$email->send();
	
endif;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>Multi-Anexos</title>
<link href="_css/typo.css" rel="stylesheet" type="text/css" />
</head>


<body>
<div id="div">


	<?php

	if( $_POST ):
		echo defined('SEND_RETURN') ?
		'<h2>'.SEND_RETURN.'</h2>' : '<h2>Informe seu nome e um email válido.</h2>' ;
	endif;


	// Exibindo as variáveis postadas após submeter o formulário
	#MultiAnexos::showPOST();

	// Exibindo um preview da mensagem html formatada
	#MultiAnexos::showHTML();

	?>


	<h1>Submetendo um formul&aacute;rio com m&uacute;ltiplos anexos</h1>	

	<p>Preencha o formulário abaixo para enviar sua mensagem.<br />É necessário preencher todos os campos.</p>

	<form id="frm" action="<?=basename(__FILE__)?>" method="post" enctype="multipart/form-data">

		<fieldset>
			<legend>Formulário de Contato</legend>
			<p>
				<label for="nome">Nome:</label>
				<input id="nome" name="nome" />
			</p>
			<p>
				<label for="email">E-Mail:</label>
				<input id="email" name="email" />
			</p>
			<p>
				<label for="telefone">Telefone:</label>
				<input id="telefone" name="telefone" />
			</p>
			<p>
				<label for="arquivo1">Anexo #1:</label>
				<input type="file" name="arquivo[]" id="arquivo1" size="50" class="file" />
			</p>
			<p>
				<label for="arquivo2">Anexo #2:</label>
				<input type="file" name="arquivo[]" id="arquivo2" size="50" class="file" />
			</p>
			<p>
				<label for="arquivo3">Anexo #3:</label>
				<input type="file" name="arquivo[]" id="arquivo3" size="50" class="file" />
			</p>
			<p>
				<label for="mensagem">Mensagem:</label>
				<textarea id="mensagem" name="mensagem" cols="10" rows="10" class="text"></textarea>
			</p>
			<span>
				<input type="submit" value="ENVIAR" class="send" />
			</span>
		</fieldset>

	</form>


</div>
</body>
</html>