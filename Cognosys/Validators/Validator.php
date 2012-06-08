<?php
namespace Cognosys\Validators;
use Cognosys\Alert;

/**
 * Base class for validators
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
//TODO: Regex validator
abstract class Validator
{
	/**
	 * @static
	 * @var array
	 */
	static private $_errors;
	
	/**
	 * Sets an alert message to given field
	 * @static
	 * @param string $field
	 * @param string $message
	 * @return void
	 */
	static public function set($field, $message)
	{
		if (isset(self::$_errors) === false) {
			self::$_errors = array();
		}
		
		self::$_errors[] = new Alert(Alert::ERROR, $message, $field);
	}
	
	/**
	 * Get all error alerts
	 * @static
	 * @return array of Alerts
	 */
	static public function get()
	{
		if (isset(self::$_errors) === false) {
			return array();
		}
		
		$errors = self::$_errors;
		self::$_errors = null;
		return $errors;
	}
}
