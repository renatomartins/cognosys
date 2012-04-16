<?php
namespace Core;
use \Exception;

/**
 * Class that choose a layout automatically
 * Before using the method 'get' you must register the path where
 * to look for template files and an array of possible layout names
 * @author Renato S. Martins
 */
//FIXME: a way to force a layout
//FIXME: layout file has URLS not based on root URL
//TODO: check device resolutions, etc..
class Layout
{
	/**
	 * @static
	 * @var string
	 */
	static private $_path;
	
	/**
	 * @static
	 * @var array
	 */
	static private $_choices;
	
	/**
	 * @static
	 * @var string Path to layout file
	 */
	static private $_chosen;
	
	/**
	 * @static
	 * @param string $path
	 * @param array $layouts
	 */
	static public function register($path, array $layouts)
	{
		self::$_path = $path;
		self::$_choices = $layouts;
		
		//FIXME: 'default' layout is hardcoded!
		//TODO: if there is only one, choose that right away
		$choice = self::$_path . self::$_choices[0] . '.php';
		if (is_readable($choice) === false) {
			throw new Exception('Chosen layout does not exist: ' . $choice);
		}
		
		self::$_chosen = $choice;
	}
	
	static public function get()
	{
		if (isset(self::$_chosen) === false) {
			throw new Exception('Use Layout::register to choose a layout path');
		}
		
		return self::$_chosen;
	}
}