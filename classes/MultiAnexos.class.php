<?php

/**
 * Multi-Anexos | Multiple File Attachments 
 * Envio de e-mail com múltiplos arquivos anexados
 * 
 * @category E-MAIL
 * @package MultiAnexos
 * @author Giovanni Ramos <giovannilauro@gmail.com>
 * @copyright 2009-2012, Giovanni Ramos
 * @since 2009-09-23 
 * @version 2.8
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link https://github.com/giovanniramos/Multi-Anexos
 *
 * */
class MultiAnexos
{
    /**
     * Armazena o cabeçalho e o corpo da mensagem, respectivamente
     * 
     * @access private static
     * @var string
     * 
     * */
    private static $head, $body;

    /**
     * Armazena o e-mail padrão de retorno
     * 
     * @access private
     * @var string
     * 
     * */
    private $returnPath;

    /**
     * Armazenam a estilização da mensagem de e-mail
     * 
     * @access private
     * @var string
     * 
     * */
    private $_body, $_table, $_table_tr, $_table_th, $_table_td;

    /**
     * Define um limite no cabeçalho da mensagem
     * 
     * @access private
     * @var array
     * 
     * */
    private $boundary = array();

    /**
     * O objeto MultiAnexos ao ser instanciado, pode opcionalmente atribuir um assunto à mensagem
     * 
     * @access public
     * @param string $subject Assunto da mensagem
     * 
     * */
    public function __construct($subject = null)
    {
        self::setReturnPath();

        $this->title = null;
        $this->subject = $subject;
        $this->boundary = '==Multipart_Boundary_' . md5(uniqid(time()));
    }

    /**
     * Método para validar o e-mail
     * 
     * @access public static
     * @param string $mail E-mail válido
     * @return boolean Retorna true, se o e-mail estiver correto
     *
     * */
    public static function is_mail($mail = null)
    {
        return (filter_var(trim($mail), FILTER_VALIDATE_EMAIL) ? true : false);
    }

    /**
     * Método para atribuir um título ao corpo do e-mail
     * 
     * @access public
     * @param string $title Título do e-mail
     * @return void
     *
     * */
    public function setTitle($title = null)
    {
        $this->title = $title;
    }

    /**
     * Método para devolver o título do e-mail
     * 
     * @access private
     * @return string Retorna vazio, se o título não for definido
     *
     * */
    private function getTitle()
    {
        return (!is_null($this->title)) ? $this->title : null;
    }

    /**
     * Método para atribuir um assunto à mensagem
     * 
     * @access public
     * @param string $subject Assunto da mensagem
     * @return void
     *
     * */
    public function setSubject($subject = null)
    {
        $this->subject = $subject;
    }

    /**
     * Método para devolver o assunto da mensagem
     * 
     * @access private
     * @return string Retorna "Sem assunto", se $subject não for definido
     *
     * */
    private function getSubject()
    {
        return (!is_null($this->subject)) ? $this->subject : 'Sem assunto';
    }

    /**
     * Método para incorporar um e-mail à mensagem
     * 
     * @access public
     * @param string $type Tipo pré-definido de e-mail: From, To, Cc, Bcc ou Reply-To
     * @param string $mail Endereço de e-mail
     * @param string $name Um nome ou apelido para o e-mail (OPCIONAL)
     * @return array Retorna o email
     *
     * */
    public function setMail($type, $mail = null, $name = null)
    {
        if (isset($mail) && self::is_mail($mail)):
            $type = strtolower($type);
            $type = str_replace('-', null, $type);
            $type = str_replace('_', null, $type);

            if (isset($type) && mb_ereg('^[[:lower:]]*$', $type)):
                if (!is_array(@$this->$type))
                    $this->$type = array();
                array_push($this->$type, array($mail, $name));
                return $this->$type;
            endif;
        endif;
    }

    /**
     * Método para retornar a lista de e-mails atribuída à mensagem
     * 
     * @access private
     * @param string $type Tipo de e-mail: From, To, Cc, Bcc ou Reply-To
     * @return string Retorna a lista de acordo com o tipo
     *
     * */
    private function getMail($type)
    {
        if (isset($type)):
            if (!$this->$type)
                return false;
            if (!is_array(@$list))
                $list = array();

            foreach ($this->$type as $k => $v)
                if (is_array($v))
                    $list[] = (is_null($v[1])) ? $v[0] : $v[1] . ' <' . $v[0] . '>';
            return implode(', ', $list);
        endif;
    }

    /**
     * Método para incorporar um e-mail de retorno em caso de falha no envio da mensagem
     * IMPORTANTE: Informar um return-path, evita bloqueios anti-spam de servidores como Gmail ou Hotmail
     * 
     * @access public
     * @param string $returnPath Por padrão um e-mail de retorno será atribuído a mensagem
     * @return void
     *
     * */
    public function setReturnPath($returnPath = true)
    {
        $this->returnPath = $returnPath;
    }

    /**
     * Método para devolver o e-mail de retorno da mensagem
     * 
     * @access private
     * @return string Retorna o e-mail de retorno
     *
     * */
    private function getReturnPath()
    {
        return (!is_bool($this->returnPath)) ? $this->returnPath : ( (bool) $this->returnPath ? $this->from[0][1] : null );
    }

