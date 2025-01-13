<?php
	// Error Reporting
error_reporting(E_ALL);

	// Check Version
if (version_compare(phpversion(), '5.4.0', '<') == true) {
	exit('PHP5.4+ Required');
}

if (!ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

if (isset($_SERVER['HTTP_ACCEPT']) && isset($_SERVER['HTTP_USER_AGENT'])) {
	if( strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false ) {	
		define('WEBPACCEPTABLE', true);	
	} else {
		define('WEBPACCEPTABLE', false);	
	}
} else {
	define('WEBPACCEPTABLE', false);
}

	// Windows IIS Compatibility
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['PATH_TRANSLATED'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

	if (isset($_SERVER['QUERY_STRING'])) {
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
	}
}

if (!isset($_SERVER['HTTP_HOST'])) {
	$_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

	// Check if SSL
if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) {
	$_SERVER['HTTPS'] = true;
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
	$_SERVER['HTTPS'] = true;
} else {
	$_SERVER['HTTPS'] = false;
}

	// Modification Override
function modification($filename) {
	if (defined('DIR_CATALOG')) {
		$file = DIR_MODIFICATION . 'admin75s47fd4777/' .  substr($filename, strlen(DIR_APPLICATION));
	} elseif (defined('DIR_OPENCART')) {
		$file = DIR_MODIFICATION . 'install/' .  substr($filename, strlen(DIR_APPLICATION));
	} else {
		$file = DIR_MODIFICATION . 'catalog/' . substr($filename, strlen(DIR_APPLICATION));
	}

	if (substr($filename, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
		$file = DIR_MODIFICATION . 'system/' . substr($filename, strlen(DIR_SYSTEM));
	}

	if (is_file($file)) {
		return $file;
	}

	return $filename;
}


function loadJsonConfig($config){
	if (defined('DIR_SYSTEM')){
		$json = file_get_contents(DIR_SYSTEM . 'config/' . $config . '.json');
	} else {
		$json = file_get_contents(dirname(__FILE__) . '/system/config/' . $config . '.json');
	}

	return json_decode($json, true);
}	

function writeJsonConfig($config, $data){

	if (defined('DIR_SYSTEM')){
		$file = DIR_SYSTEM . 'config/' . $config . '.json';
	} else {
		$file = dirname(__FILE__) . '/system/config/' . $config . '.json';
	}

	file_put_contents($file, json_encode($data));
}

function addToFirewall($ip){
	$json = loadJsonConfig('firewall');		
	$json['block'][$ip] = 1;

	writeJsonConfig('firewall', $json);
}

	//CHEKING FIREWALL
$blockIPS = loadJsonConfig('firewall');

if (!(php_sapi_name() === 'cli') && !empty($blockIPS['block'][$_SERVER['REMOTE_ADDR']])){
	header('HTTP/1.1 429 Too Many Requests');
	header('Content-type: text/html');

	echo '<html>
	<head>
	<title>Too Many Requests</title>
	</head>
	<body>
	<h1>Too Many Requests</h1>
	<p>Do not disturb, pls</p>
	</body>
	</html>';

	die();
}

	// Autoloader
if (is_file(DIR_SYSTEM . '../vendor/autoload.php')) {
	require_once(DIR_SYSTEM . '../vendor/autoload.php');
}


if (!(php_sapi_name() === 'cli') && !isset($_COOKIE['language']) && !isset($_SESSION['language']) && 
	stripos($_SERVER['REQUEST_URI'], 'index.php') === false && 
	stripos($_SERVER['REQUEST_URI'], '/ua') !== 0 &&
	stripos($_SERVER['REQUEST_URI'], '/ordersync') !== 0
){

	$crawlerDetect = new Jaybizzle\CrawlerDetect\CrawlerDetect;

if (!$crawlerDetect->isCrawler()) {
	$UA_URI = HTTPS_SERVER . 'ua' . $_SERVER['REQUEST_URI'];
	define('UA_REDIRECTION_URI', $UA_URI);		
	
	if ($_SERVER['REQUEST_URI'] == '/'){
		header("X-UA-REDIRECT:" . $UA_URI);

		header("X-UA-LANG-REDIRECT: Slava Ukraini!"); 
		header("HTTP/1.1 302 Found"); 
		header("Location: " . $UA_URI); 
		exit(); 
	}
}
}	

function library($class) {
	$file = DIR_SYSTEM . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';

	if (is_file($file)) {
		include_once(modification($file));
		
		return true;
	} else {
		return false;
	}
}

spl_autoload_register('library');
spl_autoload_extensions('.php');

	// Engine
require_once(modification(DIR_SYSTEM . 'engine/action.php'));
require_once(modification(DIR_SYSTEM . 'engine/controller.php'));
require_once(modification(DIR_SYSTEM . 'engine/event.php'));
require_once(modification(DIR_SYSTEM . 'engine/front.php'));
require_once(modification(DIR_SYSTEM . 'engine/loader.php'));
require_once(modification(DIR_SYSTEM . 'engine/model.php'));
require_once(modification(DIR_SYSTEM . 'engine/registry.php'));
require_once(modification(DIR_SYSTEM . 'engine/proxy.php'));

	// Helper
require_once(DIR_SYSTEM . 'helper/general.php');
require_once(DIR_SYSTEM . 'helper/utf8.php');
require_once(DIR_SYSTEM . 'helper/json.php');

function start($application_config) {
	require_once(DIR_SYSTEM . 'framework.php');	
}