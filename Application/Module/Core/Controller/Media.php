<?php

namespace APP\Application\Module\Core;

use APP\Engine\Module\Controller;
use APP\Engine\File;
use APP\Application\Module\Core\Model\Media;
use APP\Engine\Utils;
use APP\Engine\Image;
use APP\Library\FileManager;

class MediaController extends Controller
{

    private $_aDefaultFolder = array(
    );
	private $_sCurrentUserPath = null ;
    public function __construct()
    {
        parent::__construct();
        $this->_aDefaultFolder = array(
            APP_UPLOAD_PATH . "Ebook",
            APP_UPLOAD_PATH . "Image",
            APP_UPLOAD_PATH . "Other",
            APP_UPLOAD_PATH . "Thumb",
        );
    }

    public function OriginalAction()
    {
        $sPath = $this->request()->get('path');

        if (!empty($sPath))
        {
            $sPath = urldecode($sPath);
            $sFullPath = APP_UPLOAD_PATH . $sPath;
            if (file_exists($sFullPath))
            {
                return (new File())->displayContent($sFullPath);
            }
        }
        exit;
    }

    public function ThumbAction()
    {
        $sSize = $this->request()->get('size', 'medium');

        $sPath = $this->request()->get('path');
        $oImage = new Image();
        $bNotFound = false;
        if (!empty($sPath))
        {
            $sFullPath = APP_UPLOAD_PATH . $sPath;

            $sBaseName = basename($sFullPath);
            if ($sSize == "origin")
            {
                $sPath = APP_UPLOAD_PATH . $sPath;
                if (file_exists($sPath))
                {
                    $oImage->displayContent($sPath);
                }
                else
                {
                	$bNotFound = true;
                }
            }
            else
            {
                $sSubPath = str_replace($sBaseName, "", $sPath);
                $sThumbPath = $this->getUserUploadPath() . APP_DS . "Thumb" . APP_DS;
				//d($sSubPath);die();
                if (file_exists($sFullPath))
                {
                    $sType = "scale";
                    $aParts = explode('-', $sSize);
                    if (count($aParts) == 2)
                    {
                        $sType = $aParts[1];
                        $sSize = $aParts[0];
                    }
                    $sThumbPath = $sThumbPath . $sSize . APP_DS;
                    if (!empty($sSubPath))
                    {
                        $sThumbPath = $sThumbPath . $sSubPath . APP_DS;
                    }
                    $sThumbImg = $sThumbPath . $sBaseName;
                    if (!file_exists($sThumbImg))
                    {
                        try
                        {
                            $iWidth = $oImage->getDefaultSize($sSize);
                            $iHeight = null;
                            if ($iWidth)
                            {
                                if ($sType == "square")
                                {
                                    $iHeight = $iWidth;
                                }
                            }
                            $oLayerImg = $oImage->initFromPath($sFullPath);
                            $oLayerImg->resize(null, $iWidth, $iHeight, true);
                            $oLayerImg->save($sThumbPath, $sBaseName, true, null, 100);
                        } catch (\Exception $ex)
                        {

                        }
                    }
                    if (file_exists($sThumbImg))
                    {
                        $oImage->displayContent($sThumbImg);
                    }
                    else
                    {
                    	$bNotFound = true;
                    }
                }
                else
                {
                	$bNotFound = true;
                }
            }
        }
        else
        {
        	$bNotFound = true;
        }
        if($bNotFound)
        {
        	$sPathDefault = $this->app->template->getBaseURL() . "Image/not_found.png";
        	$oImage->displayContent($sPathDefault);
        }
        exit;
        ;
    }

