<?php
// Glimpse base directory
$glimpseBaseDir = str_replace('/index.php', '', str_replace('\\', '/', __FILE__));

// Include autoloader
require_once $glimpseBaseDir . '/Glimpse/AutoLoader.php';

// Glimpse configuration
$glimpseConfiguration = array(
	'glimpsebasedir' => $glimpseBaseDir
);

// Read configuration
$glimpseConfigurationFile = dirname(str_replace('phar://', '', $glimpseBaseDir) . '/glimpse') . '/glimpse.ini';
if (file_exists($glimpseConfigurationFile)) {
	$configuration = parse_ini_file($glimpseConfigurationFile, true);
	$glimpseConfiguration = array_merge($glimpseConfiguration, $configuration['Glimpse']);
}

// Start Glimpse
$glimpse = new Glimpse($glimpseConfiguration);