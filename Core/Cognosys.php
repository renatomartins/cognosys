<?php
namespace Core;
use \Addendum,
	\Exception;

define('ROOT', realpath(dirname(__FILE__) . '/..') . '/');
define('CORE', ROOT . 'Core/');
define('LIB', ROOT . 'Lib/');
define('CONFIG', ROOT . 'Config/');
define('PUBLIC', ROOT . 'public/');
define('DOCTRINE', LIB . 'Doctrine/');
define('APP', ROOT . 'App/');
define('MODULES', APP . 'Modules/');
define('LAYOUT', APP . 'Layouts/');

require_once CORE . 'Autoloader.php';
require_once LIB . 'addendum/annotations.php';

/**
 * Initializes Cognosys, launches everything else
 * @author Renato S. Martins
 */
class Cognosys
{
	/**
	 * Main function
	 * @static
	 * @param int $stage
	 */
	static public function run()
	{
		$wt = new Cognosys;
		$wt->_init();
	}
	
	/**
	 * Launches core classes of the framework
	 */
	private function _init()
	{
		date_default_timezone_set('UTC');
		
		Autoloader::register(
			ROOT,		// loads all application classes from Core, App, etc...
			LIB,		// loads library components that follows the Autoloader's rules
			DOCTRINE	// loads independent library components in Doctrine, ie. Symfony
		);
		
		Addendum::registerNamespaces('Core\\Constraints');
		
		Config::load(CONFIG . 'development.yml');
		
		if (Config::get('development')) {
			error_reporting(E_ALL | E_STRICT);
		}
		
		Mail::configure(Config::get('mail'));
		
		Layout::register(LAYOUT, Config::get('layouts'));
		
		$request = null;
		$response = null;
		
		try {
			$request = new Request(Config::get('root'));
			
			// redirects the execution to a controller
			// according to the request parameters
			$response = new Response(
				$request,
				Config::get('routes'),
				Config::get('modules')
			);
			
			$controller = Controller::factory(
				$request,
				$response,
				Config::get('database')
			);
			$controller->run();
		} catch (Error $e) {
			$e->handle($request, $response);
		} catch (Exception $e) {
			//TODO: handle everything else
			echo "Error alert! Repeat: error alert! Wiiiu Wiiiu Wiii!<br>";
			var_dump($e);
		}
	}
}
