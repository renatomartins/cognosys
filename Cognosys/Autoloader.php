<?php
namespace Cognosys;

/**
 * Loads classes based in their namespace. There must be a correspondence between their path and namespace, i.e.:
 * <code>my_project/Application/Models/User.php</code>
 * has the namespace
 * <code>Application\Models\User</code>
 * You must specify the path of your project, so it will prepend that path,
 * replace the backslashes with forward slashes and result in the actual path
 * the Autoloader will look for.
 *
 * Usage example:
 * <code>
 * 	Autoloader::register(realpath("../../my_project")); //yes, same as ".." but just to clear any doubts to what should be the root
 * </code>
 * 
 * <strong>Warning</strong>: Be sure your external libraries have this correspondence between
 * path and namespace and add their root path in the register function.
 * If a class does not have a namespace, it must be included manually
 * 
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class Autoloader
{
	static private $_path_list = array();
	static private $_registered = false;
	
	/**
	 * Add one or more path to the autoload, can be called several times to add paths.
	 * The first time it's called, registers the autoload function in the SPL autoloader
	 * 
	 * Usage example:
	 * <code>
	 * 	AutoLoader::register($path_to_root, $path_to_doctrine [, etc.])
	 * </code>
	 * 
	 * @static
	 */
	static public function register()
	{
		self::$_path_list = array_merge(self::$_path_list, func_get_args());

		if (!self::$_registered) {
			spl_autoload_register(array('self', 'autoload'));
			self::$_registered = true;
		}
	}
	
	/**
	 * Tries to load classes based on their namespace<br>
	 * @static
	 * @param string $class
	 */
	static public function autoload($class)
	{
		$class = str_replace('\\', '/', $class) . '.php';

		foreach (self::$_path_list as $path) {
			if (file_exists($path . $class))
				return require_once $path . $class;
		}
	}
}
