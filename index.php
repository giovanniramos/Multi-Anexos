<?php
// Carregando a classe Multi-Anexos
require_once 'classes/MultiAnexos.class.php';

if ($_POST && MultiAnexos::is_mail($_POST['email'])):

    // Instânciamos a classe, e logo em seguida definimos um email de remetente e destinatário, respectivamente
    $email = new MultiAnexos();
    $email->setMail('from', $_POST['email'], $_POST['nome']);
    $email->setMail('to', 'your_name@domain.com');

    // Encaminhando o email
    $email->send();

endif;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Multi-Anexos</title>
        <link rel="stylesheet" type="text/css" media="screen" href="vendor/formee-3-1/css/formee-structure.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="vendor/formee-3-1/css/formee-style.css" />
        <style>
            * {margin:0;padding:0;}
            form:after, div:after, ol:after, ul:after, li:after, dl:after {content:".";display:block;clear:both;visibility:hidden;height:0;overflow:hidden;} /* fix  ff bugs */
            body {background: #fff; font: normal 10px/1.1em Arial,Sans-Serif;margin:10px 20px;padding:0;}
            form {clear:both;}
        </style>
    </head>
    <body>
        <?php
        if ($_POST):
            echo defined('SEND_RETURN') ? '<h2>' . SEND_RETURN . '</h2>' : '<h2>Informe seu nome e um email válido.</h2>';
        endif;

        #MultiAnexos::showPOST(); // Exibindo as variáveis após submeter o formulário
        #MultiAnexos::showHTML(); // Exibindo um preview da mensagem html formatada
        ?>

        <br />

        <div class="formee-msg-info">
            <h3>Segue as instruções</h3>
            <ul>
                <li>Preencha o formul&aacute;rio abaixo para enviar sua mensagem.</li>
                <li>&Eacute; necess&aacute;rio preencher todos os campos obrigat&oacute;rios.</li>
            </ul>
        </div>

        <form class="formee" action="<?= basename(__FILE__) ?>" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Formul&aacute;rio de Contato</legend>
                <div class="grid-12-12">
                    <label for="nome">Nome <em class="formee-req">*</em></label>
                    <input type="text" id="nome" name="nome" class="formee-small" />
                </div>
                <div class="grid-12-12">
                    <label for="email">E-mail <em class="formee-req">*</em></label>
                    <input type="text" id="email" name="email" class="formee-small" />
                </div>
                <div class="grid-12-12">
                    <label for="telefone">Telefone <em class="formee-req">*</em></label>
                    <input type="text" id="telefone" name="telefone" class="formee-small" />
                </div>
                <div class="grid-12-12">
                    <label for="arquivo1">Anexo #1</label>
                    <input type="file" id="arquivo1" name="arquivo[]" class="formee-small" />
                </div>
                <div class="grid-12-12">
                    <label for="arquivo2">Anexo #2</label>
                    <input type="file" id="arquivo2" name="arquivo[]" class="formee-small" />
                </div>
                <div class="grid-12-12">
                    <label for="arquivo3">Anexo #3</label>
                    <input type="file" id="arquivo3" name="arquivo[]" class="formee-small" />
                </div>
                <div class="grid-12-12">
                    <label for="mensagem">Mensagem <em class="formee-req">*</em></label>
                    <textarea id="mensagem" name="mensagem" cols="10" rows="10"></textarea>
                </div>
                <div class="grid-12-12">
                    <input type="submit" value="ENVIAR" />
                </div>
            </fieldset>
        </form>
    </body>
</html>