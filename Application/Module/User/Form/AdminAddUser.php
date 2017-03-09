<?php

namespace APP\Application\Module\User\Form;

use APP\Engine\HTML\Form as HtmlForm;
use APP\Engine\Validator;
use APP\Engine\HTML\Input;
use APP\Engine\HTML\Select;
use APP\Application\Module\User\Model\Group;
use APP\Engine\HTML\Checkbox;
use APP\Engine\HTML\Textarea;
use APP\Engine\File as FileManager;
use APP\Engine\HTML\File as File;

class AdminAddUser extends HtmlForm
{

    public function __construct()
    {
        parent::__construct();
        $this->_aElements = array();
        $aPostData = $this->_aData;

        $oInput = new Input();
        $oInput->setName("full_name");
        //$oInput->setValue($this->request()->get('full_name'));
        $oInput->required(true);
        $oInput->setErrorMessage($this->app->language->translate('core.fullname_cannot_be_empty'));
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("user_name");
        //$oInput->setValue($this->request()->get('user_name'));
        $oInput->required(true);
        $oInput->setErrorMessage($this->app->language->translate('core.username_cannot_be_empty'));
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("user_title");

        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("email");
        //$oInput->setValue($this->request()->get('email'));
        $oInput->required(true);
        $mValidator = new Validator($aPostData);
        $mValidator->rule('email', 'email');
        $oInput->validator($mValidator);
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("password");
        //$oInput->setValue($this->request()->get('password'));
        if ($this->request()->get('id') <= 0)
        {
            $oInput->required(true);
        }
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("birthday");
        //$oInput->setValue($this->request()->get('birthday'));
        $this->addElement($oInput);

        $oInput = new Input();
        $oInput->setName("address");
        //$oInput->setValue($this->request()->get('birthday'));
        $this->addElement($oInput);

        $oInput = new Select();
        $oInput->setName("main_group_id");
        //$oInput->setValue($this->request()->get('main_group_id'));
        $aGroups = (new Group())->getAll();
        foreach ($aGroups as $ikey => $oGroup)
        {
            $oInput->setOption($oGroup->user_group_id, $oGroup->group_name);
        }
        $this->addElement($oInput);

        $oInput = new Checkbox();
        $oInput->setName('status');
        $oInput->setValue((int) $this->request()->get('status'));
        $this->addElement($oInput);

        $oInput = new Textarea();
        $oInput->setName('user_text');
        $oInput->setValue($this->request()->get('user_text'));
        $this->addElement($oInput);

        $oInput = new File();
        $oInput->setName('user_image');
        $oInput->setFileType(array(
        		"png", "jpg", "gif", "jpeg"
        ));
        $this->addElement($oInput);
        if ($this->request()->isPost())
        {
        	if ($_FILES && $oInput->validateFileType())
        	{
        		$oFile = new FileManager();
        		$iUserId = $this->request()->get('id');
        		$oViewer = $this->app->auth->getViewer();
        		if(!$iUserId && $oViewer)
        		{
        			$iUserId = $oViewer->user_id;
        		}
        		$sUploadedFile = $oFile->upload($oInput->getName(), APP_UPLOAD_PATH . "user-".$iUserId . APP_DS);
        		if ($sUploadedFile)
        		{
        			$oInput->setValue( $sUploadedFile);
        		}
        		else
        		{
        			$aFileInfo = $oFile->getFileInfo();
        			$sMessage = $oFile->getErrorFile($aFileInfo['error']);
        			if ($sMessage)
        			{
        				$oInput->setErrorMessage($sMessage);//->setMessage($sMessage);
        			}
        		}
        	}
        	else
        	{
        		$oInput->setValue("");
        		$oInput->setMessage("");
        		$oInput->forceValid(true);
        		$this->removeElement('user_image');
        	}
        }


    }

}
