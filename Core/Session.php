<?php
namespace Core;

/**
 * Session handler
 * @author António Santos
 * @author Renato S. Martins
 */
class Session
{
	static private $_key = '_webtool_';
	
	/**
	 * @staticvar Core\Session
	 */
	static private $_instance;
	
	/**
	 * Returns the singleton session
	 * @static
	 * @return Core\Session
	 */
	static public function instance()
	{
		if (self::$_instance === null) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	/**
	 * Starts the session and reset its id
	 */
	private function __construct()
	{
		session_start();
		session_regenerate_id();
	}
	
	/**
	 * Destroys this session
	 * @return void
	 */
	public function destroy()
	{
		session_destroy();
	}
	
	/**
	 * Checks if given key is set in the session
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return isset($_SESSION[self::$_key . $key]);
	}
	
	/**
	 * If given key exists in the session, returns the associated value, false otherwise
	 * @param string $key
	 * @param bool $delete true
	 * @return mixed|null
	 */
	public function get($key, $delete = true)
	{
		$value = $this->has($key)
			? $_SESSION[self::$_key . $key]
			: null;
		if ($delete) {
			$this->remove($key);
		}
		return $value;
	}
	
	/**
	 * Stores the value in the session with given key and returns it
	 * @param string $key
	 * @param mixed $value
	 * @return mixed
	 */
	public function set($key, $value)
	{
		return $_SESSION[self::$_key . $key] = $value;
	}
	
	/**
	 * Unsets the key in the session
	 * @param string $key
	 * @return void
	 */
	public function remove($key)
	{
		unset($_SESSION[self::$_key . $key]);
	}
	
	/**
	 * Just returns $_SESSION
	 * @return array
	 */
	public function dump()
	{
		return $_SESSION;
	}
}
