<?php

/**
 * Multi-Anexos | Multiple File Attachments 
 * Envio de e-mail com múltiplos arquivos anexados
 * 
 * @author Giovanni Ramos
 * @twitter @giovanni_ramos
 * @version 2.5
 * @since 2009-09-23 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
**/


class MultiAnexos {

	public  static $head, $body;
	private $boundary = array();


	/**
	 * O objeto MultiAnexos ao ser instanciado, pode opcionalmente atribuir um assunto à mensagem
	 * 
	 * @access public
	 * @param string $subject Assunto da mensagem
	 * 
	 * */
	public function __construct($subject = null) {
		self::setHTML();
		self::setReturnPath();
		$this->subject = $subject;
		$this->boundary = '==Multipart_Boundary_'.md5(uniqid(time()));
	}


	/**
	 * Método para validar email
	 * 
	 * @access public
	 * @param string $mail E-mail a ser testado
	 * @return boolean Retorna true, se o e-mail for válido
	 *
	 * */
	public function is_mail($mail = null) {
		return (filter_var(trim($mail), FILTER_VALIDATE_EMAIL) ? true : false);
	}


	/**
	 * Método para atribuir um assunto à mensagem
	 * 
	 * @access public
	 * @param string $subject Assunto da mensagem
	 * @return void
	 *
	 * */
	public function setSubject($subject = null) {
		$this->subject = $subject;
	}


	/**
	 * Método para recuperar o assunto da mensagem
	 * 
	 * @access private
	 * @return string Retorna o assunto da mensagem. Se nenhum for definido, retorna o Subject: "Sem assunto"
	 *
	 * */
	private function getSubject() {
		return (!is_null($this->subject)) ? $this->subject : "Sem assunto";
	}


	/**
	 * Método para incorporar um e-mail à mensagem
	 * 
	 * @access public
	 * @param string $type Tipo pré-definido de e-mail: From, To, Cc, Bcc ou Reply-To
	 * @param string $email Endereço de e-mail
	 * @param string $name Um nome ou apelido para o e-mail (opcional)
	 * @return array Retorna o email
	 *
	 * */
    public function setMail($type, $mail = null, $name = null) {
		if(isset($mail) && self::is_mail($mail)):
    		$type = strtolower($type);
    		$type = str_replace('-', null, $type);
    		$type = str_replace('_', null, $type);

			if(isset($type) && ereg('^[[:lower:]]*$', $type)):
		        if(!is_array($this->$type))
		            $this->$type = array();
		        array_push($this->$type, array($mail, $name));
		        return $this->$type;
 			endif;
     	endif;
    }


	/**
	 * Método para retornar a lista de e-mails atribuída à mensagem
	 * 
	 * @access public
	 * @param string $type Tipo de e-mail: From, To, Cc, Bcc ou Reply-To
	 * @return string Retorna a lista de acordo com o tipo
	 *
	 * */
	 public function getMail($type) {
		if(isset($type)):
			if(!$this->$type) return false;
			if(!is_array($list)) $list = array(); 

			foreach($this->$type as $k => $v)
				if(is_array($v))
					$list[] = (is_null($v[1])) ? $v[0] : $v[1].' <'.$v[0].'>';
			return implode(', ', $list);
		endif;
	}


	/**
	 * Método para incorporar um e-mail de retorno em caso de falha no envio da mensagem
	 * IMPORTANTE: Informar um Return-Path, evita bloqueios anti-spam de servidores como Gmail ou Hotmail
	 * 
	 * @access public
	 * @param string $setReturnPath Por padrão um e-mail de retorno será atribuído a mensagem
	 * @return void
	 *
	 * */
	public function setReturnPath($setReturnPath = true) {
		$this->setReturnPath = $setReturnPath;
	}


 	/**
	 * Método para recuperar o e-mail de retorno da mensagem
	 * 
	 * @access private
	 * @return string Retorna o e-mail de retorno
	 *
	 * */
	private function getReturnPath() {
		return (!is_bool($this->setReturnPath)) ? $this->setReturnPath : ( (bool)$this->setReturnPath ? $this->from[0][1] : null );
	}


 	/**
	 * Método para capturar variáveis enviadas via post, pelo formulário 
	 * 
	 * @access private
	 * @return string Retorna a lista de variáveis
	 *
	 * */
	public function getPOST() { 
		$list = null;
		foreach($_POST as $k => $v)
			$list.= '<b>'.ucfirst($k).':</b> '.$v.'<br />';
		return $list;
	}


 	/**
	 * Método para exibir as variáveis submetidas pelo formulário
	 * 
	 * @access static
	 * @return string void
	 *
	 * */
	static function showPOST() {
		if($_POST)
			echo '<pre>',htmlspecialchars(print_r($_POST, true)),'</pre>';
	}


