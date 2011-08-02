<?php
/**
 * Glimpse plugin: Plugins
 * 
 * Renders plugin metadata on the output protocol.
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
class Glimpse_Plugin_Plugins
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
		
		$plugins = $glimpse->retrievePlugins();
		foreach ($plugins as $plugin) {
			$reflectedObject = new ReflectionObject($plugin);
			$pluginName = basename('/' . str_replace('_', '/', $reflectedObject->getName()));
			
			$data[$pluginName] = array(
				'helpUrl' => !is_null($plugin->getHelpUrl()) ? $plugin->getHelpUrl() : ''
			);
		}
		
		return array(
			"plugins" => $data
		);
	}
	
	/**
	 * Get the plugin help URL.
	 * 
	 * @return string Help URL for the plugin.
	 */
	public function getHelpUrl() {
		return null;
	}
}