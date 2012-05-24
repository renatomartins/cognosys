<?php
namespace Cognosys;
use \Addendum,
	\Exception;

define('ROOT', realpath(dirname(__FILE__) . '/..') . '/');
define('COGNOSYS', ROOT . 'Cognosys/');
define('LIB', ROOT . 'Lib/');
define('CONFIG', ROOT . 'Config/');
define('PUBLIC', ROOT . 'public/');
define('DOCTRINE', LIB . 'Doctrine/');
define('APP', ROOT . 'App/');
define('MODULES', APP . 'Modules/');
define('LAYOUT', APP . 'Layouts/');

require_once COGNOSYS . 'Autoloader.php';
require_once LIB . 'addendum/annotations.php';

/**
 * Initializes Cognosys, launches everything else
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class Cognosys
{
	/**
	 * Main function
	 * Launches core classes of the framework
	 * @static
	 */
	static public function run()
	{
		date_default_timezone_set('UTC');
		
		Autoloader::register(
			ROOT,		// loads all application classes from Cognosys, App, etc...
			LIB,		// loads library components that follows the Autoloader's rules
			DOCTRINE	// loads independent library components in Doctrine, ie. Symfony
		);
		
		// Register contraint classes to use in the annotations of controllers
		Addendum::registerNamespaces('Cognosys\\Constraints');
		
		Config::load(CONFIG . 'test.yml');
		
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
			echo "An unexpected exception occured!<br><br>";
			var_dump($e);
		}
	}
}
