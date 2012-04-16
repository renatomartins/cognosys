<?php
namespace Core;
use Symfony\Component\Yaml\Yaml,
	\Exception;

/**
 * Loads YAML configuration files
 * @author Renato S. Martins
 * @example
 * <code>
 *   Config::load('/path/to/configuration/file.yml');
 *   Config::get('key');
 *   Config::get('multi/level/key');
 * </code>
 */
abstract class Config
{
	/**
	 * @static
	 * @var array
	 */
	static private $_properties;
	
	/**
	 * Loads a file with name $pathname
	 * @static
	 * @param string $name
	 */
	static final public function load($name)
	{
		try {
			self::$_properties = Yaml::parse($name);
		} catch (Exception $e) {
			self::$_properties = array();
		}
	}
	
	/**
	 * Returns the value associated with given key. To access deeper levels of the hierarchy of keys,
	 * just insert the various keys separated by a slash.
	 * If the key does not exist, returns the $default value
	 * Config::get("database") returns array("host" => "localhost", "port" => 3000, ...)
	 * Config::get("database/port") returns 3000
	 * @static
	 * @param string $key
	 * @param mixed $default
	 * @return mixed|null
	 */
	static final public function get($key, $default = null)
	{
		if (isset(self::$_properties) === null) {
			throw new Exception('Use Config::load to load a ocnfiguration file');
		}
		
		$hierarchy = explode('/', $key);
		$level = self::$_properties;

		foreach ($hierarchy as $k) {
			if (is_array($level) && isset($level[$k])) {
				$level = $level[$k];
			} elseif ($default === null) {
				throw new Exception(
					'Configuration does not have the key "' . $key . '"'
				);
			} else {
				return $default;
			}
		}

		return $level;
	}
}
