<?php
namespace Cognosys\Constraints;
use Cognosys\Controller,
	Cognosys\Constraint,
	Cognosys\ConstraintArgumentException;

/**
 * Constraint to check if the current user does not belong to a certain group
 * @author Renato S. Martins <smartins.renato@gmail.com>
 */
class NotGroup extends Constraint
{
	/**
	 * @var string
	 */
	public $name;
	
	/**
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
		
		$user = $controller->getUser();
		return $user->hasGroup($this->name, $this->inherit) === false;
	}
}
