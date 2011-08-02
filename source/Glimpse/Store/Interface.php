<?php
/**
 * Glimpse store interface
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
interface Glimpse_Store_Interface
{
	/**
	 * Store data.
	 * 
	 * @param mixed $data Data to store.
	 */
	function store($data = '');
	
	/**
	 * Fetch data.
	 * 
	 * @return mixed Data retrieved from store.
	 */
	function fetch();
}