<?php
/**
 * Glimpse handler interface
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
interface Glimpse_Handler_Interface
{
	/**
	 * Processes the handler pre-run.
	 * 
	 * @param Glimpse $glimpse The current Glimpse instance.
	 * @return string The rendered handler.
	 */
	function processPreRun(Glimpse $glimpse);
	
	/**
	 * Processes the handler post-run.
	 * 
	 * @param Glimpse $glimpse The current Glimpse instance.
	 * @return string The rendered handler.
	 */
	function processPostRun(Glimpse $glimpse);
}