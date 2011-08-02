<?php
/**
 * Glimpse plugin: Request
 * 
 * Renders request data on the output protocol.
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
class Glimpse_Plugin_Request
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
		
		if (isset($_REQUEST) && count($_REQUEST) > 0) {
			$data['_REQUEST'] = array(array('Key', 'Value'));
			foreach ($_REQUEST as $key => $value) {
				$data['_REQUEST'][] = array($key, $value);
			}	
		}
		if (isset($_GET) && count($_GET) > 0) {
			$data['_GET'] = array(array('Key', 'Value'));
			foreach ($_GET as $key => $value) {
				$data['_GET'][] = array($key, $value);
			}
		}
		if (isset($_POST) && count($_POST) > 0) {
			$data['_POST'] = array(array('Key', 'Value'));
			foreach ($_POST as $key => $value) {
				$data['_POST'][] = array($key, $value);
			}
		}
		
		return array(
			"Request" => count($data) > 0 ? $data : null
		);
	}
	
	/**
	 * Get the plugin help URL.
	 * 
	 * @return string Help URL for the plugin.
	 */
	public function getHelpUrl() {
		return "http://getGlimpse.com/Help/Plugin/Request";
	}
}