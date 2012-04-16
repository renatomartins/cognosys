<?php
namespace Core\Constraints;
use Core\Constraint,
	Core\Controller,
	Core\ConstraintArgumentException;

/**
 * Constraint to check if the user has a role
 * @author Renato S. Martins
 */
class Role extends Constraint
{
	/**
	 * @var string
	 */
	public $name;
	
	public function authorize(Controller $controller)
	{
		if (isset($this->name) === false) {
			throw new ConstraintArgumentException(get_class($this), 'name');
		}
		
		if (self::$authorized) {
			return true;
		}
		
		$user = $controller->getUser();
		return self::$authorized = $user->hasRole($this->name);
	}
}
