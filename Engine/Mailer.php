<?php
namespace APP\Engine;
use APP\Library\Mailer\Smtp;
use APP\Library\Mailer\Basic as BasicMailer;

class Mailer
{
	protected $_aMailConfigs = array();
	protected $_oMailer = null;
	protected $_bIsEnableMailer = true;
	protected $_sTemplatePath = "";
	protected $_aParams = array();
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
	public function template($sTemplateFile)
	{
		$this->_sTemplatePath = $sTemplateFile;
		return $this;
	}
	public function params($aParams = array())
	{
		$this->_aParams = $aParams;
		return $this;
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
		if(!empty($this->_sTemplatePath))
		{
			$aParams = $this->_oMailer->getParams();
			$aParams = array_merge($aParams,$this->_aParams);
			$sContent = $this->getContentFromTemplate($this->_sTemplatePath, $this->_aParams);
			$this->_oMailer->message($sContent);
		}
		return $this->_oMailer->send();
	}
	public function test()
	{
		$this->_oMailer->test();die();
	}
	public function getContentFromTemplate($sTemplateFile, $aParams = array())
	{
		if(strpos($sTemplateFile, APP_THEME_PATH) === false)
		{
			$sTemplateFile = APP_THEME_PATH . "Mail". APP_DS . $sTemplateFile ;
		}
		$sTemplateFile =\APP\Engine\Template::getFileName($sTemplateFile);
		if(!file_exists($sTemplateFile))
		{
			throw new \Exception("Template mail is not defined");
		}
		$app = \APP\Engine\Application::getInstance();
		$aParams['sMailSignature'] = $app->getSetting('mail.signature_footer_description','');
		$sContent =  $app->template->assign($aParams)->render($sTemplateFile, true);
		return $sContent;
	}
}