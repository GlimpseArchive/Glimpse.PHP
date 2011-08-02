<?php
/**
 * Glimpse Trace class
 * 
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
class Glimpse_Trace
{
	/**
	 * Trace messages
	 * 
	 * @var array
	 */
	private static $_messages = array();

	/**
	 * First timing
	 * 
	 * @var int
	 */
	private static $_timingFirst = 0;
	
	/**
	 * Last timing
	 * 
	 * @var int
	 */
	private static $_timingLast = 0;
	
	/**
	 * Creates a new Glimpse_Trace instance.
	 */
	private function __construct() {
	}
	
	/**
	 * Retrieve log messages.
	 * 
	 * @return array
	 */
	public static function retrieveMessages() {
		return self::$_messages;
	}
		
	/**
	 * Logs an Info message to the trace log.
	 * 
	 * @param string $message Message to log.
	 */
	public static function info($message = '') {
		self::log('Info', $message);
	}
		
	/**
	 * Logs a Warn message to the trace log.
	 * 
	 * @param string $message Message to log.
	 */
	public static function warn($message = '') {
		self::log('Warn', $message);
	}
		
	/**
	 * Logs an Error message to the trace log.
	 * 
	 * @param string $message Message to log.
	 */
	public static function error($message = '') {
		self::log('Error', $message);
	}
		
	/**
	 * Logs a Fail message to the trace log.
	 * 
	 * @param string $message Message to log.
	 */
	public static function fail($message = '') {
		self::log('Fail', $message);
	}
	
	/**
	 * Logs a message to the trace log.
	 * 
	 * @param string $category Category to log (Info, Warn, Error, Fail).
	 * @param string $message Message to log.
	 */
	public static function log($category = 'Info', $message = '') {
		// Timing
		$timeFromFirst = 0;
		$timeFromLast = 0;
		if (self::$_timingFirst == 0) {
			self::$_timingFirst = microtime(true);
			self::$_timingLast = self::$_timingFirst;
		} else {
			$timeFromFirst = (microtime(true) - self::$_timingFirst) * 1000;
			$timeFromLast = (microtime(true) - self::$_timingLast) * 1000;
		}
	
		 // Log
		self::$_messages[] = array($message, $category, number_format($timeFromFirst, 2) . ' ms',  number_format($timeFromLast, 2) . ' ms');
		
		// Timing
		self::$_timingLast = microtime(true);
	}
}