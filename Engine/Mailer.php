<?php
namespace APP\Engine;
use APP\Library\Mailer\Smtp;
use APP\Library\Mailer\Basic as BasicMailer;

class Mailer
{
	protected $_aMailConfigs = array();
	protected $_oMailer = null;
	protected $_bIsEnableMailer = true;
	public function __construct()
	{
		$app = \APP\Engine\Application::getInstance();
		$sMethod = $app->getSetting('mail.send_mail_method','mail');
		$this->_bIsEnableMailer = $bIsEnableMailer = $app->getSetting('mail.enable_mail_system');
		if($bIsEnableMailer)
		{
			$this->_aMailConfigs = array(
				'host' => $app->getSetting('mail.smtp_host'),
				'user' => $app->getSetting('mail.smtp_user'),
				'pass' => $app->getSetting('mail.smtp_password'),
				'port' => $app->getSetting('mail.smtp_port'),
				'authenticate' => $app->getSetting('mail.smtp_authentication'),
			);
			$oMailer = null ;
			$sMethod = strtolower($sMethod);
			switch($sMethod)
			{
				case 'smtp':
					$oMailer = new Smtp();
					break;
				case 'mail':
					$oMailer = new BasicMailer();
					break;
				default:
					break;
			}
			if($oMailer)
			{
				$oMailer->init($this->_aMailConfigs);
				$this->_oMailer = $oMailer;
				$oMailer->from($app->getSetting('mail.default_sent_out_email'),$app->getSetting('mail.from_name'));
			}
		}
	}
	public function isEnabled()
	{
		return $this->_bIsEnableMailer;
	}
	public function instance()
	{
		return $this->_oMailer;
	}
	public function to($sEmail, $sName = "")
	{
		$this->_oMailer->to($sEmail,$sName);
		return $this;
	}
	public function from($sEmail, $sName = "")
	{
		$this->_oMailer->from($sEmail,$sName);
		return $this;
	}
	public function subject($sSubject)
	{
		$this->_oMailer->subject($sSubject);
		return $this;
	}
	public function message($sMessage)
	{
		$this->_oMailer->message($sMessage);
		return $this;
	}
	public function addRecevier($sEmail, $sName, $bIsBlind = false)
	{
		if($bIsBlind)
		{
			$this->_oMailer->bcc($sEmail,$sName);
		}
		else
		{
			$this->_oMailer->cc($sEmail,$sName);
		}
		return $this;
	}
	public function attachement($sFilePath, $sFileName)
	{
		$this->_oMailer->attachment($sFilePath, $sFileName);
		return $this;
	}
	public function send()
	{
		return $this->_oMailer->send();
	}
	public function test()
	{
		$this->_oMailer->test();die();
	}
}