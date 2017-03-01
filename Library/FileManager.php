<?php

namespace APP\Library;

class FileManager {
	private $_sCurrentPath;
	private $_mErrors;
	private $_bMoving = false;
	private $_sFileLocation = "";
	public function rename() {
		$sNewName = isset ( $_POST ['name'] ) ? $_POST ['name'] : "";
		$aData = isset ( $_POST ['data'] ) ? $_POST ['data'] : array ();
		$aReturn = array (
				'status' => 'success',
				'message' => 'Rename successfully'
		);
		if (! empty ( $sNewName ) && count ( $aData )) {
			$sOldFile = $aData [0];
			$sOldFileName = basename ( $sOldFile );
			$sNewFile = str_replace ( $sOldFileName, $sNewName, $sOldFile );
			// echo $sNewFile;
			@rename ( $sOldFile, $sNewFile );
			if (! file_exists ( $sNewFile )) {
				$aReturn ['status'] = 'error';
				$aReturn ['message'] = 'Cannot rename file ' . $sOldFile;
			}
		}
		echo json_encode ( $aReturn );
		exit ();
	}
	public function newFolder() {
		$sPath = isset ( $_POST ['path'] ) ? $_POST ['path'] : "";
		$sFileName = isset ( $_POST ['file'] ) ? $_POST ['file'] : "";
		$sPerm = isset ( $_POST ['perm'] ) ? $_POST ['perm'] : "0755";
		$aReturn = array (
				'status' => 'error',
				'message' => 'Cannot create new file ' . $sFileName
		);
		if (! empty ( $sFileName ) && ! empty ( $sPath )) {
			$sFullPathFile = $sPath . $sFileName;
			if (is_dir ( $sFullPathFile )) {
				$aReturn ['message'] = 'The folder has already existed in ' . $sPath;
			} else {
				@mkdir ( $sFullPathFile, intval ( $sPerm, 8 ) );
				if (is_dir ( $sFullPathFile )) {
					$aReturn ['message'] = 'The folder was created successfully';
					$aReturn ['status'] = 'success';
				} else {
					$aReturn ['message'] = 'Cannot create folder in ' . $sPath;
				}
			}
		}
		echo json_encode ( $aReturn );
		exit ();
	}
	public function permission() {
		$sPerm = isset ( $_POST ['perm'] ) ? $_POST ['perm'] : "0755";
		$aData = isset ( $_POST ['data'] ) ? $_POST ['data'] : array ();
		$aReturn = array (
				'status' => 'success',
				'message' => 'Changed permissions successfully'
		);
		if (count ( $aData )) {
			foreach ( $aData as $mValue ) {
				@chmod ( $mValue, intval ( $sPerm, 8 ) );
			}
		}
		echo json_encode ( $aReturn );
		exit ();
	}
	public function newFile() {
		$sPath = isset ( $_POST ['path'] ) ? $_POST ['path'] : "";
		$sFileName = isset ( $_POST ['file'] ) ? $_POST ['file'] : "";
		$sPerm = isset ( $_POST ['perm'] ) ? $_POST ['perm'] : "";

		$aReturn = array (
				'status' => 'error',
				'message' => 'Cannot create new file ' . $sFileName
		);
		if (! empty ( $sFileName ) && ! empty ( $sPath )) {
			$sFullPathFile = $sPath . $sFileName;
			if (file_exists ( $sFullPathFile )) {
				$aReturn ['message'] = 'The file has already existed in ' . $sPath;
			} else {
				$oFile = @fopen ( $sFullPathFile, 'w' );
				if (! $oFile) {
					$aReturn ['message'] = 'Cannot create the file';
				} else {
					@fwrite ( $oFile, "" );
					$aReturn ['message'] = 'File ' . $sFileName . ' was created successfully';
					$aReturn ['status'] = 'success';
				}
				@fclose ( $oFile );
				if (file_exists ( $sFullPathFile )) {

					@chmod ( $sFullPathFile, intval ( $sPerm, 8 ) );
				}
			}
		}
		echo json_encode ( $aReturn );
		exit ();
	}
	public function removeFile($sFile) {
		if(file_exists($sFile)){
			@unlink ( $sFile );
			return true;
		}
		return false;
	}
	public function recurse_remove($sSourcePath, $bForceRemoveParent = true) {
		$dir = opendir ( $sSourcePath );
		while ( false !== ($file = readdir ( $dir )) ) {
			if (($file != '.') && ($file != '..')) {
				if (is_dir ( $sSourcePath . '/' . $file )) {
					$this->recurse_remove ( $sSourcePath . '/' . $file );
				} else {
					@unlink ( $sSourcePath . '/' . $file );
				}
			}
		}
		closedir ( $dir );
		if($bForceRemoveParent){
			@rmdir ( $sSourcePath );
		}

		return true;
	}
	public function copy() {
		$this->_bMoving = isset ( $_GET ['moving'] ) ? $_GET ['moving'] : 0;

		$sFolderPath = isset ( $_POST ['file'] ) ? $_POST ['file'] : "";
		$sPath = isset ( $_POST ['path'] ) ? $_POST ['path'] : "";
		$aData = isset ( $_POST ['data'] ) ? $_POST ['data'] : array ();
		$aReturn = array (
				'status' => 'error',
				'message' => 'Cannot copy. Please try again'
		);
		if (count ( $aData ) && ! empty ( $sFolderPath )) {
			if (! is_dir ( $sFolderPath )) {
				@mkdir ( $sFolderPath, 0755 );
			}
			if (is_dir ( $sFolderPath )) {
				foreach ( $aData as $mValue ) {
					if (is_file ( $mValue )) {
						@copy ( $mValue, $sFolderPath . '/' . basename ( $mValue ) );
						if ($this->_bMoving) {
							@unlink ( $mValue );
						}
					} else {
						$this->recurse_copy ( $mValue, $sFolderPath );
					}
				}
				$aReturn = array (
						'status' => 'success',
						'message' => 'Completed ' . (($this->_bMoving == 1) ? 'move' : 'copy') . ' process'
				);
			}
		}
		echo json_encode ( $aReturn );
		exit ();
	}
	public function recurse_copy($sSourcePath, $sDestinationPath) {
		$sBaseSource = $sSourcePath;
		if (! is_dir ( $sDestinationPath )) {
			@mkdir ( $sDestinationPath );
		}

		$dir = opendir ( $sSourcePath );

		while ( false !== ($file = readdir ( $dir )) ) {

			if (($file != '.') && ($file != '..')) {

				if (is_dir ( $sSourcePath . '/' . $file )) {

					$this->recurse_copy ( $sSourcePath . '/' . $file, $sDestinationPath . '/' . $file );
				} else {

					copy ( $sSourcePath . '/' . $file, $sDestinationPath . '/' . $file );
					if ($this->_bMoving) {
						@unlink ( $sSourcePath . '/' . $file );
					}
				}
			}
		}
		closedir ( $dir );
		if ($this->_bMoving) {

			@rmdir ( $sBaseSource );
		}
	}
	public function compress($sFileName, $aData) {
		$aReturn = array ();
		if (! is_writable ( dirname ( ($sFileName) ) )) {
			$aReturn = array (
					'message' => "Cannot create zip here",
					'status' => 'error'
			);
			return $aReturn;
		}
		if (count ( $aData ) && ! empty ( $sFileName )) {
			if (! extension_loaded ( 'zip' )) {
				$aReturn = array (
						'message' => "Server is not supported ZIP method",
						'status' => 'error'
				);
			} else {
				$aReturn = array (
						'status' => 'error',
						'message' => 'Cannot complete zip process. Please try again'
				);
				if ($this->_zip ( $aData, $sFileName, true )) {
					$aReturn = array (
							'status' => 'success',
							'message' => 'Completed zip process'
					);
				}
			}
		}
		return $aReturn;
	}
	public function unzip($sSourcePath, $sDestinationPath) {
		$zip = new ZipArchive ();
		$res = $zip->open ( $sSourcePath );
		if ($res == true) {
			$zip->extractTo ( $sDestinationPath );
			$zip->close ();
			return true;
		}
		return false;
	}
	public function zip($aSources, $sDestinationPath, $bIncludeDir = false) {
		if (! extension_loaded ( 'zip' )) {
			return false;
		}
		if (file_exists ( $sDestinationPath )) {
			@unlink ( $sDestinationPath );
		}
		$fp = fopen ( $sDestinationPath, 'w' );
		if ($fp === FALSE) {
			return false;
		}
		fclose ( $fp );
		$zip = new ZipArchive ();
		if (! $zip->open ( $sDestinationPath, ZIPARCHIVE::CREATE )) {
			return false;
		}
		foreach ( $aSources as $source ) {
			$source = str_replace ( '\\', '/', realpath ( $source ) );
			if (is_dir ( $source ) === true) {
				$files = new RecursiveIteratorIterator ( new RecursiveDirectoryIterator ( $source ), RecursiveIteratorIterator::SELF_FIRST );
				if ($bIncludeDir) {
					$arr = explode ( "/", $source );
					$maindir = $arr [count ( $arr ) - 1];
					$source = "";
					for($i = 0; $i < count ( $arr ) - 1; $i ++) {
						$source .= '/' . $arr [$i];
					}
					$source = substr ( $source, 1 );
					$zip->addEmptyDir ( $maindir );
				}
				foreach ( $files as $file ) {
					$file = str_replace ( '\\', '/', $file );
					if (in_array ( substr ( $file, strrpos ( $file, '/' ) + 1 ), array (
							'.',
							'..'
					) ))
						continue;
					$file = realpath ( $file );
					if (is_dir ( $file ) === true) {
						$zip->addEmptyDir ( str_replace ( $source . '/', '', $file . '/' ) );
					} else if (is_file ( $file ) === true) {
						$zip->addFromString ( str_replace ( $source . '/', '', $file ), file_get_contents ( $file ) );
					}
				}
			} else if (is_file ( $source ) === true) {
				$zip->addFromString ( basename ( $source ), file_get_contents ( $source ) );
			}
		}
		return $zip->close ();
	}
	public function scanDir($sPath = null, $bUpdatePath = true) {
		if ($sPath == null) {
			$sPath = $this->_sCurrentPath;
		} else {
			if ($bUpdatePath) {
				$this->setPath ( $sPath );
			}
		}
		if (strrpos ( $sPath, APP_DS ) != strlen ( $sPath ) - 1) {
			$sPath = $sPath . APP_DS;
		}
		$mData = @scandir ( $sPath, SCANDIR_SORT_ASCENDING );

		//d($mData);
		$aResult = array (
				'folder' => array (),
				'file' => array ()
		);
		if (count ( $mData )) {
			foreach ( $mData as $key => $hFile ) {
				if ($hFile == ".") {
					continue;
				}
				$aFile = array (
						'title' => $hFile,
						'path' => $sPath,
						'type' => 'folder',
						'time' => 'N/A',
						'size' => 'N/A',
						'perm' => '',
						'full_path' => $sPath,
						'file_size_view' => 'N/A',
						'time_view' => 'N/A'
				);
				switch ($hFile) {
					case "." :
						break;

					case ".." :
						$aFile ['path'] = dirname ( $aFile ['path'] ) . APP_DS;
						$aFile ['full_path'] = $aFile ['path'];
						break;

					default :
						$sFullPath = $sPath . $hFile;
						if (is_dir ( $sFullPath )) {
							$aFile ['type'] = "folder";
						} else {
							$aFile ['type'] = "file";
							$aFile ['time'] = filectime ( $sFullPath );
							$aFile ['size'] = @filesize ( $sFullPath );
							$aFile ['file_size_view'] = $this->convertFileSize ( $aFile ['size'] );
							$aFile ['time_view'] = $this->getTime ( $aFile ['time'] );
						}
						$aFile ['full_path'] = $sFullPath;
						break;
				}
				$aFile ['perm'] = $this->getPermision ( $aFile ['full_path'] );
				$aResult [$aFile ['type']] [] = $aFile;
			}
		}
		return array_merge ( $aResult ['folder'], $aResult ['file'] );
	}
	public function getPermision($mPath) {
		return substr ( sprintf ( '%o', fileperms ( $mPath ) ), - 4 );
	}
	public function convertFileSize($bytes, $sType = "") {
		$label = array (
				'B',
				'KB',
				'MB',
				'GB',
				'TB',
				'PB'
		);
		for($i = 0; $bytes >= 1024 && $i < (count ( $label ) - 1); $bytes /= 1024, $i ++)
			;
		return (round ( $bytes, 2 ) . " " . $label [$i]);
	}
	public function getTime($iTime = null) {
		if (! $iTime || $iTime == "N/A") {
			return "N/A";
		}
		return date ( "Y M d H:i:s", ( int ) $iTime );
	}
	public function setPath($sPath = "") {
		$this->_sCurrentPath = $sPath;
		return $this;
	}
	public function getPath() {
		return $this->_sCurrentPath;
	}
	public function setError($sCode, $sMessage) {
		$this->_mErrors [] = array (
				'code' => $sCode,
				'message' => $sMessage
		);
		return $this;
	}
	public function isError() {
		return (count ( $this->_mErrors ) > 0) ? true : false;
	}
	public function getErrors() {
		return $this->_mErrors;
	}
	function getMaximumFileUploadSize($bByteReturn = false) {
		$mValue = min ( ini_get ( 'post_max_size' ), ini_get ( 'post_max_size' ) );
		if ($bByteReturn) {
			$mValue = (( int ) $mValue) * 1024;
		}
		return $mValue;
	}
}

?>