    public function UploadAction()
    {
        $oFile = new File();
        $aResult = array(
            'status' => 0,
            'message' => $this->language()->translate('core.no_uploaded_file'),
        );
        $bHasPerm = $this->auth()->acl()->hasPerm('core.can_upload_file');
        if (!$bHasPerm)
        {
            $aResult['message'] = $this->language()->translate('core.you_does_not_have_permission_to_access_this_area');
        }
        else
        {
            if (isset($_FILES['filemanager']))
            {
            	$sPath = $this->request()->get('pathUpload');
                if (empty($sPath))
                {
                	$sPath = $this->getUserUploadPath();
                }
                else
                {
                	$sPath = APP_UPLOAD_PATH . $sPath. APP_DS;
                }
                if ($this->IsUploadedPath($sPath))
                {
                    $sNewFileName = $oFile->upload('filemanager', $sPath);
                    $aFile = $oFile->getFileInfo();

                    if (($sNewFileName))
                    {

                        $aResult['status'] = 1;
                        $aResult['message'] = "";
                        $aResult['title'] = $aFile['name'];
                        $sFullPath = $sPath . APP_DS . $sNewFileName;
                        if (Utils::isImage($sFullPath))
                        {
                        	$oImage = new Image();
                            $aResult['thumb'] = $oImage->getThumbUrl($sFullPath, 'medium-square');
                            $aResult['original'] = $oImage->getThumbUrl($sFullPath, 'origin');
                        }
                        $aResult['absolute_path'] = str_replace(APP_UPLOAD_PATH, "", $sFullPath);
                        //$iUserId = 0;

                        /*if($oCurrentUser)
                        {
                        	$iUserId = $oCurrentUser->user_id;
                        }
                        $oNewMedia = (new Media())->getTable()->createRow();
                        $oNewMedia->media_title = $aFile['name'];
                        $oNewMedia->owner_id = $iUserId;
                        $oNewMedia->destination = $aResult['absolute_path'];
                        $oNewMedia->uploaded_time = APP_TIME;
                        $oNewMedia->file_type = isset($aFile['file_ext']) ? $aFile['file_ext'] : ($oFile->getExt($sNewFileName));
						$oNewMedia->meta_file = $aFile;
						try{
							$oNewMedia->save();
						}catch(\Exception $ex)
						{

						}*/

                    } else
                    {
                        $aResult['message'] = $oFile->getErrorFile($aFile['error']);
                    }
                }
            }
        }

        echo json_encode($aResult);
        exit;
    }

    public function DeleteAction()
    {
        $aFiles = isset($_POST['files']) ? $_POST['files'] : "";
        if (!empty($aFiles))
        {
            $aFiles = explode('|', $aFiles);
            if (!is_array($aFiles))
            {
                $aFiles = array($aFiles);
            }
            $oFileManager = new File();
            foreach ($aFiles as $ikey => $sFile)
            {
                try
                {
                    $sFullPath = APP_UPLOAD_PATH . $sFile;
                    if ($this->IsUploadedPath($sFullPath))
                    {
                        if (is_dir($sFullPath))
                        {
                            if (in_array($sFullPath, $this->_aDefaultFolder))
                            {
                                $oFileManager->recurse_remove($sFullPath);
                            }
                        } else
                        {
                            $oFileManager->removeFile($sFullPath);
                        }
                    }
                } catch (\Exception $ex)
                {

                }
            }
        }
        return true;
    }
	private function getUserUploadPath()
	{
		if(!$this->_sCurrentUserPath)
		{
			$this->_sCurrentUserPath = "";
			$sBaseFolder = "anonymous" . APP_DS;
			$oViewer =$this->auth()->getViewer();
            if($oViewer && $oViewer->user_id){
            	$sBaseFolder = "user-". $oViewer->user_id. APP_DS;
            }
            $sPath = APP_UPLOAD_PATH . $sBaseFolder ;
			$this->_sCurrentUserPath = $sPath;
		}
		return $this->_sCurrentUserPath;
	}
    private function IsUploadedPath($sPath)
    {
    	if(empty($sPath))
    	{
    		return false;
    	}
    	//check base path;
    	$sBasePathCheck = str_replace(APP_UPLOAD_PATH, "", $sPath);
    	if($sPath == $sBasePathCheck)
    	{
    		return false;
    	}
    	if($this->auth()->acl()->hasPerm('core.can_access_all_upload_folder'))
    	{
    		return true;
    	}
    	$sUserUploadPath = $this->getUserUploadPath();
        $sCheckReplacePath = str_replace($sUserUploadPath, '', $sPath);
        if($sPath == $sCheckReplacePath)
        {
			return false;
        }
        return true;
    }

