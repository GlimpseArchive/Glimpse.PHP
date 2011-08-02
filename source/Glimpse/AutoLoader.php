<?php
/** Glimpse root directory */
if (!defined('GLIMPSE_ROOT')) {
	$glimpseRoot = realpath(dirname(__FILE__) . '/../') . DIRECTORY_SEPARATOR;
	if (strlen($glimpseRoot) <= 2) {
		// PHAR file
		$glimpseRoot = dirname(__FILE__) . '/../';
	}
	define('GLIMPSE_ROOT', str_replace('\\', '/', $glimpseRoot));
}
Glimpse_AutoLoader::Register();

/**
 * Glimpse autoloader
 *
 * @author Maarten Balliauw <maarten@maartenballiauw.be>
 */
class Glimpse_AutoLoader
{
	/**
	 * Registers the autoloader
	 */
	public static function Register() {
		return spl_autoload_register(array('Glimpse_AutoLoader', 'Load'));
	}
	
	/**
	 * Load a class
	 * 
	 * @param string $className Class name to load
	 */
	public static function Load($className){
		if ((class_exists($className)) || (strpos($className, 'Glimpse') === false)) {
			return false;
		}

		$classFilePath = GLIMPSE_ROOT . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

		if ((file_exists($classFilePath) === false) || (is_readable($classFilePath) === false)) {
			return false;
		}

		require($classFilePath);
	}
}