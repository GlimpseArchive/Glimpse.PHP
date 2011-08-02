<?php
/**
 * Glimpse handler: Data
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
class Glimpse_Handler_Data
	implements Glimpse_Handler_Interface
{
	/**
	 * Processes the handler pre-run.
	 * 
	 * @param Glimpse $glimpse The current Glimpse instance.
	 * @return string The rendered handler.
	 */
	public function processPreRun(Glimpse $glimpse) {
	}
	
	
	/**
	 * Processes the handler post-run.
	 * 
	 * @param Glimpse $glimpse The current Glimpse instance.
	 * @return string The rendered handler.
	 */
	public function processPostRun(Glimpse $glimpse) {
		if (!defined('GLIMPSE_RENDERED') && !isset($_REQUEST['glimpseFile'])) {
			define('GLIMPSE_RENDERED', true);

			$urlPath = $_SERVER['PHP_SELF'] . '?glimpseVersion=' . $glimpse->getVersion() . '&glimpseFile=';

			echo '<script type="text/javascript" id="glimpseData" data-glimpse-requestID="' . $glimpse->getRequestId() . '">';
			echo 'var glimpsePath = "' . $urlPath . '";';
			echo 'var glimpse = ' . json_encode($glimpse->getCurrentRequestData()->Data) . ';';
			echo '</script>';
			echo '<script type="text/javascript" src="' . $urlPath . 'client.js"></script>';
		}
	}
}