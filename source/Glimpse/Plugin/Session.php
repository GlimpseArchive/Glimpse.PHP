<?php
/**
 * Glimpse plugin: Session
 * 
 * Renders session data on the output protocol.
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
class Glimpse_Plugin_Session
	implements Glimpse_Plugin_Interface
{
	/**
	 * Gets the plugin data that should be rendered on the output protocol stream.
	 * 
	 * @param Glimpse $glimpse The current Glimpse instance.
	 * @return array Array conforming to the Glimpse protocol definition.
	 */
	public function getData(Glimpse $glimpse) {
		$data = array();
		
		if (isset($_SESSION) && count($_SESSION) > 0) {
			$data[] = array('Key', 'Value');
			foreach ($_SESSION as $key => $value) {
				$data = array($key, $value);
			}	
		}
		
		return array(
			"Session" => count($data) > 0 ? $data : null
		);
	}
	
	/**
	 * Get the plugin help URL.
	 * 
	 * @return string Help URL for the plugin.
	 */
	public function getHelpUrl() {
		return "http://getGlimpse.com/Help/Plugin/Session";
	}
}