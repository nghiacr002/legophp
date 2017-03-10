<?php

namespace APP\Library\Mailer;
require_once APP_PATH_LIB . 'vendor' . APP_DS . 'phpmailer'. APP_DS . 'phpmailer' . APP_DS . 'PHPMailerAutoload.php';
class Smtp extends Basic
{
	private $_oPHPMailer = null;
	public function init($aConfigs = array())
	{
		parent::init($aConfigs);
		$this->_oPHPMailer = new \PHPMailer();
		$this->_oPHPMailer->isSMTP();
		$this->_oPHPMailer->Host = $aConfigs['host'];  // Specify main and backup SMTP servers
		$this->_oPHPMailer->SMTPAuth = ($aConfigs['authenticate'] == 1) ? true: false;                               // Enable SMTP authentication
		$this->_oPHPMailer->Username = $aConfigs['user'];                 // SMTP username
		$this->_oPHPMailer->Password = $aConfigs['pass'];                           // SMTP password
		$this->_oPHPMailer->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$this->_oPHPMailer->Port = $aConfigs['port'];
		$this->_oPHPMailer->isHTML(true);
	}
	public function send()
	{
		if($this->validate())
		{
			$this->_oPHPMailer->setFrom($this->_mFrom['email'], $this->_mFrom['name']);
			if(count($this->_mReplyTo))
			{
				$this->_oPHPMailer->addReplyTo($this->_mReplyTo['email'], $this->_mReplyTo['name']);
			}
			if(count($this->_mCC))
			{
				foreach($this->_mCC as $sEmail => $sName)
				{
					$this->_oPHPMailer->addCC($sEmail,$sName);
				}
			}
			if(count($this->_mBCC))
			{
				foreach($this->_mBCC as $sEmail => $sName)
				{
					$this->_oPHPMailer->addBCC($sEmail,$sName);
				}
			}
			if(count($this->_aAttachements))
			{
				foreach($this->_aAttachements as $sFilePath => $sFileName)
				{
					$this->_oPHPMailer->addAttachment($sFilePath,$sFileName);
				}
			}
			foreach($this->_mTo as $sEmail => $sRecevierName)
			{
				$this->_oPHPMailer->addAddress($sEmail,$sRecevierName);
			}
			$this->_oPHPMailer->Body = $this->_sMessage;
			$this->_oPHPMailer->Subject = $this->_sSubject;
			if(!$this->_oPHPMailer->send())
			{
				$this->_mLastError = $this->_oPHPMailer->ErrorInfo;
			}
		}
		$sError =  $this->getLastError();
		$this->reset();
		return $sError;
	}
	public function test()
	{
		//not implement now
		return true;
	}
}