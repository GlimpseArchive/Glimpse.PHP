<?php
/**
 * Glimpse plugin: Trace
 * 
 * Renders Trace data on the output protocol.
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
class Glimpse_Plugin_Trace
	implements Glimpse_Plugin_Interface
{
	/**
	 * Gets the plugin data that should be rendered on the output protocol stream.
	 * 
	 * @param Glimpse $glimpse The current Glimpse instance.
	 * @return array Array conforming to the Glimpse protocol definition.
	 */
	public function getData(Glimpse $glimpse) {
		$data = array(array('Message', 'Category', 'From First', 'From Last'));
		$traceData = Glimpse_Trace::retrieveMessages();
		foreach ($traceData as $traceEntry) {
			$traceEntry[] = strtolower($traceEntry[1]);
			$data[] = $traceEntry;
		}
		
		return array(
			"Trace" => count($data) > 0 ? $data : null
		);
	}
	
	/**
	 * Get the plugin help URL.
	 * 
	 * @return string Help URL for the plugin.
	 */
	public function getHelpUrl() {
		return "http://getGlimpse.com/Help/Plugin/Trace";
	}
}