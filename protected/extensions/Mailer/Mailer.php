<?php
require dirname(__FILE__).'/lib/classes/Swift.php';
Yii::registerAutoloader(array('Swift','autoload'));
require dirname(__FILE__).'/lib/swift_init.php';

class Mailer extends CApplicationComponent {
	public $transportType = 'smtp'; // smtp, sendmail, mail

	// smtp specific configuration
	public $username = 'dotplant.mailer@devgroup.ru';
	public $password = 'TumYlDIP9LiOpb';
	public $host = 'smtp.gmail.com';
	public $port = 465;
	public $security = 'ssl';

	public $mailFrom = 'dotplant.mailer@devgroup.ru;DotPlant CMS Mailer';
	public $signatureText = 'This mail sent automatically by DotPlant CMS.';
	public $signatureHTML = 'This mail sent automatically by <a href="http://dotplant.ru">DotPlant CMS</a>.';

	public $sendmailPath = '/usr/sbin/sendmail -bs';

	private $_transport = null;
	private $_mailer = null;

	public function init() {

		$configurableValues = array(
			'transportType' => 'Transport type',
			'username' => 'Username',
			'password' => 'Password',
			'host' => 'Host',
			'port' => 'Port',
			'mailFrom' => 'Mail from',
			'signatureText' => 'Signature text',
			'signatureHTML' => 'Signature HTML',
			'sendmailPath' => 'Sendmail path',
			);
		foreach ($configurableValues as $key => $configKey) {
			$this->$key = Config::model()->getConfigValue('E-Mail', $configKey)->value;
		}
		$mailFromParts = explode(";", $this->mailFrom);
		if (!isset($mailFromParts[1])) {
			$this->mailFrom = (array) $this->mailFrom;
		}
		$this->mailFrom = array($mailFromParts[0]=>$mailFromParts[1]);
		$this->security = Config::model()->getConfigValue('E-Mail', 'security')->value;
		if ($this->security == "" || $this->security == 0 || $this->security == 'null') {
			$this->security = null;
		}
		$this->port = intval($this->port);


		switch ($this->transportType) {
			case 'smtp':
				$this->_transport = Swift_SmtpTransport::newInstance($this->host, $this->port, $this->security)
				  ->setUsername($this->username)
				  ->setPassword($this->password);
				break;

			case 'sendmail':
				$this->_transport = Swift_SendmailTransport::newInstance($this->sendmailPath);
				break;

			case 'mail':
			default:
				$this->_transport = Swift_MailTransport::newInstance();
				break;

		}
		$this->_mailer = Swift_Mailer::newInstance($this->_transport);
		Swift_Preferences::getInstance()->setCharset('utf-8');

	}


	public function send($to, $subject, $body) {
		$to=explode(",", $to);
		$message = Swift_Message::newInstance($subject)
		  ->setFrom($this->mailFrom)
		  ->setTo((array)$to)
		  ->setBody(strip_tags($body)."\n\n".$this->signatureText)
		  ->addPart($body."<br><br>".$this->signatureHTML, 'text/html');

		// Send the message
		return $this->_mailer->send($message);
	}


}