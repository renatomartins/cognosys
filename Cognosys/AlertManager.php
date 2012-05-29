<?php
namespace Cognosys;
use Cognosys\Exceptions\ApplicationError,
	\ReflectionClass;

/**
 * Manage persistent messages through requests
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class AlertManager
{
	/**
	 * Key to set in the session
	 * @static
	 */
	static private $_key = 'alerts';
	
	/**
	 * @static
	 * @var array
	 */
	static private $_types;
	
	/**
	 * Sets an alert in the session
	 * @static
	 * @param int $type a constant from this class
	 * @param string $message
	 * @return void
	 * @throws Cognosys\Exceptions\ApplicationError
	 */
	static public function set($type, $message)
	{
		self::_assertType($type);
		
		$alerts = self::_getFromSession();
		array_push($alerts, new Alert($type, $message));
		self::_setInSession($alerts);
	}
	
	/**
	 * Sets multiple alerts in the session
	 * @static
	 * @param array $alerts
	 * @return void
	 * @throws Cognosys\Exceptions\ApplicationError
	 */
	static public function import(array $alerts)
	{
		foreach ($alerts as $alert) {
			self::_assertType($alert->type());
		}
		self::_setInSession(
			array_merge(self::_getFromSession(), $alerts)
		);
	}
	
	/**
	 * Gets an array of alerts of given type,
	 * deleting the retrieved alerts from session
	 * @static
	 * @param int $type
	 * @return array
	 * @throws Cognosys\Exceptions\ApplicationError
	 */
	static public function byType($type = null)
	{
		$byType = array();
		
		if ($type === null) {
			$alerts = self::getAll();
			foreach ($alerts as $i => $alert) {
				if ( ! isset($byType[$alert->type()])) {
					$byType[$alert->type()] = array();
				}
				$byType[$alert->type()][] = $alert;
				unset($alerts[$i]);
			}
		} else {
			self::_assertType($type);
			$alerts = self::_getFromSession();
			foreach ($alerts as $i => $alert) {
				if ($alert->type() === $type) {
					$byType[] = $alert;
					unset($alerts[$i]);
				}
			}
		}
		
		self::_setInSession($alerts);
		return $byType;
	}
	
	/**
	 * Gets an array of alerts of given field,
	 * deleting the retrieved alerts from the session
	 * @static
	 * @param string $field
	 * @param array
	 */
	static public function byField($field)
	{
		$alerts = self::_getFromSession();
		$byField = array();
		
		foreach ($alerts as $i => $alert) {
			if ($alert->field() === $field) {
				$byField[] = $alert;
				unset($alerts[$i]);
			}
		}
		
		self::_setInSession($alerts);
		return $byField;
	}
	
	/**
	 * Gets all alerts, deleting them from the session
	 * @static
	 * @return array
	 */
	static public function getAll()
	{
		$alerts = self::_getFromSession();
		self::clear();
		return $alerts;
	}
	
	/**
	 * Counts the alerts in the session,
	 * if no type is given returns the number of elements,
	 * otherwise just the elements that have given type
	 * @static
	 * @param int|null $type
	 * @return int
	 * @throws Cognosys\Exceptions\ApplicationError
	 */
	static public function count($type = null)
	{
		$alerts = self::_getFromSession();
		
		if ($type === null) {
			return count($alerts);
		} else {
			self::_assertType($type);
			return count(array_filter($alerts, function($alert) use ($type){
				return $alert->type() === $type;
			}));
		}
	}
	
	/**
	 * Reset the array of alerts in the session
	 * @static
	 * @return void
	 */
	static public function clear()
	{
		self::_setInSession(array());
	}
	
	/**
	 * Asserts if a type is valid
	 * @static
	 * @return void
	 * @throws Cognosys\Exceptions\ApplicationError
	 */
	static private function _assertType($type)
	{
		$types = self::_getTypes();
		
		if (in_array($type, $types) === false) {
			throw new ApplicationError(
				'The alert type "' . $type . '" is not a defined constant of '
				. get_called_class() . ' class'
			);
		}
	}
	
	/**
	 * Returns the available types in an array
	 * @static
	 * @return array
	 */
	static private function _getTypes()
	{
		if (self::$_types === null) {
			$class = new ReflectionClass('Cognosys\Alert');
			self::$_types = $class->getConstants();
		}
		
		return self::$_types;
	}
	
	/**
	 * If necessary, sets a new array in the session to support alert messages
	 * @static
	 * @return void
	 */
	static private function _assureSession()
	{
		if (Session::instance()->has(self::$_key) === false) {
			self::clear();
		}
	}
	
	/**
	 * Returns the array of alerts from the session
	 * @static
	 * @return array
	 */
	static private function _getFromSession()
	{
		self::_assureSession();
		return Session::instance()->get(self::$_key, false);
	}
	
	/**
	 * Sets an array of alerts in the session
	 * @static
	 * @return void
	 */
	static private function _setInSession(array $alerts)
	{
		Session::instance()->set(self::$_key, $alerts);
	}
}
