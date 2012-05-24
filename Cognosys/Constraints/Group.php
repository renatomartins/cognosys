<?php
namespace Cognosys\Constraints;
use Cognosys\Controller,
	Cognosys\Constraint,
	Cognosys\ConstraintArgumentException;

/**
 * Constraint to check if the user belongs to a certain group
 * @author Renato S. Martins <smartins.renato@gmail.com>
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
