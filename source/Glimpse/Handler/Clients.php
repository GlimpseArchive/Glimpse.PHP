<?php
/**
 * Glimpse handler: Clients
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
class Glimpse_Handler_Clients
	implements Glimpse_Handler_Interface
{
	/**
	 * Processes the handler pre-run.
	 * 
	 * @param Glimpse $glimpse The current Glimpse instance.
	 * @return string The rendered handler.
	 */
	public function processPreRun(Glimpse $glimpse) {
		if (!isset($_REQUEST['glimpseFile']) || $_REQUEST['glimpseFile'] != 'Clients') {
			return;
		}
		
		// HTTP headers
		header('Content-Type: application/json; charset=utf-8');
		
		// Build data
		$requestHistory = $glimpse->getRequestHistory();
		if (!is_array($requestHistory) || count($requestHistory) == 0) {
			echo "{'Error': true, 'Message': 'No history available.'}";
			$glimpse->endRequest();
		}
		
		$data = array();
		foreach ($requestHistory as $request) {
			$data[$request->GlimpseClientName][$request->RequestId] = (object)array(
				'Url' => $request->Url,
				'Browser' => $request->Browser,
				'RequestTime' => $request->RequestTime,
				'IsAjax' => $request->IsAjax,
				'Method' => $request->Method
			);
		}
		foreach ($data as $key => $value) {
			$data[$key] = (object)$value;
		}
		$data = (object)array('Data' => (object)$data);
		
		// Return a response
		echo json_encode($data);

		$glimpse->endRequest();
	}
	
	
	/**
	 * Processes the handler post-run.
	 * 
	 * @param Glimpse $glimpse The current Glimpse instance.
	 * @return string The rendered handler.
	 */
	public function processPostRun(Glimpse $glimpse) {
	}
}