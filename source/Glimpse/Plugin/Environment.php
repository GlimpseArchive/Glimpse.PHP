<?php
/**
 * Glimpse plugin: Environment
 * 
 * Renders environment data on the output protocol.
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
class Glimpse_Plugin_Environment
	implements Glimpse_Plugin_Interface
{
	/**
	 * Gets the plugin data that should be rendered on the output protocol stream.
	 * 
	 * @param Glimpse $glimpse The current Glimpse instance.
	 * @return array Array conforming to the Glimpse protocol definition.
	 */
	public function getData(Glimpse $glimpse) {
		$info = $glimpse->retrievePhpInfo();
		$data = $info["Environment"];
		return array(
			"Environment" => count($data) > 0 ? $data : null
		);
	}
	
	/**
	 * Get the plugin help URL.
	 * 
	 * @return string Help URL for the plugin.
	 */
	public function getHelpUrl() {
		return "http://getGlimpse.com/Help/Plugin/Environment";
	}
}