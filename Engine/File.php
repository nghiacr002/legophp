<?php

namespace APP\Engine;

use APP\Library\FileManager;

class File extends FileManager
{
	private $_aFile = array ();
	public function upload($sFormItem, $sFolderPath = "", $sNewFileName = "", $iPerm = 0644)
	{
		if (empty ( $sFolderPath ))
		{
			$sFolderPath = API_UPLOAD_PATH;
			$sUploadFilePath = $this->buildPath ( $sFolderPath );
		} else
		{
			$sUploadFilePath = $sFolderPath;
		}
		$this->getFile ( $sFormItem );
		if (empty ( $sNewFileName ))
		{
			$sNewFileName = $this->_aFile ['name']; // md5(uniqid().time());
		}
		$sNewUploadPath = $sUploadFilePath . $sNewFileName;
		$this->createIfMissingFolder ( $sUploadFilePath );
		if (isset ( $this->_aFile ['error'] ) && $this->_aFile ['error'] == UPLOAD_ERR_OK)
		{
			if (move_uploaded_file ( $this->_aFile ['tmp_name'], $sNewUploadPath ))
			{
				return str_replace ( $sFolderPath, '', $sNewUploadPath );
			}
		}
		return false;
	}
	public function getFileInfo($sFileName = "")
	{
		if (empty ( $sFileName ))
		{
			return $this->_aFile;
		}
		$aFile = array (
				'title' => basename ( $sFileName ),
				'path' => $sFileName,
				'type' => 'file',
				'time' => filectime ( $sFileName ),
				'size' => filesize ( $sFileName ),
				'perm' => '',
				'full_path' => $sFileName,
				'file_size_view' => 'N/A',
				'time_view' => 'N/A',
				'file_ext' => $this->getExt($sFileName),
		);
		$aFile ['file_size_view'] = $this->convertFileSize ( $aFile ['size'] );
		$aFile ['time_view'] = $this->getTime ( $aFile ['time'] );
		return $aFile;
	}
	public function getErrorFile($sCode)
	{
		// $sCode = isset($this->_aFile['error']) ? $this->_aFile['error'] : "##";
		switch ($sCode)
		{
			case UPLOAD_ERR_INI_SIZE :
				$message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
				break;
			case UPLOAD_ERR_FORM_SIZE :
				$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
				break;
			case UPLOAD_ERR_PARTIAL :
				$message = "The uploaded file was only partially uploaded";
				break;
			case UPLOAD_ERR_NO_FILE :
				$message = "No file was uploaded";
				break;
			case UPLOAD_ERR_NO_TMP_DIR :
				$message = "Missing a temporary folder";
				break;
			case UPLOAD_ERR_CANT_WRITE :
				$message = "Failed to write file to disk";
				break;
			case UPLOAD_ERR_EXTENSION :
				$message = "File upload stopped by extension";
				break;
			default :
				$message = "Unknown upload error";
				break;
		}
		return $message;
	}
	private function getFile($sFormItem)
	{
		if (strpos ( $sFormItem, ']' ) === false)
		{
			$this->_aFile = $_FILES [$sFormItem];
		} elseif (preg_match ( '/^(.+)\[(.+)\]$/', $sFormItem, $aM ))
		{
			$this->_aFile ['name'] = $_FILES [$aM [1]] ['name'] [$aM [2]];
			$this->_aFile ['type'] = $_FILES [$aM [1]] ['type'] [$aM [2]];
			$this->_aFile ['tmp_name'] = $_FILES [$aM [1]] ['tmp_name'] [$aM [2]];
			$this->_aFile ['error'] = $_FILES [$aM [1]] ['error'] [$aM [2]];
			$this->_aFile ['size'] = $_FILES [$aM [1]] ['size'] [$aM [2]];
		}
		return false;
	}
	public function getExt($sFileName)
	{
		$sFileName = strtolower ( $sFileName );
		$aParts = explode ( '.', $sFileName );
		$iCnt = count ( $aParts ) - 1;
		$sExt = $aParts [$iCnt];
		if (strlen ( $sExt ) > 4)
		{
			return substr ( $sExt, 0, 4 );
		}
		return $sExt;
	}
	public function getLimit($iMaxSize)
	{
		$iUploadMaxFileSize = (ini_get ( 'upload_max_filesize' ) * 1048576);
		$iPostMaxSize = (ini_get ( 'post_max_size' ) * 1048576);

		if ($iUploadMaxFileSize > 0 && $iUploadMaxFileSize < ($iMaxSize * 1048576))
		{
			return ini_get ( 'upload_max_filesize' );
		}

		if ($iPostMaxSize > 0 && $iPostMaxSize < ($iMaxSize * 1048576))
		{
			return ini_get ( 'post_max_size' );
		}

		return $iMaxSize . 'MB';
	}
	public function createIfMissingFolder($sFolderPath, $sBasePath = null)
	{
		if (! $sBasePath)
		{
			$sBasePath = APP_UPLOAD_PATH;
		}
		$sPartChecking = str_replace ( $sBasePath, "", $sFolderPath );
		if ($sPartChecking)
		{
			$aParts = explode ( APP_DS, $sPartChecking );
			if (! count ( $aParts ))
			{
				$aParts = array (
						$aParts
				);
			}
			$sPath = $sBasePath;
			for($i = 0; $i < count ( $aParts ); $i ++)
			{
				$sPath = $sPath . APP_DS . $aParts [$i];
				if (! file_exists ( $sPath ))
				{
					@mkdir ( $sPath );
					@chmod ( $sPath, 0755 );
				}
			}
		}
	}
	public function buildPath($sFolderPath)
	{
		$sReturnPath = $sFolderPath;
		$sDate = date ( 'Y-m-d' );
		$aParts = explode ( '-', $sDate );
		foreach ( $aParts as $iKey => $sPart )
		{
			$sReturnPath .= $sPart . APP_DS;
			if (! is_dir ( $sReturnPath ))
			{
				@mkdir ( $sReturnPath, 0777 );
				@chmod ( $sReturnPath, 0777 );
			}
		}
		return $sReturnPath;
	}
	public function read($sFileName = "")
	{
		$oHandle = fopen ( $sFileName, "r" );
		if (! $oHandle)
		{
			return false;
		}
		$sContent = fread ( $oHandle, filesize ( $sFileName ) );
		fclose ( $oHandle );
		return $sContent;
	}
	public function write($sFileName, $sContent = "")
	{
		$oHandle = fopen ( $sFileName, "w" );
		if (! $oHandle)
		{
			return false;
		}
		$sContent = fwrite ( $oHandle, $sContent );
		fclose ( $oHandle );
		return $sContent;
	}
	public function append($sFileName, $sContent = "")
	{
		$oHandle = fopen ( $sFileName, "a" );
		if (! $oHandle)
		{
			return false;
		}
		$sContent = fwrite ( $oHandle, $sContent );
		fclose ( $oHandle );
		return $sContent;
	}
	public function scanFolder($sFolderPath = "", $bRecursive = false, $sFileType = ".php")
	{
		if (is_file ( $sFolderPath ))
		{
			return array (
					$sFolderPath
			);
		}
		if (! is_dir ( $sFolderPath ))
		{
			return array ();
		}
		$aListFiles = array ();
		$oHandle = opendir ( $sFolderPath );
		while ( false !== ($sFile = readdir ( $oHandle )) )
		{

			if ($bRecursive == true && is_dir ( $sFile ))
			{
				$aListFiles = array_merge ( $aListFiles, $this->scanFolder ( $sFile, $bRecursive ) );
			} elseif ($sFileType == "*" || strpos ( $sFile, $sFileType ) !== false)
			{
				$aListFiles [] = $sFile;
			}
		}
		closedir ( $oHandle );
		return $aListFiles;
	}
	public function download($sFileName, $sPathFile, $sContentType = 'application/force-download')
	{
		if (ini_get ( 'zlib.output_compression' ))
		{
			ini_set ( 'zlib.output_compression', 'Off' );
		}
		ob_clean ();
		ob_end_flush ();
		header ( 'Content-Description: File Transfer' );
		header ( "Pragma: public" ); // required
		header ( "Expires: 0" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Cache-Control: private", false ); // required for certain browsers
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Content-Type: " . $sContentType );
		header ( "Content-Length: " . filesize ( $sPathFile ) );
		header ( "Content-Disposition: attachment; filename=\"" . $sFileName . "\";" );
		$fd = fopen ( $sPathFile, "rb" );
		if ($fd)
		{
			while ( ! feof ( $fd ) )
			{
				$buffer = fread ( $fd, 1024 );
				echo $buffer;
			}
		}
		@fclose ( $fd );
		die ();
	}
	public function displayContent($sFileName, $sContentType = null, $aHeaders = array())
	{
		if ($sContentType == null)
		{
			$sContentType = mime_content_type ( $sFileName );
		}
		ob_clean ();
		header ( 'Content-type: ' . $sContentType );
		if(count($aHeaders))
		{
			foreach($aHeaders as $sHeader)
			{
				header($sHeader);
			}
		}
		else
		{
			header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60))); // 1 hour
		}
		readfile ( $sFileName );
		exit ();
	}
	public function isWritable($sPath)
	{
		return is_writable ( $sPath );
	}
}

?>