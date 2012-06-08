<?php
namespace Cognosys\Validators;

/**
 * Validates a string
 * @author Renato S. Martins <smartins.renato@gmail.com>
 * @example
 * To check if a field has a value, use
 * <code>
 * String::required($this->title, 'title')
 * </code>
 */
class String extends Validator
{
	/**
	 * Check if a string has any length
	 * @static
	 * @param mixed $value
	 * @param string $field
	 * @return void
	 */
	static public function required(&$value, $field = null)
	{
		$value = self::_validate($value, $field);
		
		if (strlen($value) === 0) {
			return self::set($field, 'This field is required');
		}
	}
	
	/**
	 * Check if a string matches given value
	 * @static
	 * @param mixed $value
	 * @param string $expected
	 * @param string $field
	 * @return void
	 */
	static public function equals(&$value, $expected, $field = null)
	{
		$value = self::_validate($value, $field);
		
		if ($value !== $expected) {
			self::set($field, 'String does not equals required value');
		}
	}

	/**
	 * Checks if a string matches a given pattern
	 * @static
	 * @param mixed $value
	 * @param string $pattern A regex pattern
	 * @param string $field
	 * @return void
	 */
	static public function regex(&$value, $pattern, $field = null)
	{
		$value = self::_validate($value, $field);

		if ( ! preg_match($pattern, $value)) {
			self::set($field, 'String does not match required pattern');
		}
	}
	
	/**
	 * Checks if the length of a string if smaller than a maximum
	 * @static
	 * @param mixed $value
	 * @param int $max
	 * @param bool $trim if the string should be trimmed to the maximum length
	 * @param string $field
	 * @return void
	 */
	static public function max(&$value, $max, $trim = false, $field = null)
	{
		$value = self::_validate($value, $field);
		
		if (strlen($value) > $max) {
			if ($trim) {
				$value = substr($value, 0, $max);
			} else {
				self::set($field, "String must be smaller than {$max}");
			}
		}
	}
	
	/**
	 * Checks if the length of a string is greater than a minimum
	 * @static
	 * @param mixed $value
	 * @param int $min
	 * @param string $field
	 * @return void
	 */
	static public function min(&$value, $min, $field = null)
	{
		$value = self::_validate($value, $field);
		
		if (strlen($value) < $min) {
			self::set($field, "String must be larger than {$min}");
		}
	}
	
	static private function _validate($value, $field)
	{
		if (isset($value) === false || is_object($value) || is_array($value)) {
			self::set($field, 'String is not valid');
			return '';
		}
		
		return (string)$value;
	}
}