    /**
     * Método para capturar os valores enviados pelo formulário
     * 
     * @access private
     * @return string Retorna uma lista formatada dos valores
     *
     * */
    private function getPOST()
    {
        $list = null;

        foreach ($_POST as $k => $v)
            $list.= '<b>' . ucfirst($k) . ':</b> ' . $v . '<br />';
        return $list;
    }

    /**
     * Método para exibir os valores enviados pelo formulário via POST
     * 
     * @access public static
     * @return string void
     *
     * */
    public static function showPOST()
    {
        if ($_POST)
            echo '<pre>', htmlspecialchars(print_r($_POST, true)), '</pre>';
    }

    /**
     * Método para aplicar estilo ao <body> da mensagem de e-mail
     * 
     * @access public
     * @param string $css Regras CSS
     * @return \MultiAnexos
     *
     * */
    public function setCssBody($css)
    {
        $this->_body = $css;

        return $this;
    }

    /**
     * Método para devolver a estilização do <body>
     * 
     * @access private
     * @return string Regras CSS
     *
     * */
    private function getCssBody()
    {
        $css = 'padding:20px 0;';

        return (!empty($this->_body)) ? $css . $this->_body : $css;
    }

    /**
     * Método para aplicar estilo na tag <table> da mensagem de e-mail
     * 
     * @access public
     * @param string $css Regras CSS
     * @return \MultiAnexos
     *
     * */
    public function setCssTable($css)
    {
        $this->_table = $css;

        return $this;
    }

    /**
     * Método para devolver a estilização da tag <table>
     * 
     * @access private
     * @return string Regras CSS
     *
     * */
    private function getCssTable()
    {
        $css = 'padding:0;min-width:400px;';

        return (!empty($this->_table)) ? $css . $this->_table : $css;
    }

    /**
     * Método para aplicar estilo na tag <tr> da mensagem de e-mail
     * 
     * @access public
     * @param string $css Regras CSS
     * @return \MultiAnexos
     *
     * */
    public function setCssTableTr($css)
    {
        $this->_table_tr = $css;

        return $this;
    }

    /**
     * Método para devolver a estilização da tag <tr>
     * 
     * @access private
     * @return string Regras CSS
     *
     * */
    private function getCssTableTr()
    {
        $css = 'font:normal 14px arial,helvetica,sans-serif;';

        return (!empty($this->_table_tr)) ? $css . $this->_table_tr : $css;
    }

    /**
     * Método para aplicar estilo na tag <th> da mensagem de e-mail
     * 
     * @access public
     * @param string $css Regras CSS
     * @return \MultiAnexos
     *
     * */
    public function setCssTableTh($css)
    {
        $this->_table_th = $css;

        return $this;
    }

    /**
     * Método para devolver a estilização da tag <th>
     * 
     * @access private
     * @return string Regras CSS
     *
     * */
    private function getCssTableTh()
    {
        $css = 'padding:6px;';

        return (!empty($this->_table_th)) ? $css . $this->_table_th : $css;
    }

    /**
     * Método para aplicar estilo na tag <td> da mensagem de e-mail
     * 
     * @access public
     * @param string $css Regras CSS
     * @return \MultiAnexos
     *
     * */
    public function setCssTableTd($css)
    {
        $this->_table_td = $css;

        return $this;
    }

    /**
     * Método para devolver a estilização da tag <td>
     * 
     * @access private
     * @return string Regras CSS
     *
     * */
    private function getCssTableTd()
    {
        $css = 'padding:20px;';

        return (!empty($this->_table_td)) ? $css . $this->_table_td : $css;
    }

    /**
     * Método para formatar o conteúdo da mensagem
     * 
     * @access public
     * @param string $body Corpo da mensagem
     * @return string void
     *
     * */
    public function setHTML($body = null)
    {
        $_body = (!is_null($body)) ? $body : self::getPOST();
        self::$body =
        '<!DOCTYPE html>
        <html>
        <head><meta charset="UTF-8" /></head>
        <body style="' . $this->getCssBody() . '">
        <table border="0" cellspacing="0" cellpadding="0" style="' . $this->getCssTable() . '">
        <tr style="' . $this->getCssTableTr() . '"><th style="' . $this->getCssTableTh() . '">' . $this->getTitle() . '</th></tr>    
        <tr style="' . $this->getCssTableTr() . '"><td style="' . $this->getCssTableTd() . '">' . $_body . '</td></tr>
        </table>
        </body>
        </html>
        ';
    }

    /**
     * Método para tratar a mensagem do corpo do e-mail
     * 
     * @access private static
     * @return string void
     *
     * */
    private function getHTML()
    {
        if (!isset(self::$body))
            self::setHTML();

        return self::cleanHTML();
    }

    /**
     * Método para exibir a mensagem enviada
     * 
     * @access public static
     * @return string void
     *
     * */
    public static function showHTML()
    {
        if ($_POST)
            echo self::$body;
    }

