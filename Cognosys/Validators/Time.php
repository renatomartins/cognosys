<?php
namespace Cognosys\Validators;
use Cognosys\Exception\UserError,
	\DateTime,
	\Exception;

/**
 * Validates a time (or date)
 * @author Renato S. Martins <smartins.renato@gmail.com>
 * @example
 * To check if a user model filled by a form post has the date as a valid value
 * <code>
 * Time::date($this->date, 'date');
 * </code>
 */
class Time extends Validator
{
	static public function date(&$value, $field = null)
	{
		$value = self::_validate($value, $field);
	}

	static public function required(&$value, $field = null)
	{
		self::_validate($value, $field);

		if ( ! $value instanceof DateTime) {
			self::set($field, 'Date is not valid');
		}
	}
	
	static private function _validate($value, $field)
	{
		if (is_string($value)) {
			try {
				return new DateTime($value);
			} catch (Exception $e) {
				// nothing to do, proceed to set the error
			}
		} elseif ($value instanceof DateTime) {
			return $value;
		}
		
		return null;
	}
}
