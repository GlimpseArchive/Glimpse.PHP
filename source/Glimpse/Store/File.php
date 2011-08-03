<?php
/**
 * Glimpse store: File
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
class Glimpse_Store_File
	implements Glimpse_Store_Interface
{
	protected $_fileName = '';
	
	public function __construct($fileName = null) {
		if (is_null($fileName)) {
			$tmpDir = sys_get_temp_dir();
			if (substr($tmpDir, -1 ) != '/' && substr($tmpDir, -1 ) != '\\') {
				$tmpDir .= '/';
			}

			$fileName = $tmpDir . 'glimpsestore' . md5(__FILE__);
		}
		
		$this->_fileName = $fileName;
	}
	
	/**
	 * Store data.
	 * 
	 * @param mixed $data Data to store.
	 */
	public function store($data = '') {
		$fp = fopen($this->_fileName, "w");
		
		if (flock($fp, LOCK_EX)) {
		    ftruncate($fp, 0);
		    fwrite($fp, serialize($data));
		    flock($fp, LOCK_UN);
		}
		
		fclose($fp);
	}
	
	/**
	 * Fetch data.
	 * 
	 * @return mixed Data retrieved from store.
	 */
	public function fetch() {
		if (!file_exists($this->_fileName)) {
			return null;
		}
		
		$fileContents = '';
		
		$fp = fopen($this->_fileName, "r");
		if (flock($fp, LOCK_SH)) {
		    $fileContents = fread($fp, filesize($this->_fileName));
		    flock($fp, LOCK_UN);
		}
		
		fclose($fp);
		return unserialize($fileContents);
	}
}