    /**
     * Método para limpar os espaços tabulados na mensagem 
     * 
     * @access private
     * @return string Retorna o BODY da mensagem
     *
     * */
    private function cleanHTML()
    {
        return str_replace('\t', null, self::$body);
    }

    /**
     * Método para preparar o cabeçalho da mensagem
     * 
     * @access private
     * @param string $multipart Tipo de conteúdo, se possui arquivo(s) anexo(s) o valor é TRUE
     * @return void
     *
     * */
    private function setHeader($multipart)
    {
        self::$head
        = ('MIME-Version: 1.0' . PHP_EOL)
        . ('From: ' . self::getMail("from") . PHP_EOL)
        . ('X-Mailer: MultiAnexos - PHP/' . phpversion() . ' ' . PHP_EOL)
        . ((!empty($this->to[0][0])) ? 'To: ' . self::getMail("to") . PHP_EOL : NULL)
        . ((!empty($this->replyto[0][0])) ? 'Reply-To: ' . self::getMail("replyto") . PHP_EOL : NULL)
        . ((!empty($this->cc[0][0])) ? 'Cc: ' . self::getMail("cc") . PHP_EOL : NULL)
        . ((!empty($this->bcc[0][0])) ? 'Bcc: ' . self::getMail("bcc") . PHP_EOL : NULL)
        . ('Content-type: ' . (($multipart) ? 'multipart/mixed; boundary="' . $this->boundary . '"' . PHP_EOL : 'text/html; charset="ISO-8859-1"') . PHP_EOL);
    }

    /**
     * Método para recuperar o cabeçalho da mensagem
     * 
     * @access private
     * @return string Retorna o HEADER da mensagem
     *
     * */
    private function getHeader($has_file)
    {
        self::setHeader($has_file);

        return self::$head;
    }

    /**
     * Método para submeter a mensagem
     * 
     * @access public
     * @return boolean Retorna o status do envio
     *
     * */
    public function send()
    {
        // Verifico se o formulário submetido possui algum arquivo anexo
        $file = (isset($_FILES['multianexo']) && in_array('0', $_FILES['multianexo']['error'])) ? $_FILES['multianexo'] : FALSE;

        // Cabeçalho da mensagem
        $head = self::getHeader((bool) $file);

        // Assunto da mensagem
        $subj = self::getSubject();

        // Corpo da mensagem
        $body = self::getHTML();

        // Executo a condição seguinte, se identificar um ou mais anexos junto a mensagem
        if ($file) {

            // Número de arquivos anexos a mensagem
            $count = count($file['name']);

            // Removendo da matriz os anexos corrompidos
            for ($x = 0; $x < $count; $x++):
                if ($file['error'][$x] <> UPLOAD_ERR_OK) {
                    unset($file['name'][$x]);
                    unset($file['size'][$x]);
                    unset($file['type'][$x]);
                    unset($file['tmp_name'][$x]);
                    unset($file['error'][$x]);
                } else {
                    $attach[] = array(
                        $file['name'][$x],
                        $file['size'][$x],
                        $file['type'][$x],
                        $file['tmp_name'][$x]
                    );
                }
            endfor;

            // Corpo HTML da mensagem
            $html = stripslashes($body);

            // Criando os cabeçalhos MIME utilizados para separar as partes da mensagem 
            $body = '--' . $this->boundary . PHP_EOL;
            $body.= 'Content-Type: text/html; charset="ISO-8859-1"' . PHP_EOL;
            $body.= 'Content-Transfer-Encoding: 8bit' . PHP_EOL . PHP_EOL;
            $body.= $html . PHP_EOL;
            $body.= '--' . $this->boundary . PHP_EOL;

            // Número de anexos
            $count = count($attach);

            // Incorporando os anexos
            for ($i = 0; $i < $count; $i++):
                if (is_uploaded_file($attach[$i][3])) {
                    $_name = $attach[$i][0];
                    $_size = $attach[$i][1];
                    $_type = $attach[$i][2];
                    $_temp = $attach[$i][3];

                    if ((strlen($_name) > 1) && ($_size > 0)) {
                        $fopen = fopen($_temp, "rb");
                        $fread = fread($fopen, filesize($_temp));
                        $close = fclose($fopen);
                        $chunk = chunk_split(base64_encode($fread));

                        $body.= 'Content-Disposition: attachment; filename="' . $_name . '"' . PHP_EOL;
                        $body.= 'Content-Type: ' . $_type . '; name="' . $_name . '"' . PHP_EOL;
                        $body.= 'Content-Transfer-Encoding: base64' . PHP_EOL . PHP_EOL;
                        $body.= $chunk . PHP_EOL;
                        $body.= '--' . $this->boundary;
                        $body.= ($count == $i + 1) ? '--' . PHP_EOL . PHP_EOL : PHP_EOL;
                    }
                }
            endfor;
        }

        // Encaminhando o e-mail e armazenando a mensagem com o status do envio
        $status = mail(null, $subj, $body, $head, ((bool) $this->returnPath ? '-f' . self::getReturnPath() : null)) ? true : false;

        // Definindo uma constante com o status do envio
        define('SEND_RETURN', ($status == true) ? true : false);

        return $status;
    }

}

?>