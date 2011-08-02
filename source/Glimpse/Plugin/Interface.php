<?php
/**
 * Glimpse plugin interface
 * 
 * Renders server data on the output protocol.
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
interface Glimpse_Plugin_Interface
{
	/**
	 * Gets the plugin data that should be rendered on the output protocol stream.
	 * 
	 * @param Glimpse $glimpse The current Glimpse instance.
	 * @return array Array conforming to the Glimpse protocol definition.
	 */
	function getData(Glimpse $glimpse);
	
	/**
	 * Get the plugin help URL.
	 * 
	 * @return string Help URL for the plugin.
	 */
	function getHelpUrl();
}