 	/**
	 * Método para formatar o conteúdo da mensagem
	 * 
	 * @access public
	 * @return string void
	 *
	 * */
	public function setHTML($body = null) {
		self::$body = (!is_null($body)) ? $body :
		'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR" lang="pt-BR">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
			<style>
			body 		{background:#FFFFFF;}
			a, a:link 	{color:#5b6105; text-decoration:none;}
			table 		{margin:0; padding:0; border:1px solid #8F8E96; width:500px;}
			table tr 	{background:#F6F6F6; }
			table td 	{color:#000000; font: normal 11px arial, helvetica, sans-serif; border:dashed 1px #DDD; padding:30px;}
			</style>
			</head>
			<body>
			<br />
			<table border="0" cellspacing="10" cellpadding="0">
			<tr>
				<td>
				'.self::getPOST().'
				</td>
			</tr>
			</table>
			<br />
			</body>
			</html>
		';
	}


 	/**
	 * Método para limpar os espaços tabulados na mensagem 
	 * 
	 * @access public
	 * @return string void
	 *
	 * */
	public function getHTML() {
		return str_replace("\t",null,self::$body);
	}


 	/**
	 * Método para exibir a mensagem enviada no corpo do e-mail
	 * 
	 * @access static
	 * @return string void
	 *
	 * */
	static function showHTML() {
		if($_POST)
			echo self::$body;
	}


 	/**
	 * Método para preparar o cabeçalho da mensagem
	 * 
	 * @access public
	 * @return string void
	 *
	 * */
	public function setHeader($multipart = false) {
		self::$head 
		= ('MIME-Version: 1.0'.PHP_EOL)
		. ('From: '.self::getMail("from").PHP_EOL)
		. ('X-Mailer: MultiAnexos v.2.0 PHP/'.phpversion().' '.PHP_EOL)
		. ((!empty($this->to[0][0])) ? 'To: '.self::getMail("to").PHP_EOL : NULL)
		. ((!empty($this->replyto[0][0])) ? 'Reply-To: '.self::getMail("replyto").PHP_EOL : NULL)
		. ((!empty($this->cc[0][0])) ? 'Cc: '.self::getMail("cc").PHP_EOL : NULL)
		. ((!empty($this->bcc[0][0])) ? 'Bcc: '.self::getMail("bcc").PHP_EOL : NULL)
		. ('Content-type: '.(($multipart) ? 'multipart/mixed; boundary="'.$this->boundary.'"'.PHP_EOL:'text/html; charset="ISO-8859-1"').PHP_EOL);
	}


 	/**
	 * Método para recuperar o cabeçalho da mensagem
	 * 
	 * @access public
	 * @return string Retorna o HEADER da mensagem
	 *
	 * */
	public function getHeader() {
		return self::$head;
	}


 	/**
	 * Método para submeter a mensagem
	 * 
	 * @access public
	 * @return boolean Retorna o status do envio
	 *
	 * */
	public function send() {

		// Verifico se o formulário submetido, possui algum arquivo anexo
		$file = (isset($_FILES['arquivo']) && in_array('0',$_FILES['arquivo']['error'])) ? $_FILES['arquivo'] : FALSE;

		$subj = self::getSubject();

		$body = self::getHTML();

		self::setHeader((bool) $file);
		$head = self::getHeader();

		// Executo a condição seguinte, se identificar um ou mais anexos junto a mensagem
		if($file){

			// Removendo da matriz os anexos falsos
			for ($x=0; $x<count($_FILES['arquivo']['name']); $x++):
				if($file['error'][$x] <> UPLOAD_ERR_OK){
					unset($file['name'][$x]);
					unset($file['size'][$x]);
					unset($file['type'][$x]);
					unset($file['tmp_name'][$x]);
					unset($file['error'][$x]);
				}else{
					$attach[] = array(
						$file['name'][$x], 
						$file['size'][$x], 
						$file['type'][$x], 
						$file['tmp_name'][$x]
					);
				}
			endfor;


			$html = stripslashes($body);

			// Criando os cabeçalhos MIME utilizados para separar as partes da mensagem 
			$body = '--'.$this->boundary.PHP_EOL;
			$body.= 'Content-Type: text/html; charset="ISO-8859-1"'.PHP_EOL;
			$body.= 'Content-Transfer-Encoding: 8bit'.PHP_EOL.PHP_EOL;
			$body.= $html.PHP_EOL;
			$body.= '--'.$this->boundary.PHP_EOL;

			for ($i=0; $i<sizeof($attach); $i++):
				if(is_uploaded_file($attach[$i][3])) {
					$Name = $attach[$i][0];
					$Size = $attach[$i][1];
					$Type = $attach[$i][2];
					$Temp = $attach[$i][3];

					if((strlen($Name)>1) && ($Size>0)) {
						$fopen = fopen($Temp,"rb");
						$fread = fread($fopen, filesize($Temp));
						$cript = base64_encode($fread);
						$close = fclose($fopen);
						$chunk = chunk_split($cript);
	
						$body.= 'Content-Disposition: attachment; filename="'.$Name.'"'.PHP_EOL;
						$body.= 'Content-Type: '.$Type.'; name="'.$Name.'"'.PHP_EOL;
						$body.= 'Content-Transfer-Encoding: base64'.PHP_EOL.PHP_EOL;
						$body.= $chunk.PHP_EOL;
						$body.= '--'.$this->boundary;
						$body.= (sizeof($attach) == $i+1) ? '--'.PHP_EOL.PHP_EOL : PHP_EOL;
					}
				}
			endfor;

		}

		// Enviando a mensagem e definindo uma mensagem de status do envio em SEND_RETURN
		$status = mail(null, $subj, $body, $head, ((bool) $this->setReturnPath ? '-f'.self::getReturnPath() : null)) ? true : false;

		$status_message = ($status) ?
		'<span class="mail_ok">Sua mensagem foi enviada com sucesso.</span>' : 
		'<span class="mail_no">Sua mensagem n&atilde;o p&ocirc;de ser enviada.</span><br /><br />Por favor tente novamente mais tarde.' ;

		define('SEND_RETURN', $status_message);
		
		return $status;
	}

}


?>