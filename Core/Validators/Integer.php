<?php
namespace Core\Validators;

/**
 * Validates an integer
 * @author Renato S. Martins
 * @example
 * To check if a user model filled by a form post has the group as a valid
 * foreign key
 * <code>
 * Integer::natural($this->group, 'group');
 * </code>
 */
class Integer extends Validator
{
	/**
	 * Checks if an integer is a natural number
	 * @static
	 * @param mixed $value
	 * @param string $field
	 * @return void
	 */
	static public function natural(&$value, $field = null)
	{
		$value = self::_validate($value, $field);
		
		if ($value <= 0) {
			self::set($field, 'Integer must be greater than zero');
		}
	}
	
	/**
	 * Checks if an integer matches given value
	 * @static
	 * @param mixed $value
	 * @param int $expected
	 * @param string $field
	 * @return void
	 */
	static public function equals(&$value, $expected, $field = null)
	{
		$value = self::_validate($value, $field);
		
		if ($value !== $expected) {
			self::set($field, 'Integer does not equals required value');
		}
	}
	
	/**
	 * Checks if an integer is smaller than a maximum value
	 * @static
	 * @param mixed $value
	 * @param int $max
	 * @param bool $correct if the max should replace the incorrect value
	 * @param string $field
	 * @return void
	 */
	static public function max(&$value, $max, $correct = false, $field = null)
	{
		$value = self::_validate($value, $field);
		
		if ($value > $max) {
			if ($correct) {
				$value = $max;
			} else {
				self::set($field, 'Integer is too large');
			}
		}
	}
	
	/**
	 * Checks if an integer is greater than a minimum value
	 * @static
	 * @param mixed $value
	 * @param int $min
	 * @param bool $correct if the min should replace the incorrect value
	 * @param string $field
	 * @return void
	 */
	static public function min(&$value, $min, $correct = false, $field = null)
	{
		$value = self::_validate($value, $field);
		
		if ($value < $min) {
			if ($correct) {
				$value = $min;
			} else {
				self::set($field, 'Integer is too small');
			}
		}
	}
	
	static private function _validate($value, $field)
	{
		if (isset($value) === false || is_object($value) || is_array($value)) {
			self::set($field, 'Integer is not valid');
			return 0;
		}
		
		return (int)$value;
	}
}
