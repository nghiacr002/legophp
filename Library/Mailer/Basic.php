<?php

namespace APP\Library\Mailer;

class Basic
{
	protected $_mTo = array();
	protected $_mFrom = array();
	protected $_mCC = array();
	protected $_mBCC = array();
	protected $_sMessage = "";
	protected $_aAttachements = array();
	protected $_mLastError = "";
	protected $_mConfig = array();
	protected $_sSubject = "";
	protected $_mReplyTo = array();
	public function __construct()
	{

	}
	public function init($configs = array())
	{
		$this->_mConfig = $configs;
		return $this;
	}
	public function replyTo($sEmail, $sName = "")
	{
		$this->_mReplyTo = array(
			'email' => $sEmail,
			'name' => $sName
		);
		return $this;
	}
	public function subject($sSubject)
	{
		$this->_sSubject = $sSubject;
		return $this;
	}
	public function to($sEmail, $sName = "")
	{
		$this->_mTo[$sEmail] = $sName;
		return $this;
	}
	public function from($sEmail, $sName = "")
	{
		$this->_mFrom = array(
			'email' => $sEmail,
			'name' => $sName
		);
		return $this;
	}
	public function send()
	{
		if($this->validate())
		{
			$aHeaders = array();
			$aHeaders[] = 'MIME-Version: 1.0';
			$aHeaders[] = 'From: '.$this->_mFrom['name'].' <'.$this->_mFrom['email'].'>';
			$aHeaders[] = 'X-Mailer: PHP/' . phpversion();
			if(count($this->_mReplyTo))
			{
				$aHeaders[] = 'Reply-To: '.$this->_mReplyTo['name'].' <'.$this->_mReplyTo['email'].'>';
			}
			$aHeaders[] = 'Content-type: text/html; charset=iso-8859-1';
			if(count($this->_aAttachements))
			{
				$uid = md5(uniqid(time()));
				$aHeaders []= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n";
				$aHeaders []= "This is a multi-part message in MIME format.";
				$aHeaders []= "--".$uid."\r\n";
				$aHeaders []= "Content-Transfer-Encoding: 7bit\r\n";
				foreach ($this->_aAttachements as $sFilePath => $sName)
				{
					$iFileSize = filesize($sFilePath);
					$handle = fopen($file, "r");
					$sContent = fread($handle, $iFileSize);
					fclose($handle);
					$sContent = chunk_split(base64_encode($sContent));
					$aHeaders []= "--".$uid."\r\n";
					$aHeaders []= "Content-Type: application/octet-stream; name=\"".$sName."\"\r\n"; // use different content types here
					$aHeaders []= "Content-Transfer-Encoding: base64\r\n";
					$aHeaders []= "Content-Disposition: attachment; filename=\"".$sName."\"\r\n";
					$aHeaders []= $sContent."\r\n";
				}
				$aHeaders []= "--".$uid."--";
			}
			if(count($this->_mCC))
			{
				$aTmp = array();
				foreach($this->_mCC as $sEmail => $sName)
				{
					$aTmp[] = $sName.'<'.$sEmail.'>';
				}
				$aHeaders[] = 'Cc: '. implode(',',$aTmp);
			}
			if(count($this->_mBCC))
			{
				$aTmp = array();
				foreach($this->_mBCC as $sEmail => $sName)
				{
					$aTmp[] = $sName.'<'.$sEmail.'>';
				}
				$aHeaders[] = 'Bcc: '. implode(',',$aTmp);
			}
			$sParam = "";
			foreach($this->_mTo as $sEmail => $sRecevierName)
			{
				@mail($sEmail, $this->_sSubject, $this->_sMessage, implode("\r\n",$aHeaders), $sParam);
			}
		}
		$sError =  $this->getLastError();
		$this->reset();
		return $sError;
	}
	public function validate()
	{
		$mError = array();
		if(!is_array($this->_mTo) || !count($this->_mTo))
		{
			$mError[] = "Need to have at least 1 receiver";
		}
		if(!is_array($this->_mFrom) || !count($this->_mFrom))
		{
			$mError[] = "Need to have at least 1 sender";
		}
		if(empty($this->_sMessage))
		{
			$mError[] = "Mail Message should not be empty";
		}
		if(empty($this->_sSubject))
		{
			$mError[] = "Mail Subject should not be empty";
		}
		if(count($mError))
		{
			$this->_mLastError = $mError;
			return false;
		}
		return true;
	}
	public function cc($sEmail, $sName = "")
	{
		$this->_mCC[$sEmail] = $sName;
		return $this;
	}
	public function bcc($sEmails = null, $sName = "")
	{
		$this->_mBCC[$sEmail] = $sName;
		return $this;
	}
	public function attachment($sFilePath, $sFileName)
	{
		$this->_aAttachements[$sFilePath] = $sFileName;
		return $this;
	}
	public function message($sMessage)
	{
		$this->_sMessage = $sMessage;
		return $this;
	}
	public function test()
	{
		if(function_exists('mail')){
			return true;
		}
		return false;
	}
	public function getLastError()
	{
		return $this->_mLastError;
	}
	public function getParams()
	{
		return array(
			'message' => $this->_sMessage,
			'subject' => $this->_sSubject,
			'mTo' => $this->_mTo,
			'attachments' => $this->_aAttachements
		);
	}
	public function reset()
	{
		$this->_aAttachements = array();
		$this->_mBCC = array();
		$this->_mCC = array();
		$this->_mTo = array();
		$this->_mFrom = array();
		$this->_mReplyTo = array();
		$this->_mLastError = "";
		return $this;
	}

}
