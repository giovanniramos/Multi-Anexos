<?php
// Carregando a classe Multi-Anexos
require_once 'classes/MultiAnexos.class.php';

if ($_POST && MultiAnexos::is_mail($_POST['email'])):

    // Instânciamos a classe, e logo em seguida definimos um e-mail de remetente e destinatário, respectivamente
    $multianexo = new MultiAnexos();
    $multianexo->setMail('from', $_POST['email'], $_POST['nome']);
    $multianexo->setMail('to', 'email@google.com');
    $multianexo->setTitle('MENSAGEM');

    // Exemplo de estilização da mensagem de e-mail
    $multianexo->setCssBody('background:#eee;')->setCssTable('margin:auto;')->setCssTableTr('font-size:12px;')->setCssTableTh('color:#fff;background-color:#222;')->setCssTableTd('color:#222;background-color:#fff;');

    // Encaminhando o e-mail
    $multianexo->send();

endif;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Multi-Anexos</title>
        <script src="vendor/formee-3-1/js/jquery-1.6.4.min.js"></script>
        <script src="vendor/formee-3-1/js/formee.js"></script>
        <link rel="stylesheet" href="vendor/formee-3-1/css/formee-structure.css" media="screen" />
        <link rel="stylesheet" href="vendor/formee-3-1/css/formee-style.css" media="screen" />
        <style>
            * {margin:0;padding:0;}
            form:after, div:after, ol:after, ul:after, li:after, dl:after {content:".";display:block;clear:both;visibility:hidden;height:0;overflow:hidden;} /* fix  ff bugs */
            body {background:#fff;font:normal 10px/1.1em Arial,Sans-Serif;margin:10px 20px;padding:0;}
            form {clear:both;}
        </style>
    </head>
    <body>
        <?php
        if ($_POST):
            echo defined('SEND_RETURN') ? SEND_RETURN : '<div class="formee-msg-warning"><h3>Informe seu nome e um email válido.</h3></div>';
        endif;

        #MultiAnexos::showPOST(); // Exibindo as variáveis após submeter o formulário
        #MultiAnexos::showHTML(); // Exibindo a mensagem html estilizada após envio
        ?>

        <br />

        <div class="formee-msg-info">
            <h3>Segue as instruções</h3>
            <ul>
                <li>Preencha o formul&aacute;rio abaixo para enviar sua mensagem.</li>
                <li>&Eacute; necess&aacute;rio preencher todos os campos obrigat&oacute;rios.</li>
            </ul>
        </div>

        <form class="formee" action="<?php echo basename(__FILE__); ?>" method="post" enctype="multipart/form-data">
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
                    <label for="arquivo1">Anexo #1</label>
                    <div class="file-wrapper">
                        <input type="text" id="arquivo1" readonly="readonly">
                        <div>
                            <button></button><input type="file" name="arquivo[]" class="formee-small">
                        </div>
                    </div>
                </div>
                <div class="grid-12-12">
                    <label for="arquivo2">Anexo #2</label>
                    <div class="file-wrapper">
                        <input type="text" id="arquivo2" readonly="readonly">
                        <div>
                            <button></button><input type="file" name="arquivo[]" class="formee-small">
                        </div>
                    </div>
                </div>
                <div class="grid-12-12">
                    <label for="arquivo3">Anexo #3</label>
                    <div class="file-wrapper">
                        <input type="text" id="arquivo3" readonly="readonly">
                        <div>
                            <button></button><input type="file" name="arquivo[]" class="formee-small">
                        </div>
                    </div>
                </div>
                <div class="grid-12-12">
                    <label for="mensagem">Mensagem</label>
                    <textarea id="mensagem" name="mensagem" cols="10" rows="10" class="formee-small"></textarea>
                </div>
                <div class="grid-12-12">
                    <input type="submit" value="ENVIAR" class="formee-button" />
                </div>
            </fieldset>
        </form>
    </body>
</html>