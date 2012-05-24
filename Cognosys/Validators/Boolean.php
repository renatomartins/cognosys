<?php
namespace Cognosys\Validators;

/**
 * Validates a boolean and sets alerts 
 * @author Renato S. Martins <smartins.renato@gmail.com>
 * @example
 * To cast a value from a checkbox to a boolean use
 * <code>
 * Boolean::cast($this->moderate, 'moderate');
 * </code>
 */
class Boolean extends Validator
{
	/**
	 * Assures the integrity of a boolean
	 * @static
	 * @param mixed $value
	 * @param string $field
	 * @return void
	 */
	static public function cast(&$value)
	{
		$value = self::_validate($value);
	}
	
	/**
	 * Checks if a boolean matches given value
	 * @static
	 * @param mixed $value
	 * @param bool $expected
	 * @param string $field
	 * @return void
	 */
	static public function equals(&$value, $expected, $field = null)
	{
		$value = self::_validate($value);
		
		if ($value !== $expected) {
			self::set($field, 'Boolean is not valid');
		}
	}
	
	static private function _validate($value)
	{
		if (is_string($value)) {
			$value = trim($value);
			if ($value === 'false' || $value === 'null') {
				return false;
			}
		}
		
		return (bool)$value;
	}
}