    public function PreviewAction()
    {
        $sFile = $this->request()->get('file');
        $sHTML = "";
        if (!empty($sFile))
        {
            $sFullPathFile = APP_UPLOAD_PATH . $sFile;
            if (file_exists($sFullPathFile))
            {
                $aFileInfo = (new File())->getFileInfo($sFullPathFile);

                $oFileManager = new FileManager();
                $oImage = new Image();
                if (Utils::isImage($aFileInfo['full_path']))
                {
                    $aFileInfo['thumb'] = $oImage->getThumbUrl($aFileInfo['full_path'], 'medium-square');
                } else
                {
                    $aFileInfo['thumb'] = '';
                }

                $aFileInfo['absolute_path'] = str_replace(APP_UPLOAD_PATH, "", $aFileInfo['full_path']);
                $aFileInfo['absolute_path_2'] = str_replace(APP_UPLOAD_PATH, "", $aFileInfo['full_path']);
                $aFileInfo['absolute_path'] = str_replace($aFileInfo['title'], "", $aFileInfo['absolute_path']);
                if (empty($aFileInfo['absolute_path']))
                {
                    $aFileInfo['absolute_path'] = "..";
                }
                $this->view->aFileInfo = $aFileInfo;
                $sHTML = $this->getContent('Preview.tpl');
            }
        }
        echo $sHTML;
        exit;
    }

    public function BrowseAction()
    {
        $aFiles = array();
        $sMode = $this->request()->get('mode');
		$sTypeFile = $this->request()->get('type');
        if ($this->request()->isAjax() || $this->request()->get('ajax') == 1)
        {
            if ($this->request()->get('preview') == true)
            {
                $this->PreviewAction();
            }
            $sFolderPath = $this->request()->get('path');
            $sFolderPath = APP_UPLOAD_PATH . $sFolderPath;
            if ($this->IsUploadedPath($sFolderPath))
            {
                $oFileManager = new File();
                $aScannedFiles = $oFileManager->scanDir($sFolderPath);
                $oImage = new Image();
                $oMedia = new Media();
                foreach ($aScannedFiles as $iKey => $aFile)
                {

                    $aFile['thumb'] = "";
                    if ($sMode == "explorer")
                    {
                        $aFile['defaultView'] = true;
                    } else
                    {
                        $aFile['defaultView'] = false;
                    }

                    $aFile['absolute_path'] = str_replace(APP_UPLOAD_PATH, "", $aFile['full_path']);
                    if ($aFile['absolute_path'] == APP_PUBLIC_PATH)
                    {
                        continue;
                    }
                    if ($aFile['type'] == "file")
                    {
                        if (Utils::isImage($aFile['full_path']))
                        {
                            $aFile['thumb'] = $oImage->getThumbUrl($aFile['full_path'], 'medium-square');
                        }
                        else
                        {
                        	if($sTypeFile == "image")
                        	{
                        		continue;
                        	}
                        }
                        $aFile['defaultView'] = false;
                        $aFile['original'] = $oMedia->getOriginalUrl($aFile['absolute_path']);
                    } else
                    {
                        if ($aFile['title'] == ".." || in_array($aFile['full_path'], $this->_aDefaultFolder))
                        {
                            $aFile['defaultView'] = true;
                        }
                    }
                    unset($aFile['full_path']);
                    unset($aFile['path']);
                    $aFiles[] = $aFile;
                }
            }
        }

        system_display_result($aFiles);
    }

}
