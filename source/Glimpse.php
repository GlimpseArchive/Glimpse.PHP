<?php
/**
 * Glimpse class
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
class Glimpse
{
	/**
	 * Cache for phpinfo() results
	 * 
	 * @var array
	 */
	protected $_phpInfoResults = null;
	
	/**
	 * Configuration
	 * 
	 * @var array
	 */
	protected $_configuration = array(
		'glimpsebasedir' => '',
		'plugins-disabled' => '',
		'requestlimit' => 15,
		'requeststore' => 'Glimpse_Store_File'
	);
	
	/**
	 * Loaded handlers
	 * 
	 * @var array
	 */
	protected $_glimpseHandlers = array();
	
	/**
	 * Loaded plugins
	 * 
	 * @var array
	 */
	protected $_glimpsePlugins = array();
	
	/**
	 * The current request identifier.
	 * 
	 * @var string
	 */
	protected $_requestId = null;
	
	/**
	 * The current request data.
	 * 
	 * @var string
	 */
	protected $_currentRequestData = null;
	
	/**
	 * The request store.
	 * 
	 * @var Glimpse_Store_File
	 */
	protected $_requestStore = null;
	
	/**
	 * Gets the Glimpse version.
	 * 
	 * @return float
	 */
	public function getVersion() {
		return 0.83;
	}
	
	/**
	 * Get Glimpse mode.
	 * 
	 * @return string Glimpse mode.
	 */
	public function getMode() {
		return strtolower( isset($_COOKIE['glimpseState']) && $_COOKIE['glimpseState'] != '' ? $_COOKIE['glimpseState'] : 'off' );
	}
	
	/**
	 * Gets the current request identifier.
	 * 
	 * @return string The request identifier.
	 */
	public function getRequestId() {
		return $this->_requestId;
	}
	
	/**
	 * Gets the current request data.
	 * 
	 * @return mixed
	 */
	public function getCurrentRequestData() {
		return $this->_currentRequestData;
	}
	
	/**
	 * Register a Glimpse handler.
	 * 
	 * @param Glimpse_Handler_Interface $handler The handler instance to register.
	 * @return Glimpse
	 */
	public function registerHandler(Glimpse_Handler_Interface $handler) {
		$this->_glimpseHandlers[] = $handler;
		return $this;
	}
	
	/**
	 * Retrieves the list of registered handlers.
	 * 
	 * @return array Array of Glimpse_Handler_Interface
	 */
	public function retrieveHandlers() {
		return $this->_glimpseHandlers;
	}
	
	/**
	 * Register a Glimpse plugin.
	 * 
	 * @param Glimpse_Plugin_Interface $plugin The plugin instance to register.
	 * @return Glimpse
	 */
	public function registerPlugin(Glimpse_Plugin_Interface $plugin) {
		$this->_glimpsePlugins[] = $plugin;
		return $this;
	}
	
	/**
	 * Retrieves the list of registered plugins.
	 * 
	 * @return array Array of Glimpse_Plugin_Interface
	 */
	public function retrievePlugins() {
		return $this->_glimpsePlugins;
	}
	
	/**
	 * Sets the list of registered plugins.
	 * 
	 * @param array $plugin Array of Glimpse_Plugin_Interface
	 * @return Glimpse
	 */
	public function setPlugins($value = array()) {
		$this->_glimpsePlugins = $value;
		return $this;
	}
	
	/**
	 * Configures Glimpse.
	 * 
	 * @param array $configuration Array containg configuration values.
	 * @return Glimpse
	 */
	public function configure($configuration = array()) {
		foreach ($configuration as $key => $value) {
			$this->_configuration[$key] = $value;
			
			switch(strtolower($key)) {
				case 'plugins-disabled':
					$pluginsDisabled = explode(',', $value);
					foreach ($pluginsDisabled as $pluginDisable) {
						foreach ($this->_glimpsePlugins as $pluginKey => $pluginValue) {
							if (is_a($this->_glimpsePlugins[$pluginKey], $pluginDisable)) {
								unset($this->_glimpsePlugins[$pluginKey]);
							}
						}
					}
					break;
			}
		}
		
		return $this;
	}
	
	/**
	 * Retrieves the current Glimpse configuration.
	 * 
	 * @return array
	 */
	public function retrieveConfiguration() {
		return $this->_configuration;
	}
	
	/**
	 * Creates a Glimpse instance.
	 * 
	 * @param array $configuration The Glimpse configuration.
	 */
	public function __construct($configuration = array()) {
		$this->_requestId = uniqid('glimpse', true);
		
		$this->registerHandler(new Glimpse_Handler_EmbeddedResource('client.js', 'text/javascript'))
		     ->registerHandler(new Glimpse_Handler_EmbeddedResource('logo.png', 'image/png'))
		     ->registerHandler(new Glimpse_Handler_EmbeddedResource('sprite.png', 'image/png'))
		     ->registerHandler(new Glimpse_Handler_Config())
		     ->registerHandler(new Glimpse_Handler_Clients())
		     ->registerHandler(new Glimpse_Handler_History())
		     ->registerHandler(new Glimpse_Handler_Data());
			 
		$this->registerPlugin(new Glimpse_Plugin_Server())
			 ->registerPlugin(new Glimpse_Plugin_Session())
			 ->registerPlugin(new Glimpse_Plugin_Request())
			 ->registerPlugin(new Glimpse_Plugin_Config())
			 ->registerPlugin(new Glimpse_Plugin_Trace())
			 ->registerPlugin(new Glimpse_Plugin_Environment());
		//$this->registerPlugin(new Glimpse_Plugin_Plugins());
		
		$this->configure($configuration);
		
		$this->_dispatchPreRun();
	}
	
	/**
	 * Destructs the Glimpse instance and renders the client-side code when Glimpse is enabled.
	 */
	public function __destruct() {
		$this->_dispatchPostRun();
	}
	
	/**
	 * Dispatches the pre-run handlers for the current request.
	 */
	protected function _dispatchPreRun() {
		// Pre-run can run regardless of $this->getMode()		
		$handlers = $this->retrieveHandlers();
		foreach ($handlers as $handler) {
			$handler->processPreRun($this);
		}
	}
	
	/**
	 * Collects Glimpse data
	 */
	protected function _collectData() {
		// Collect data for the current request
		$glimpseData = array();
		foreach ($this->retrievePlugins() as $plugin) {
			$data = $plugin->getData($this);
			if (!is_null($data) && is_array($data)) {
				foreach ($data as $tabName => $tabData) {
					$glimpseData[$tabName] = $tabData;
				}
			}
		}
		$glimpseData = (object)$glimpseData;
		
		// Glimpse client name
		$glimpseClientName = 'null';
		if (isset($_COOKIE['glimpseClientName']) && $_COOKIE['glimpseClientName'] != '') {
			$glimpseClientName = $_COOKIE['glimpseClientName'];
		}

		// Create request metadata
		$requestData = (object)array(
			'GlimpseClientName' => $glimpseClientName,
			'Method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'Unknown',
			'Json' => '',
			'Browser' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown',
			'ClientName' => $glimpseClientName, 
			'RequestTime' => isset($_SERVER['REQUEST_TIME']) ? date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) : 'Unknown',
			'RequestId' => $this->getRequestId(),
			'IsAjax' => isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_USER_AGENT'] == 'XMLHttpRequest',
			'Url' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'Unknown',
			'Data' => $glimpseData
		);

		// Store request data
		$this->_currentRequestData = $requestData;
		$this->_appendRequestHistory($requestData);
	}
	
	/**
	 * Gets the request history.
	 * 
	 * @return array Array of requests.
	 */
	public function getRequestHistory() {
		// Retrieve request history from store
		$requestStore = $this->_createRequestStore();
		$requestStoreData = $requestStore->fetch();
		if (!is_array($requestStoreData)) {
			$requestStoreData = array();
		}
		return $requestStoreData;
	}
	
	/**
	 * Append a request history entry.
	 * 
	 * @param mixed $requestData The request history entry to append.
	 */
	protected function _appendRequestHistory($requestData) {
		// Retrieve request history from store
		$requestStore = $this->_createRequestStore();
		$requestStoreData = $requestStore->fetch();
		if (is_array($requestStoreData)) {
			$requestlimit = $this->_configuration['requestlimit'];
			while (count($requestStoreData) >= $requestlimit) {
				array_shift($requestStoreData);
			}
		} else {
			$requestStoreData = array();
		}
		$requestStoreData[] = $requestData;
		$requestStore->store($requestStoreData);
	}
	
	/**
	 * Dispatches the post-run handlers for the current request.
	 */
	protected function _dispatchPostRun() {	
		if ($this->getMode() == 'off') {
			return;
		}

		// Only track non-Glimpse requests
		if (!isset($_REQUEST['glimpseFile'])) {
			$this->_collectData();
		}
		
		$handlers = $this->retrieveHandlers();
		foreach ($handlers as $handler) {
			$handler->processPostRun($this);
		}
	}
	
	/**
	 * Ends the current HTTP request
	 */
	public function endRequest() {
		die();
	}
	
	/**
	 * Create a request store.
	 * 
	 * @return Glimpse_Store_Interface
	 */
	protected function _createRequestStore() {
		if (is_null($this->_requestStore)) {
			$fileStoreClass = $this->_configuration['requeststore'];
			$this->_requestStore = new $fileStoreClass();
		}
		return $this->_requestStore;
	}
	
	/**
	 * Retrieve phpinfo() output as array.
	 * 
	 * @return array
	 */
	public function retrievePhpInfo() {
		if (!is_null($this->_phpInfoResults)) {
			return $this->_phpInfoResults;
		}
		
		ob_start(); 
		phpinfo(-1);
		  
		$pi = preg_replace(
		array('#^.*<body>(.*)</body>.*$#ms', '#<h2>PHP License</h2>.*$#ms',
		  '#<h1>Configuration</h1>#',  "#\r?\n#", "#</(h1|h2|h3|tr)>#", '# +<#',
		  "#[ \t]+#", '#&nbsp;#', '#  +#', '# class=".*?"#', '%&#039;%',
		   '#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>'
		   .'<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#',
		   '#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#',
		   '#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#',
		   "# +#", '#<tr>#', '#</tr>#'),
			array('$1', '', '', '', '</$1>' . "\n", '<', ' ', ' ', ' ', '', ' ',
			   '<h2>PHP Configuration</h2>'."\n".'<tr><td>PHP Version</td><td>$2</td></tr>'.
			   "\n".'<tr><td>PHP Egg</td><td>$1</td></tr>',
			   '<tr><td>PHP Credits Egg</td><td>$1</td></tr>',
			   '<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" .
			   '<tr><td>Zend Egg</td><td>$1</td></tr>', ' ', '%S%', '%E%'),
		ob_get_clean());
 
		$sections = explode('<h2>', strip_tags($pi, '<h2><th><td>'));
		unset($sections[0]);
		 
		$pi = array();
		foreach($sections as $section){
		    $n = substr($section, 0, strpos($section, '</h2>'));
		    preg_match_all('#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#', $section, $askapache, PREG_SET_ORDER);
		    foreach($askapache as $m) {
		        $pi[$n][$m[1]] = isset($m[2]) && (!isset($m[3]) || $m[2] == $m[3]) ? $m[2] : array_slice($m, 2);
		    }
		}
		
		$this->_phpInfoResults = $pi;
		return $pi;
	}
}