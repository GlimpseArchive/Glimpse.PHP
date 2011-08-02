<?php
/**
 * Glimpse handler: Embedded Resource
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
class Glimpse_Handler_EmbeddedResource
	implements Glimpse_Handler_Interface
{
	/**
	 * The filename to serve.
	 * 
	 * @var string
	 */
	protected $_fileName = null;
	
	/**
	 * The content type to serve.
	 * 
	 * @var string
	 */
	protected $_contentType = null;
	
	/**
	 * Creates a new Glimpse_Handler_EmbeddedResourceHandler instance.
	 * 
	 * @param string $fileName The filename to serve.
	 * @param string $contentType The content type to serve.
	 */
	public function __construct($fileName, $contentType) {
		$this->_fileName = $fileName;
		$this->_contentType = $contentType;
	}
	
	/**
	 * Processes the handler pre-run.
	 * 
	 * @param Glimpse $glimpse The current Glimpse instance.
	 * @return string The rendered handler.
	 */
	public function processPreRun(Glimpse $glimpse) {
		if (!isset($_REQUEST['glimpseFile'])) {
			return;
		}
		
		$file = $_REQUEST['glimpseFile'];
		$configuration = $glimpse->retrieveConfiguration();
		$glimpseBaseDir = $configuration['glimpsebasedir'];
		$fileToRender = $glimpseBaseDir  . '/resources/' . $file;
		
		if (file_exists($fileToRender)) {
			header('Content-Type: ' . $this->_contentType);
			echo file_get_contents($fileToRender);
			$glimpse->endRequest();
		}
	}
	
	
	/**
	 * Processes the handler post-run.
	 * 
	 * @param Glimpse $glimpse The current Glimpse instance.
	 * @param string $file The file to handle.
	 * @return string The rendered handler.
	 */
	public function processPostRun(Glimpse $glimpse) {
	}
}