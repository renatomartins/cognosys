<?php
namespace Core\Constraints;
use Core\Controller,
	Core\Constraint,
	Core\ConstraintArgumentException;

/**
 * Constraint to check if the user belongs to a certain group
 * @author Renato S. Martins
 */
class Group extends Constraint
{
	/**
	 * @var string
	 */
	public $name;
	
	/**
	 * True if the groups bellow inherit the permission
	 * @var bool
	 */
	public $inherit;
	
	public function authorize(Controller $controller)
	{
		if (isset($this->name) === false) {
			throw new ConstraintArgumentException(get_class($this), 'name');
		}
		
		if (isset($this->inherit) === false) {
			$this->inherit = true;
		}
		
		if (self::$authorized) {
			return true;
		}
		
		// at this point, this is, without doubt, an instance of the entity User
		$user = $controller->getUser();
		return self::$authorized = $user->hasGroup($this->name, $this->inherit);
	}
}
